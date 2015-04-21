<?php

class CRM_Mainactivity_Form_Report_SectorBusinessProgramm extends CRM_Report_Form {

  protected $_add2groupSupported = FALSE;

  protected $_customGroupExtends = array();

  function __construct() {
    $sectors = array();
    $enhancedTags = CRM_Mainactivity_EnhancedTags::singleton();
    foreach($enhancedTags->getSectorTree() as $tag_id) {
      if ($tag_id != $enhancedTags->getParentSectorTagId()) {
        try {
          $tag = civicrm_api3('Tag', 'getvalue', array(
            'id' => $tag_id,
            'parent_id' => $enhancedTags->getParentSectorTagId(),
            'return' => 'name'
          ));
          $sectors[$tag_id] = $tag;
        } catch (Exception $e) {
          //do nothing
        }
      }
    }
    asort($sectors);

    $this->_columns = array(
      'civicrm_activity' => array(
        'dao' => 'CRM_Activity_DAO_Activity',
        'fields' => array(
          'activity_type_id' =>
            array(
              'title' => ts('Activity Type'),
              'default' => false,
              'type' => CRM_Utils_Type::T_STRING,
            ),
          'subject' =>
            array(
              'title' => ts('Subject'),
              'default' => false,
            ),
          'activity_date_time' =>
            array(
              'title' => ts('Activity Date'),
              'default' => false,
            ),
          'status_id' =>
            array(
              'title' => ts('Activity Status'),
              'default' => TRUE,
              'type' => CRM_Utils_Type::T_STRING,
            ),
        ),
      ),
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' =>
          array(
            'id' =>
              array(
                'required' => TRUE,
                'no_display' => TRUE,
              ),
            'display_name' =>
              array(
                'title' => ts('Contact Name'),
                'default' => TRUE,
              ),
          ),
        'filters' =>
          array(
            'sector' => array(
              'pseudofield' => true,
              'title' => ts('Sector'),
              'type' => CRM_Report_Form::OP_INT,
              'operatorType' => CRM_Report_Form::OP_MULTISELECT,
              'options' => $sectors,
            ),
          )
      ),
      'civicrm_case' => array(
        'dao' => 'CRM_Case_DAO_Case',
        'fields' =>
          array(
            'id' =>
              array(
                'title' => ts('Case ID'),
                'required' => TRUE,
                'no_display' => TRUE,
              ),
            'subject' => array(
              'title' => ts('Case Subject'), 'default' => false,
            ),
            'case_status_id' => array(
              'title' => ts('Case Status'), 'default' => true, 'name' => 'status_id',
            ),
            'case_type_id' => array(
              'title' => ts('Case Type'), 'default' => false,
            ),
          ),
      ),
    );

    parent::__construct();

