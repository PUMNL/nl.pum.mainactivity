<?php

/* 
 * change the status of a case based on the current status (Preperation) and the travel departure date, set status to Execution
 * change the status of a case based on the current status (Execution) and the activity end date, set the status to debriefing
 */

class CRM_Mainactivity_AutomaticCaseStatus {
  
  protected $main_activity_info;
  protected $start_date;
  protected $end_date;
  
  public function __construct() {     
     $this->main_activity_info = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'main_activity_info'));
     $this->start_date = civicrm_api3('CustomField', 'getsingle', array('name' => 'main_activity_start_date', 'custom_group_id' => $this->main_activity_info['id']));
     $this->end_date = civicrm_api3('CustomField', 'getsingle', array('name' => 'main_activity_end_date', 'custom_group_id' => $this->main_activity_info['id']));
  }
  
  public function parseFromPreperationToExecution() {
    $config = CRM_Mainactivity_CaseStatusConfig::singleton();
    $this->parseCaseStatus($config->getCaseStatusPreperation('value'), $this->main_activity_info['table_name'], 'entity_id', $this->start_date['column_name'], $config->getCaseStatusExecution('value'));
  }
  
  public function parseFromExecutionToDebriefing() {
    $config = CRM_Mainactivity_CaseStatusConfig::singleton();
    $this->parseCaseStatus($config->getCaseStatusExecution('value'), $this->main_activity_info['table_name'], 'entity_id', $this->end_date['column_name'], $config->getCaseStatusDebriefing('value'));
  }
  
  protected function parseCaseStatus($current_status, $join_table, $join_field, $date_field, $new_status) {
    $sql = "SELECT `civicrm_case`.`id` AS `case_id`, civicrm_case.case_type_id  FROM `civicrm_case`
      INNER JOIN `{$join_table}` `ct` ON `civicrm_case`.`id` = `ct`.`{$join_field}`
      WHERE `civicrm_case`.`status_id` = '{$current_status}' 
      AND `ct`.`{$date_field}` IS NOT NULL
      AND DATE(`ct`.`{$date_field}`) <= CURDATE()";

    $dao = CRM_Core_DAO::executeQuery($sql);

    while($dao->fetch()) {
      $case = civicrm_api3('Case', 'getsingle', array('id' => $dao->case_id));
      $params = array();
      $params['id'] = $dao->case_id;
      $params['status_id'] = $new_status;
      civicrm_api3('Case', 'create', $params);

      //the pre hook doesn't get called when we use the api for updating
      //so make sure the debriefing activities are loaded as soon as a case reaches debriefing status
      CRM_Mainactivity_Hooks_DebriefingActivity::createDebriefingActivities($new_status, $current_status, $case['case_type_id'], $dao->case_id);
    }
        
  }
  
}