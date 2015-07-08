<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */
class CRM_Mainactivity_Form_Report_Summary extends CRM_Report_Form {

  protected $_summary = NULL;

  protected $_add2groupSupported = FALSE;

  protected $_customGroupExtends = array('Case');
  
  protected $_activityLastCompleted = FALSE;
  
  protected $_activityNextScheduled = FALSE;
  
  function __construct() {
    $this->case_types    = CRM_Case_PseudoConstant::caseType();
    $this->case_statuses = CRM_Case_PseudoConstant::caseStatus();

    $this->deleted_labels = array('' => ts('- select -'), 0 => ts('No'), 1 => ts('Yes'));

    $this->_columns = array(
      'civicrm_c2' =>
      array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' =>
        array(
          'client_name' =>
          array(
            'name' => 'display_name',
            'title' => ts('Client'),
            'required' => TRUE,
          ),
          'id' =>
          array(
            'no_display' => TRUE,
            'required' => TRUE,
          ),
        ),
      ),
      'civicrm_c3' =>
      array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' =>
        array(
          'expert_name' =>
          array(
            'name' => 'display_name',
            'title' => ts('Expert'),
            'default' => TRUE,
          ),
          'id' =>
          array(
            'no_display' => TRUE,
            'required' => TRUE,
          ),
        ),
      ),
      'civicrm_address' =>
      array(
        'dao' => 'CRM_Core_DAO_Address',
        'grouping' => 'contact-fields',
        'fields' =>
        array(
          'street_address' => NULL,
          'city' => NULL,
          'postal_code' => NULL,
          'state_province_id' =>
          array('title' => ts('State/Province'),
          ),
        ),
        'order_bys' =>
        array('state_province_id' => array('title' => 'State/Province'),
          'city' => array('title' => 'City'),
          'postal_code' => array('title' => 'Postal Code'),
        ),
      ),
      'civicrm_country' =>
      array(
        'dao' => 'CRM_Core_DAO_Country',
        'fields' =>
        array(
          'name' =>
          array('title' => 'Country', 'default' => TRUE),
        ),
        'order_bys' =>
        array(
          'name' =>
          array('title' => 'Country'),
        ),
        'grouping' => 'contact-fields',
      ),
      'civicrm_case' =>
      array(
        'dao' => 'CRM_Case_DAO_Case',
        'fields' =>
        array(
          'id' =>
          array('title' => ts('Case ID'),
            'required' => TRUE,
            'no_display' => TRUE,
          ),
          'subject' => array(
            'title' => ts('Case Subject'), 'default' => TRUE,
          ),
          'status_id' => array(
            'title' => ts('Status'), 'default' => TRUE,
          ),
          'case_type_id' => array(
            'title' => ts('Case Type'), 'default' => TRUE,
          ),
          'start_date' => array(
            'title' => ts('Start Date'), 'default' => TRUE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'end_date' => array(
            'title' => ts('End Date'), 'default' => TRUE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'duration' => array(
            'title' => ts('Duration (Days)'), 'default' => FALSE,
          ),
        ),
        'filters' =>
        array('start_date' => array('title' => ts('Start Date'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'end_date' => array('title' => ts('End Date'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'case_type_id' => array('title' => ts('Case Type'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $this->case_types,
          ),
          'status_id' => array('title' => ts('Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $this->case_statuses,
          ),
          'is_deleted' => array('title' => ts('Deleted?'),
            'type' => CRM_Report_Form::OP_INT,
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => $this->deleted_labels,
            'default' => 0,
          ),
        ),
      ),
      'civicrm_case_status' => array(
        'order_bys' => array(
          'case_status' => array(
            'title' => ts('Case Status'),
            'name' => 'label',
          )
        )
      ),
      'civicrm_relationship' =>
      array(
        'dao' => 'CRM_Contact_DAO_Relationship',
      ),
      'civicrm_expert_relationship' =>
      array(
        'dao' => 'CRM_Contact_DAO_Relationship',
      ),
      'civicrm_case_contact' =>
      array(
        'dao' => 'CRM_Case_DAO_CaseContact',
      ),
      'civicrm_activity_next_scheduled' =>
      array(
        'dao' => 'CRM_Activity_DAO_Activity',
        'fields' =>
        array(
          'next_scheduled_activity_date' =>
          array(
            'name' => 'activity_date_time',
            'title' => ts('Date of the next scheduled activity in the case'),
            'default' => TRUE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'next_scheduled_activity_subject' =>
          array(
            'name' => 'subject',
            'default' => true,
            'title' => ts('Subject of the next scheduled activity in the case'),
          ),
          'next_scheduled_activity_type' =>
          array(
            'name' => 'activity_type_id',
            'default' => true,
            'title' => ts('Activity type of the next scheduled activity'),
          ),
        ),
        'order_bys' => array(
          'next_scheduled_activity_date' =>  array(
            'title' => ts('Date of the next scheduled activity in the case'),
            'name' => 'activity_date_time',
          ),
        ),
      ),
     'civicrm_activity_last_completed' =>
      array(
        'dao' => 'CRM_Activity_DAO_Activity',
        'fields' =>
        array(
          'last_completed_activity_date' =>
          array(
            'name' => 'activity_date_time',
            'title' => ts('Date of the last completed activity in the case'),
            'default' => TRUE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'last_completed_activity_subject' =>
          array(
            'name' => 'subject',
            'default' => true,
            'title' => ts('Subject of the last completed activity in the case'),
          ),
          'last_completed_activity_type' =>
          array(
            'name' => 'activity_type_id',
            'default' => true,
            'title' => ts('Activity type of the last completed activity'),
          ),
        ),
        'order_bys' => array(
          'last_completed_activity_date' =>  array(
            'title' => ts('Date of the last completed activity in the case'),
            'name' => 'activity_date_time',
          ),
        ),
      ),
    );

    parent::__construct();
  }

  function preProcess() {
    parent::preProcess();
  }

  function select() {
    $select = array();
    $this->_columnHeaders = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (CRM_Utils_Array::value('required', $field) ||
            CRM_Utils_Array::value($fieldName, $this->_params['fields'])
          ) {
            if ($fieldName == 'duration') {
              $select[] = "IF({$table['fields']['end_date']['dbAlias']} Is Null, '', DATEDIFF({$table['fields']['end_date']['dbAlias']}, {$table['fields']['start_date']['dbAlias']})) as {$tableName}_{$fieldName}";
            }
            else {
              $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
            }
            if ($tableName == 'civicrm_activity_next_scheduled') {
              $this->_activityNextScheduled = TRUE;
            }
            if ($tableName == 'civicrm_activity_last_completed') {
              $this->_activityLastCompleted = TRUE;
            }
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
          }
        }
      }
    }

    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }

  function from() {
    $session   = CRM_Core_Session::singleton();
    $userID    = $session->get('userID');
    
    $expert_rel_type_id = civicrm_api3('RelationshipType', 'getvalue', array('return' => 'id', 'name_a_b' => 'Expert'));
    $case_status_option_group_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_status'));
    
    $cc  = $this->_aliases['civicrm_case'];
    $c2  = $this->_aliases['civicrm_c2'];
    $c3  = $this->_aliases['civicrm_c3'];
    $cr  = $this->_aliases['civicrm_relationship'];
    $cr2  = $this->_aliases['civicrm_expert_relationship'];
    $ccc = $this->_aliases['civicrm_case_contact'];
    $case = $this->_aliases['civicrm_case'];
    $case_status = $this->_aliases['civicrm_case_status'];

    $this->_from = "
          FROM civicrm_case {$cc}
          inner join civicrm_relationship {$cr} on {$cc}.id = {$cr}.case_id AND ({$cr}.contact_id_a = {$userID} OR {$cr}.contact_id_b = {$userID})
          inner join civicrm_case_contact {$ccc} on {$ccc}.case_id = {$cc}.id
          inner join civicrm_contact {$c2} on {$c2}.id={$ccc}.contact_id
          left join civicrm_option_value {$case_status} on {$cc}.status_id = {$case_status}.value and {$case_status}.option_group_id = '{$case_status_option_group_id}'
      ";
    if ($this->isTableSelected('civicrm_c3')) {
      $this->_from .= "
          LEFT JOIN civicrm_relationship {$cr2} ON {$cc}.id = {$cr2}.case_id AND {$cr2}.contact_id_a = {$ccc}.contact_id AND {$cr2}.relationship_type_id = '{$expert_rel_type_id}'
          LEFT JOIN civicrm_contact {$c3} ON {$cr2}.contact_id_b = {$c3}.id
          ";
          
    }
          
    if ($this->isTableSelected('civicrm_country') || $this->isTableSelected('civicrm_address')) {
      $this->_from .= "
        LEFT JOIN civicrm_address {$this->_aliases['civicrm_address']}
        ON ({$c2}.id = {$this->_aliases['civicrm_address']}.contact_id AND
        {$this->_aliases['civicrm_address']}.is_primary = 1 ) ";
    }
    if ($this->isTableSelected('civicrm_country')) {
      $this->_from .= "
        LEFT JOIN civicrm_country {$this->_aliases['civicrm_country']}
        ON {$this->_aliases['civicrm_address']}.country_id = {$this->_aliases['civicrm_country']}.id
      ";
    }
    // Include clause for next scheduled activity of the case
    if ($this->_activityNextScheduled) {
      $this->_from .= " LEFT JOIN civicrm_activity {$this->_aliases['civicrm_activity_next_scheduled']} ON ( {$this->_aliases['civicrm_activity_next_scheduled']}.id = ( SELECT ca.id FROM civicrm_case_activity cca, civicrm_activity ca WHERE ca.id = cca.activity_id AND cca.case_id = {$case}.id AND ca.status_id = 1 ORDER BY ca.activity_date_time ASC LIMIT 1 ) )";
    }
    // Include clause for last completed activity of the case
    if ($this->_activityLastCompleted) {
      $this->_from .= " LEFT JOIN civicrm_activity {$this->_aliases['civicrm_activity_last_completed']} ON ( {$this->_aliases['civicrm_activity_last_completed']}.id = ( SELECT ca.id FROM civicrm_case_activity cca, civicrm_activity ca WHERE ca.id = cca.activity_id AND cca.case_id = {$case}.id AND ca.status_id = 2 ORDER BY ca.activity_date_time DESC LIMIT 1) )";
    }
  }

  function where() {
    $clauses = array();
    $this->_having = '';
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('filters', $table)) {
        foreach ($table['filters'] as $fieldName => $field) {
          $clause = NULL;
          if (CRM_Utils_Array::value("operatorType", $field) & CRM_Report_Form::OP_DATE) {
            $relative = CRM_Utils_Array::value("{$fieldName}_relative", $this->_params);
            $from     = CRM_Utils_Array::value("{$fieldName}_from", $this->_params);
            $to       = CRM_Utils_Array::value("{$fieldName}_to", $this->_params);

            $clause = $this->dateClause($field['dbAlias'], $relative, $from, $to,
              CRM_Utils_Array::value('type', $field)
            );
          }
          else {

            $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
            if ($fieldName == 'case_type_id') {
              $value = CRM_Utils_Array::value("{$fieldName}_value", $this->_params);
              if (!empty($value)) {
                $clause = "( {$field['dbAlias']} REGEXP '[[:<:]]" . implode('[[:>:]]|[[:<:]]', $value) . "[[:>:]]' )";
              }
              $op = NULL;
            }

            if ($op) {
              $clause = $this->whereClause($field,
                $op,
                CRM_Utils_Array::value("{$fieldName}_value", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_min", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_max", $this->_params)
              );
            }
          }

          if (!empty($clause)) {
            $clauses[] = $clause;
          }
        }
      }
    }

    if (empty($clauses)) {
      $this->_where = "WHERE ( 1 ) ";
    }
    else {
      $this->_where = "WHERE " . implode(' AND ', $clauses);
    }
  }

  function groupBy() {
    $this->_groupBy = "";
  }
  
  function modifyColumnHeaders() {
    $this->_columnHeaders['manage_case'] = array(
      'title' => '',
      'type' => CRM_Utils_Type::T_STRING,
    );
  }

  function postProcess() {

    $this->beginPostProcess();
    
    $sql = $this->buildQuery(TRUE);

    $rows = $graphRows = array();
    $this->buildRows($sql, $rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }

  function alterDisplay(&$rows) {
    $entryFound = FALSE;
    $activityTypes = CRM_Core_PseudoConstant::activityType(TRUE, TRUE);
    foreach ($rows as $rowNum => $row) {
      if (array_key_exists('civicrm_case_status_id', $row)) {
        if ($value = $row['civicrm_case_status_id']) {
          $rows[$rowNum]['civicrm_case_status_id'] = $this->case_statuses[$value];
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_case_case_type_id', $row) &&
        CRM_Utils_Array::value('civicrm_case_case_type_id', $rows[$rowNum])
      ) {
        $value   = $row['civicrm_case_case_type_id'];
        $typeIds = explode(CRM_Core_DAO::VALUE_SEPARATOR, $value);
        $value   = array();
        foreach ($typeIds as $typeId) {
          if ($typeId) {
            $value[$typeId] = $this->case_types[$typeId];
          }
        }
        $rows[$rowNum]['civicrm_case_case_type_id'] = implode(', ', $value);
        $entryFound = TRUE;
      }
      
      // convert Client ID to contact page
      if (CRM_Utils_Array::value('civicrm_c3_expert_name', $rows[$rowNum])) {
        $url = CRM_Utils_System::url("civicrm/contact/view" , "action=view&reset=1&cid=". $row['civicrm_c3_id'], $this->_absoluteUrl);
        $rows[$rowNum]['civicrm_c3_expert_name_link'] = $url;
        $rows[$rowNum]['civicrm_c3_expert_name_hover'] = ts("View client");
        $entryFound = TRUE;
      }
      
      // convert Client ID to contact page
      if (CRM_Utils_Array::value('civicrm_c2_client_name', $rows[$rowNum])) {
        $url = CRM_Utils_System::url("civicrm/contact/view" , "action=view&reset=1&cid=". $row['civicrm_c2_id'], $this->_absoluteUrl);
        $rows[$rowNum]['civicrm_c2_client_name_link'] = $url;
        $rows[$rowNum]['civicrm_c2_client_name_hover'] = ts("View client");
        $entryFound = TRUE;
      }
      
      if (array_key_exists('civicrm_case_id', $row) &&
        CRM_Utils_Array::value('civicrm_c2_id', $rows[$rowNum])
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view/case",
          'reset=1&action=view&cid=' . $row['civicrm_c2_id'] . '&id=' . $row['civicrm_case_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['manage_case'] = ts('Manage');
        $rows[$rowNum]['manage_case_link'] = $url;
        $rows[$rowNum]['manage_case_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }

      // convert Case ID and Subject to links to Manage Case
      if (array_key_exists('civicrm_case_id', $row) &&
        CRM_Utils_Array::value('civicrm_c2_id', $rows[$rowNum])
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view/case",
          'reset=1&action=view&cid=' . $row['civicrm_c2_id'] . '&id=' . $row['civicrm_case_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_case_id_link'] = $url;
        $rows[$rowNum]['civicrm_case_id_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_case_subject', $row) &&
        CRM_Utils_Array::value('civicrm_c2_id', $rows[$rowNum])
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view/case",
          'reset=1&action=view&cid=' . $row['civicrm_c2_id'] . '&id=' . $row['civicrm_case_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_case_subject_link'] = $url;
        $rows[$rowNum]['civicrm_case_subject_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_case_case_type_id', $row) &&
        CRM_Utils_Array::value('civicrm_c2_id', $rows[$rowNum])
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view/case",
          'reset=1&action=view&cid=' . $row['civicrm_c2_id'] . '&id=' . $row['civicrm_case_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_case_case_type_id_link'] = $url;
        $rows[$rowNum]['civicrm_case_case_type_id_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_case_status_id', $row) &&
        CRM_Utils_Array::value('civicrm_c2_id', $rows[$rowNum])
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view/case",
          'reset=1&action=view&cid=' . $row['civicrm_c2_id'] . '&id=' . $row['civicrm_case_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_case_status_id_link'] = $url;
        $rows[$rowNum]['civicrm_case_status_id_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_case_is_deleted', $row)) {
        $value = $row['civicrm_case_is_deleted'];
        $rows[$rowNum]['civicrm_case_is_deleted'] = $this->deleted_labels[$value];
        $entryFound = TRUE;
      }
      
      if (array_key_exists('civicrm_activity_next_scheduled_next_scheduled_activity_subject', $row) &&
        !CRM_Utils_Array::value('civicrm_activity_next_scheduled_next_scheduled_activity_subject', $row)
      ) {
        $rows[$rowNum]['civicrm_activity_next_scheduled_next_scheduled_activity_subject'] = ts('(No Subject)');
        $entryFound = TRUE;
      }
      
      if (array_key_exists('civicrm_activity_next_scheduled_next_scheduled_activity_type', $row)) {
        if ($value = $row['civicrm_activity_next_scheduled_next_scheduled_activity_type']) {
          $rows[$rowNum]['civicrm_activity_next_scheduled_next_scheduled_activity_type'] = $activityTypes[$value];
        }
        $entryFound = TRUE;
      }
      
      if (array_key_exists('civicrm_activity_last_completed_last_completed_activity_subject', $row) &&
        !CRM_Utils_Array::value('civicrm_activity_last_completed_last_completed_activity_subject', $row)
      ) {
        $rows[$rowNum]['civicrm_activity_last_completed_last_completed_activity_subject'] = ts('(No Subject)');
        $entryFound = TRUE;
      }
      
      if (array_key_exists('civicrm_activity_last_completed_last_completed_activity_type', $row)) {
        if ($value = $row['civicrm_activity_last_completed_last_completed_activity_type']) {
          $rows[$rowNum]['civicrm_activity_last_completed_last_completed_activity_type'] = $activityTypes[$value];
        }
        $entryFound = TRUE;
      }

      if (!$entryFound) {
        break;
      }
    }
  }
}

