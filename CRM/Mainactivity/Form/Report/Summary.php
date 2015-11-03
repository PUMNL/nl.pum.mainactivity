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

  // issue 2995 properties
  protected $_acceptMAProposalActivityTypeId = array();
  protected $_rejectMAProposalActivityTypeId = array();
  protected $_acceptMAProposalTableName = array();
  protected $_customerApprovesExpertTableName = array();
  protected $_briefingExpertActivityTypeId = array();
  protected $_userSelectList = array();

  /**
   * Constructor method
   */
  function __construct() {
    $this->case_types    = CRM_Case_PseudoConstant::caseType();
    $this->case_statuses = CRM_Case_PseudoConstant::caseStatus();
    $this->setActivityTypes();
    $this->setCustomGroups();
    $this->setUserSelectList();

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
        'filters' => array(
          'user_id' => array(
            'title' => ts('Main Activities for User'),
            'default' => 1,
            'pseudofield' => 1,
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => $this->_userSelectList,
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
        ),
      'civicrm_country' =>
        array(
          'dao' => 'CRM_Core_DAO_Country',
          'fields' =>
            array(
              'name' =>
                array('title' => 'Country', 'default' => TRUE),
            ),
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
          'case_type_id' => array(
            'title' => ts('Case Type'), 'default' => TRUE,
          ),
          'status_id' => array(
            'title' => ts('Status'), 'default' => TRUE,
          ),
        ),
        'filters' =>
        array(
          'case_type_id' => array('title' => ts('Case Type'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $this->case_types,
          ),
          'status_id' => array('title' => ts('Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $this->case_statuses,
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
      'case_status_weight' =>
      array(
        'dao' => 'CRM_Core_DAO_OptionValue',
        'fields' =>
          array(
            'case_status_label' =>
              array(
                'name' => 'label',
                'no_display' => TRUE,
                'required' => TRUE,
              ),
            'weight' =>
              array(
                'no_display' => TRUE,
                'required' => TRUE,
              ),
          ),
      ),
      'civicrm_case_activity' =>
      array(
        'dao' => 'CRM_Case_DAO_CaseActivity',
        'fields' =>
          array(
            'case_id' =>
              array(
                'no_display' => TRUE,
                'required' => TRUE,
              ),
            'activity_id' =>
              array(
                'no_display' => TRUE,
                'required' => TRUE,
              ),
          ),
      ),
      'civicrm_activity' =>
      array(
        'dao' => 'CRM_Activity_DAO_Activity',
        'fields' =>
          array(
            'activity_status_id' =>
              array(
                'name' => 'status_id',
                'no_display' => TRUE,
                'required' => TRUE,
              ),
            'activity_type_id' =>
              array(
                'no_display' => TRUE,
                'required' => TRUE,
              ),
            'activity_date_time' =>
              array(
                'no_display' => TRUE,
                'required' => TRUE,
              ),
          ),
      ),
    );

    parent::__construct();
  }

  /**
   * Overridden parent method to build select part of query
   */
  function select() {
    $select = array();
    $this->_columnHeaders = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (CRM_Utils_Array::value('required', $field) ||
            CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {
            $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
            if (isset($field['title'])) {
              $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
            }
          }
        }
      }
    }
    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }

  /**
   * Overridden parent method to build from part of query
   */

  function from() {
    $caseStatusOptionGroupId = civicrm_api3("OptionGroup", "getvalue",
      array('return' => "id", 'name' => "case_status"));
    $threepeasConfig = CRM_Threepeas_Config::singleton();
    $caseRelationConfig = CRM_Threepeas_CaseRelationConfig::singleton();
    $expertRelationshipTypeId = $threepeasConfig->expertRelationshipTypeId;
    $ccRelationshipTypeId = $caseRelationConfig->getRelationshipTypeId("country_coordinator");
    $scRelationshipTypeId = $caseRelationConfig->getRelationshipTypeId("sector_coordinator");
    $poRelationshipTypeId = $caseRelationConfig->getRelationshipTypeId("project_officer");
    $cc  = $this->_aliases['civicrm_case'];
    $c2  = $this->_aliases['civicrm_c2'];
    $c3  = $this->_aliases['civicrm_c3'];
    $cr  = $this->_aliases['civicrm_relationship'];
    $cr2  = $this->_aliases['civicrm_expert_relationship'];
    $ccc = $this->_aliases['civicrm_case_contact'];
    $cca = $this->_aliases['civicrm_case_activity'];
    $act = $this->_aliases['civicrm_activity'];
    $csw = $this->_aliases['case_status_weight'];

    $this->_from = "
          FROM civicrm_case {$cc}
          INNER JOIN civicrm_relationship {$cr} ON {$cc}.id = {$cr}.case_id
            AND ({$cr}.relationship_type_id IN ({$ccRelationshipTypeId},{$scRelationshipTypeId},{$poRelationshipTypeId}))
          INNER JOIN civicrm_case_contact {$ccc} ON {$ccc}.case_id = {$cc}.id
          INNER JOIN civicrm_contact {$c2} ON {$c2}.id={$ccc}.contact_id
      ";

    if ($this->isTableSelected('civicrm_c3')) {
      $this->_from .= "
          LEFT JOIN civicrm_relationship {$cr2} ON {$cc}.id = {$cr2}.case_id
            AND {$cr2}.contact_id_a = {$ccc}.contact_id AND {$cr2}.relationship_type_id = '{$expertRelationshipTypeId}'
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
    if ($this->isTableSelected('civicrm_case_activity')) {
      $this->_from .= "
        LEFT JOIN civicrm_case_activity {$cca} ON {$cc}.id = {$cca}.case_id
        LEFT JOIN civicrm_activity {$act} ON {$cca}.activity_id = {$act}.id AND {$act}.is_current_revision = 1
          AND {$act}.activity_type_id IN ({$this->_briefingExpertActivityTypeId},
          {$this->_acceptMAProposalActivityTypeId}, {$this->_rejectMAProposalActivityTypeId})";
    }
    if ($this->isTableSelected('case_status_weight')) {
      $this->_from .= "
        LEFT JOIN civicrm_option_value {$csw} ON {$cc}.status_id = {$csw}.value AND {$csw}.option_group_id =
          {$caseStatusOptionGroupId} AND {$csw}.is_active = 1";
    }
  }

  /**
   * Overridden parent method to build where clause
   */
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
          } else {
            $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
            if ($fieldName == 'case_type_id') {
              $value = CRM_Utils_Array::value("{$fieldName}_value", $this->_params);
              if (!empty($value)) {
                $clause = "( {$field['dbAlias']} REGEXP '[[:<:]]" . implode('[[:>:]]|[[:<:]]', $value) . "[[:>:]]' )";
              }
              $op = NULL;
            }
            if ($fieldName == 'user_id') {
              $value = $this->setUserClause();
              if (!empty($value)) {
                $clause = "( {$this->_aliases['civicrm_relationship']}.contact_id_b = {$value} )";
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
    } else {
      $this->_where = "WHERE " . implode(' AND ', $clauses);
    }
  }

  /**
   * Overridden parent method to set the column headers
   */
  function modifyColumnHeaders() {
    $this->_columnHeaders['expert_approves'] =
      array('title' => ts('Expert approves Main Act.'), CRM_Utils_Type::T_STRING);
    $this->_columnHeaders['sc_approves'] =
      array('title' => ts('PQ approved by SC'), CRM_Utils_Type::T_STRING);
    $this->_columnHeaders['cc_approves'] =
      array('title' => ts('PQ approved by CC'), CRM_Utils_Type::T_STRING);
    $this->_columnHeaders['cust_approves'] =
      array('title' => ts('Customer aprroves Expert'), CRM_Utils_Type::T_STRING);
    $this->_columnHeaders['briefing_date'] =
      array('title' => ts('Briefing Date'), CRM_Utils_Type::T_DATE);
    $this->_columnHeaders['briefing_status'] =
      array('title' => ts('Briefing Status'), CRM_Utils_Type::T_STRING);
    $this->_columnHeaders['manage_case'] =
      array('title' => '','type' => CRM_Utils_Type::T_STRING,
    );
  }

  /**
   * Overridden parent method to process criteria into report with data
   */
  function postProcess() {

    $this->beginPostProcess();

    $sql = $this->buildQuery(TRUE);

    $rows = $graphRows = array();
    $this->buildRows($sql, $rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }

  /**
   * Overridden parent method to alter the display of each row
   * @param array $rows
   */
  function alterDisplay(&$rows) {
    $entryFound = FALSE;

    foreach ($rows as $rowNum => $row) {

      // build manage case url
      if (array_key_exists('civicrm_case_id', $row) && array_key_exists('civicrm_c2_id', $row)) {
        $caseUrl = CRM_Utils_System::url("civicrm/contact/view/case", 'reset=1&action=view&cid='
          . $row['civicrm_c2_id'] . '&id=' . $row['civicrm_case_id'], $this->_absoluteUrl);
        $rows[$rowNum]['manage_case'] = ts('Manage');
        $rows[$rowNum]['manage_case_link'] = $caseUrl;
        $rows[$rowNum]['manage_case_hover'] = ts("Manage Case");
      }

      if (array_key_exists('civicrm_case_status_id', $row)) {
        if ($value = $row['civicrm_case_status_id']) {
          $rows[$rowNum]['civicrm_case_status_id'] = $this->case_statuses[$value];
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_case_case_type_id', $row) && CRM_Utils_Array::value('civicrm_case_case_type_id', $rows[$rowNum])) {
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
      
      if (array_key_exists('civicrm_case_case_type_id', $row)) {
        $rows[$rowNum]['civicrm_case_case_type_id_link'] = $caseUrl;
        $rows[$rowNum]['civicrm_case_case_type_id_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }

      if (array_key_exists('civicrm_case_status_id', $row)) {
        $rows[$rowNum]['civicrm_case_status_id_link'] = $caseUrl;
        $rows[$rowNum]['civicrm_case_status_id_hover'] = ts("Manage Case");
        $entryFound = TRUE;
      }

      if (!$entryFound) {
        break;
      }
    }
  }
  /**
   * Overridden parent method orderBy (issue 2995 order by status on weight)
   */
  function orderBy() {
    $this->_orderBy  = "";
    $this->_sections = array();
    $this->storeOrderByArray();
    $this->_orderByArray[] = $this->_aliases['case_status_weight'].".weight";
    $startDate = $this->orderByMainActivityStartDate();
    if (!empty($startDate)) {
      $this->_orderByArray[] = $startDate;
    }
    if(!empty($this->_orderByArray) && !$this->_rollup == 'WITH ROLLUP'){
      $this->_orderBy = "ORDER BY " . implode(', ', $this->_orderByArray);
    }
    $this->assign('sections', $this->_sections);
  }

  /**
   * Method to set required Activity Types in class properties
   *
   * @access private
   */
  private function setActivityTypes() {
    $activityTypesArray = array(
      "Accept Main Activity Proposal" => "acceptMAProposal",
      "Reject Main Activity Proposal" => "rejectMAProposal",
      "Briefing Expert" => "briefingExpert"
    );
    foreach ($activityTypesArray as $name => $property) {
      $propertyName = "_".$property."ActivityTypeId";
      $activityType = CRM_Threepeas_Utils::getActivityTypeWithName($name);
      $this->$propertyName = $activityType['value'];
    }
  }

  /**
   * Method to set required Custom Groups in class properties
   *
   * @access private
   */
  private function setCustomGroups() {
    $customGroupArray = array(
      "Add_Keyqualifications" => "acceptMAProposal",
      "Customer_dis_agreement_of_Proposed_Expert" => "customerApprovesExpert"
    );
    foreach ($customGroupArray as $name => $property) {
      $propertyName = "_".$property . "TableName";
      $customGroup = CRM_Threepeas_Utils::getCustomGroup($name);
      $this->$propertyName = $customGroup['table_name'];
    }
  }

  /**
   * Method to get the users list for the user filter
   *
   * @acceess private
   */
  private function setUserSelectList() {
    $ccContacts = CRM_Threepeas_BAO_PumCaseRelation::getAllActiveRelationContacts('country_coordinator');
    $profContacts = CRM_Threepeas_BAO_PumCaseRelation::getAllActiveRelationContacts('project_officer');
    $sectorContacts = CRM_Threepeas_BAO_PumCaseRelation::getAllSectorCoordinators();
    $threepeasConfig = CRM_Threepeas_Config::singleton();
    $projectManagers = array();
    $pmContacts = array();
    $groupContactParams = array('group_id' => $threepeasConfig->projectmanagerGroupId);
    $this->_userSelectList[0] = 'current user';
    try {
      $projectManagers = civicrm_api3('GroupContact', 'Get', $groupContactParams);
    } catch (CiviCRM_API3_Exception $ex) {
    }
    foreach ($projectManagers['values'] as $projectManager) {
      $pmContacts[$projectManager['contact_id']] = $projectManager['contact_id'];
    }
    $allContacts = $ccContacts + $profContacts + $sectorContacts + $pmContacts;
    @uasort($allContacts, 'CRM_Threepeas_Utils::sortArrayByTitle');
    $this->_userSelectList[0] = 'current user';
    foreach ($allContacts as $contact) {
      $this->_userSelectList[$contact] = CRM_Threepeas_Utils::getContactName($contact);
    }
  }

  /**
   * Method if row already exists for caseId
   * @param int $caseId
   * @param array $rows
   * @return bool
   */
  private function rowExists($caseId, $rows) {
    foreach ($rows as $row) {
      if ($row['civicrm_case_id'] == $caseId) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Overridden parent method to set the found rows on distinct case_id
   */
  function setPager($rowCount = self::ROW_COUNT_LIMIT) {
    if ($this->_limit && ($this->_limit != '')) {
      $sql              = "SELECT COUNT(DISTINCT({$this->_aliases['civicrm_case']}.id)) ".$this->_from." ".$this->_where;
      $this->_rowsFound = CRM_Core_DAO::singleValueQuery($sql);
      $params           = array(
        'total' => $this->_rowsFound,
        'rowCount' => $rowCount,
        'status' => ts('Records') . ' %%StatusMessage%%',
        'buttonBottom' => 'PagerBottomButton',
        'buttonTop' => 'PagerTopButton',
        'pageID' => $this->get(CRM_Utils_Pager::PAGE_ID),
      );
      $pager = new CRM_Utils_Pager($params);
      $this->assign_by_ref('pager', $pager);
    }
  }

  /**
   * Overridden parent method to build the report rows
   *
   * @param string $sql
   * @param array $rows
   * @access public
   */
  function buildRows($sql, &$rows) {
    $rows = array();
    $dao = CRM_Core_DAO::executeQuery($sql);
    $this->modifyColumnHeaders();
    while ($dao->fetch()) {
      //only add to rows if there is no row yet for the case
      if ($this->rowExists($dao->civicrm_case_id, $rows) == FALSE) {
        $row = array();
        foreach ($this->_columnHeaders as $key => $value) {
          if (property_exists($dao, $key)) {
            $row[$key] = $dao->$key;
          }
        }
        $this->setAdditionalValues($dao->civicrm_case_id, $row);
        $rows[] = $row;
      } else {
        $this->updateRow($dao, $rows);
      }
    }
  }

  /**
   * Method to add the user clause for where
   */
  private function setUserClause() {
    if (!isset($this->_params['user_id_value']) || empty($this->_params['user_id_value'])) {
      $session = CRM_Core_Session::singleton();
      $userId = $session->get('userID');
    } else {
      $userId = $this->_params['user_id_value'];
    }
    return $userId;
  }

  /**
   * Method to update the row with detail information from activities
   *
   * @param object $dao
   * @param array $rows
   * @access private
   */
  private function updateRow($dao, &$rows) {

  }
  private function setAdditionalValues($caseId, &$row) {
    $mainActivityProposal = new CRM_Mainactivity_MainActivityProposal();
    $row['expert_approves'] = $mainActivityProposal->expertApproves($caseId);
    $row['cc_approves'] = $mainActivityProposal->ccApproves($caseId);
    $row['sc_approves'] = $mainActivityProposal->scApproves($caseId);
    $expert = new CRM_Mainactivity_Expert();
    $row['cust_approves'] = $expert->customApprovesExpert($caseId);
    $briefingActivity = $expert->getBriefingExpertActivity($caseId);
    if (!empty($briefingActivity)) {
      $row['briefing_status'] = $briefingActivity['status'];
      $row['briefing_date'] = date("d F Y", strtotime($briefingActivity['activity_date_time']));
    } else {
      $row['briefing_status'] = "n/a";
      $row['briefing_date'] = "n/a";
    }
  }
  private function orderByMainActivityStartDate() {
    $mainActivityCustomGroup = CRM_Threepeas_Utils::getCustomGroup('main_activity_info');
    $maStartDateCustomField = CRM_Threepeas_Utils::getCustomField($mainActivityCustomGroup['id'], "main_activity_start_date");
    $maStartDateField = "custom_".$maStartDateCustomField['id'];
    foreach ($this->_columns as $tableIdentifier => $tableData) {
      if (isset($tableData['fields'])) {
        foreach($tableData['fields'] as $fieldName => $fieldData) {
          if ($fieldName == $maStartDateField) {
            $alias = $fieldData['alias'];
          }
        }
      }
    }
    if (array_key_exists($maStartDateField, $this->_params['fields'])) {
      return $alias.".".$maStartDateCustomField['column_name'];
    } else {
      return null;
    }
  }
}

