<?php

/* 
 * change the status of a case based on the current status (Preperation) and the travel departure date, set status to Execution
 * change the status of a case based on the current status (Execution) and the activity end date, set the status to debriefing
 */

class CRM_Mainactivity_AutomaticCaseStatus {
  
  protected $main_activity_info;
  protected $start_date;
  protected $end_date;
  
  protected $preperation;
  protected $execution;
  protected $debriefing;
  
  public function __construct() {     
     $this->main_activity_info = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'main_activity_info'));
     $this->start_date = civicrm_api3('CustomField', 'getsingle', array('name' => 'main_activity_start_date', 'custom_group_id' => $this->main_activity_info['id']));
     $this->end_date = civicrm_api3('CustomField', 'getsingle', array('name' => 'main_activity_end_date', 'custom_group_id' => $this->main_activity_info['id']));
     
     $case_status_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_status'));
     $this->preperation = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Preparation', 'option_group_id' => $case_status_id));
     $this->execution = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Execution', 'option_group_id' => $case_status_id));
     $this->debriefing = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Debriefing', 'option_group_id' => $case_status_id));
  }
  
  public function parseFromPreperationToExecution() {
    $this->parseCaseStatus($this->preperation['value'], $this->main_activity_info['table_name'], 'entity_id', $this->start_date['column_name'], $this->execution['value']);
  }
  
  public function parseFromExecutionToDebriefing() {
    $this->parseCaseStatus($this->execution['value'], $this->main_activity_info['table_name'], 'entity_id', $this->end_date['column_name'], $this->debriefing['value']);
  }
  
  protected function parseCaseStatus($current_status, $join_table, $join_field, $date_field, $new_status) {
    $sql = "SELECT `civicrm_case`.`id` AS `case_id`  FROM `civicrm_case` 
      INNER JOIN `{$join_table}` `ct` ON `civicrm_case`.`id` = `ct`.`{$join_field}`
      WHERE `civicrm_case`.`status_id` = '{$current_status}' 
      AND `ct`.`{$date_field}` IS NOT NULL
      AND DATE(`ct`.`{$date_field}`) <= CURDATE()";

    $dao = CRM_Core_DAO::executeQuery($sql);
    while($dao->fetch()) {
      $params = array();
      $params['id'] = $dao->case_id;
      $params['status_id'] = $new_status;
      civicrm_api3('Case', 'create', $params);
    }
        
  }
  
}