    $this->_customGroupExtends = array('Activity');
    $permCustomGroupIds[] = civicrm_api3('CustomGroup', 'getvalue', array('name' => 'Business_Programme', 'return' => 'id'));
    $this->addBusinessCustomDataToColumns(TRUE, $permCustomGroupIds);
  }

  function addBusinessCustomDataToColumns($addFields = TRUE, $permCustomGroupIds = array()) {
    if (empty($this->_customGroupExtends)) {
      return;
    }
    if (!is_array($this->_customGroupExtends)) {
      $this->_customGroupExtends = array($this->_customGroupExtends);
    }
    $customGroupWhere = '';
    if (!empty($permCustomGroupIds)) {
      $customGroupWhere = "cg.id IN (".implode(',' , $permCustomGroupIds).") AND";
    }
    $sql = "
SELECT cg.table_name, cg.title, cg.extends, cf.id as cf_id, cf.label, cf.is_searchable,
       cf.column_name, cf.data_type, cf.html_type, cf.option_group_id, cf.time_format
FROM   civicrm_custom_group cg
INNER  JOIN civicrm_custom_field cf ON cg.id = cf.custom_group_id
WHERE cg.extends IN ('" . implode("','", $this->_customGroupExtends) . "') AND
      {$customGroupWhere}
      cg.is_active = 1 AND
      cf.is_active = 1
ORDER BY cg.weight, cf.weight";
    $customDAO = CRM_Core_DAO::executeQuery($sql);

    $curTable = NULL;
    while ($customDAO->fetch()) {
      if ($customDAO->table_name != $curTable) {
        $curTable = $customDAO->table_name;
        $curFields = $curFilters = array();

        // dummy dao object
        $this->_columns[$curTable]['dao'] = 'CRM_Contact_DAO_Contact';
        $this->_columns[$curTable]['extends'] = $customDAO->extends;
        //$this->_columns[$curTable]['grouping'] = $customDAO->table_name;
        //$this->_columns[$curTable]['group_title'] = $customDAO->title;

        foreach (array(
                   'fields', 'filters', 'group_bys', 'order_bys') as $colKey) {
          if (!array_key_exists($colKey, $this->_columns[$curTable])) {
            $this->_columns[$curTable][$colKey] = array();
          }
        }
      }
      $fieldName = 'custom_' . $customDAO->cf_id;

      if ($addFields) {
        // this makes aliasing work in favor
        $curFields[$fieldName] = array(
          'name' => $customDAO->column_name,
          'title' => $customDAO->label,
          'dataType' => $customDAO->data_type,
          'htmlType' => $customDAO->html_type,
        );
        if ($customDAO->is_searchable) {
          $curFields[$fieldName]['default'] = true;
        }
      }
      if ($this->_customGroupFilters) {
        // this makes aliasing work in favor
        $curFilters[$fieldName] = array(
          'name' => $customDAO->column_name,
          'title' => $customDAO->label,
          'dataType' => $customDAO->data_type,
          'htmlType' => $customDAO->html_type,
        );
      }

      switch ($customDAO->data_type) {
        case 'Date':
          // filters
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_DATE;
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_DATE;
          // CRM-6946, show time part for datetime date fields
          if ($customDAO->time_format) {
            $curFields[$fieldName]['type'] = CRM_Utils_Type::T_TIMESTAMP;
          }
          break;

        case 'Boolean':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_SELECT;
          $curFilters[$fieldName]['options'] = array('' => ts('- select -'),
            1 => ts('Yes'),
            0 => ts('No'),
          );
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_INT;
          break;

        case 'Int':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_INT;
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_INT;
          break;

        case 'Money':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_FLOAT;
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_MONEY;
          break;

        case 'Float':
          $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_FLOAT;
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_FLOAT;
          break;

        case 'String':
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_STRING;

          if (!empty($customDAO->option_group_id)) {
            if (in_array($customDAO->html_type, array(
              'Multi-Select', 'AdvMulti-Select', 'CheckBox'))) {
              $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT_SEPARATOR;
            }
            else {
              $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT;
            }
            if ($this->_customGroupFilters) {
              $curFilters[$fieldName]['options'] = array();
              $ogDAO = CRM_Core_DAO::executeQuery("SELECT ov.value, ov.label FROM civicrm_option_value ov WHERE ov.option_group_id = %1 ORDER BY ov.weight", array(1 => array($customDAO->option_group_id, 'Integer')));
              while ($ogDAO->fetch()) {
                $curFilters[$fieldName]['options'][$ogDAO->value] = $ogDAO->label;
              }
            }
          }
          break;

        case 'StateProvince':
          if (in_array($customDAO->html_type, array(
            'Multi-Select State/Province'))) {
            $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT_SEPARATOR;
          }
          else {
            $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT;
          }
          $curFilters[$fieldName]['options'] = CRM_Core_PseudoConstant::stateProvince();
          break;

        case 'Country':
          if (in_array($customDAO->html_type, array(
            'Multi-Select Country'))) {
            $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT_SEPARATOR;
          }
          else {
            $curFilters[$fieldName]['operatorType'] = CRM_Report_Form::OP_MULTISELECT;
          }
          $curFilters[$fieldName]['options'] = CRM_Core_PseudoConstant::country();
          break;

        case 'ContactReference':
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_STRING;
          $curFilters[$fieldName]['name'] = 'display_name';
          $curFilters[$fieldName]['alias'] = "contact_{$fieldName}_civireport";

          $curFields[$fieldName]['type'] = CRM_Utils_Type::T_STRING;
          $curFields[$fieldName]['name'] = 'display_name';
          $curFields[$fieldName]['alias'] = "contact_{$fieldName}_civireport";
          break;

        default:
          $curFields[$fieldName]['type'] = CRM_Utils_Type::T_STRING;
          $curFilters[$fieldName]['type'] = CRM_Utils_Type::T_STRING;
      }

      if (!array_key_exists('type', $curFields[$fieldName])) {
        $curFields[$fieldName]['type'] = CRM_Utils_Array::value('type', $curFilters[$fieldName], array());
      }

      if ($addFields) {
        $this->_columns[$curTable]['fields'] = array_merge($this->_columns[$curTable]['fields'], $curFields);
        $this->_columns[$curTable]['order_bys'] = array_merge($this->_columns[$curTable]['order_bys'], $curFields);
      }
    }
  }

  function from() {
    $this->_from = "FROM `civicrm_activity` {$this->_aliases['civicrm_activity']}
                    INNER JOIN civicrm_case_activity cca on {$this->_aliases['civicrm_activity']}.id = cca.activity_id
                    INNER JOIN civicrm_case_contact ccc on cca.case_id = ccc.case_id
                    INNER JOIN civicrm_contact {$this->_aliases['civicrm_contact']} ON {$this->_aliases['civicrm_contact']}.id = ccc.contact_id
                    INNER JOIN civicrm_case {$this->_aliases['civicrm_case']} ON {$this->_aliases['civicrm_case']}.id = cca.case_id
                    INNER JOIN civicrm_entity_tag ON civicrm_entity_tag.entity_table = 'civicrm_contact' AND civicrm_entity_tag.entity_id = {$this->_aliases['civicrm_contact']}.id";


  }

  function where() {
    parent::where();

    $op = CRM_Utils_Array::value("sector_op", $this->_params);
    if ($op) {
      $field['dbAlias']= "`civicrm_entity_tag`.`tag_id`";
      $clause = $this->whereClause($field,
        $op,
        CRM_Utils_Array::value("sector_value", $this->_params),
        CRM_Utils_Array::value("sector_min", $this->_params),
        CRM_Utils_Array::value("sector_max", $this->_params)
      );

      if ($clause) {
        $this->_where .= " AND ".$clause;
      }
    }

    $activity_type = CRM_Core_OptionGroup::getValue('activity_type', 'Business Programme', 'name');
    $this->_where .= " AND {$this->_aliases['civicrm_activity']}.activity_type_id = '".$activity_type."'";

    $this->_where .= " AND {$this->_aliases['civicrm_activity']}.is_deleted = 0 AND {$this->_aliases['civicrm_activity']}.is_current_revision = 1";
  }

  function modifyColumnHeaders() {
    $this->_columnHeaders['manage_case'] = array(
      'title' => '',
      'type' => CRM_Utils_Type::T_STRING,
    );
  }

  function alterDisplay(&$rows) {
    $activityTypes = CRM_Core_OptionGroup::values('activity_type');
    $activityStatuses = CRM_Core_OptionGroup::values('activity_status');
    $caseTypes = CRM_Core_OptionGroup::values('case_type');
    $caseStatuses = CRM_Core_OptionGroup::values('case_status');

    $entryFound = false;
    $onHover = ts('View Contact Summary for this Contact');
    foreach ($rows as $rowNum => $row) {

      if (array_key_exists('civicrm_contact_display_name', $row) && $this->_outputMode != 'csv') {
        if ($value = $row['civicrm_contact_id']) {
          $url = CRM_Utils_System::url('civicrm/contact/view',
            'reset=1&cid=' . $value,
            TRUE
          );
          $rows[$rowNum]['civicrm_contact_display_name'] = "<a href='$url'>" . $row['civicrm_contact_display_name'] . '</a>';
        }
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_activity_activity_type_id', $row)) {
        if ($value = $row['civicrm_activity_activity_type_id']) {

          $value = explode(',', $value);
          foreach ($value as $key => $id) {
            $value[$key] = $activityTypes[$id];
          }

          $rows[$rowNum]['civicrm_activity_activity_type_id'] = implode(' , ', $value);
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_activity_status_id', $row)) {
        if ($value = $row['civicrm_activity_status_id']) {

          $value = explode(',', $value);
          foreach ($value as $key => $id) {
            $value[$key] = $activityStatuses[$id];
          }

          $rows[$rowNum]['civicrm_activity_status_id'] = implode(' , ', $value);
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_case_case_status_id', $row)) {
        if ($value = $row['civicrm_case_case_status_id']) {

          $value = explode(',', $value);
          foreach ($value as $key => $id) {
            $value[$key] = $caseStatuses[$id];
          }

          $rows[$rowNum]['civicrm_case_case_status_id'] = implode(' , ', $value);
          if ($row['civicrm_case_id'] && $row['civicrm_contact_id']) {
            $url = CRM_Utils_System::url("civicrm/contact/view/case",
              'reset=1&action=view&cid=' . $row['civicrm_contact_id'] . '&id=' . $row['civicrm_case_id'],
              true
            );
            $rows[$rowNum]['civicrm_case_case_status_id_link'] = $url;
            $rows[$rowNum]['civicrm_case_case_status_id_hover'] = ts('Manage case');
          }
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_case_case_type_id', $row)) {
        if ($value = $row['civicrm_case_case_type_id']) {

          $value = explode(CRM_Core_DAO::VALUE_SEPARATOR, $value);
          foreach ($value as $key => $id) {
            $id = str_replace(CRM_Core_DAO::VALUE_SEPARATOR, '', $id);
            if ($id) {
              $value[$key] = $caseTypes[$id];
            }
          }

          $rows[$rowNum]['civicrm_case_case_type_id'] = implode(' , ', $value);
          if ($row['civicrm_case_id'] && $row['civicrm_contact_id']) {
            $url = CRM_Utils_System::url("civicrm/contact/view/case",
              'reset=1&action=view&cid=' . $row['civicrm_contact_id'] . '&id=' . $row['civicrm_case_id'],
              true
            );
            $rows[$rowNum]['civicrm_case_case_type_id_link'] = $url;
            $rows[$rowNum]['civicrm_case_case_type_id_hover'] = ts('Manage case');
          }
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_case_id', $row) &&
        CRM_Utils_Array::value('civicrm_contact_id', $rows[$rowNum])
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view/case",
          'reset=1&action=view&cid=' . $row['civicrm_contact_id'] . '&id=' . $row['civicrm_case_id'],
          true
        );
        $rows[$rowNum]['manage_case'] = ts('Manage case');
        $rows[$rowNum]['manage_case_link'] = $url;
        $rows[$rowNum]['manage_case_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }

      if (!$entryFound) {
        break;
      }
    }
  }

}