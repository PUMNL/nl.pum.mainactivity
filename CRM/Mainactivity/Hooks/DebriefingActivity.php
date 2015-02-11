<?php

class CRM_Mainactivity_Hooks_DebriefingActivity {
  
  public static function pre($op, $objectName, $id, &$params) {
    if ($op != 'edit' || $objectName != 'Case') {
      return;
    }

    if (!isset($params['case_status_id'])) {
      return; //case status param is not set so its not changed
    }
            
    $currentCase = civicrm_api3('Case', 'getsingle', array('id' => $id));
    if ($params['case_status_id'] == $currentCase['status_id']) {
      return; //case status is not changed
    }
    
    $config = CRM_Mainactivity_DebriefingConfig::singleton();
    if (!$config->isValidCaseType($currentCase['case_type_id'])) {
      return;
    }
    
    if ( $params['case_status_id'] != $config->getCaseStatusDebriefing('value', $currentCase['case_type_id'])) {
      return;
    }
    
    $date = new DateTime();    
    foreach($config->getDebriefingActivityDefinition($currentCase['case_type_id']) as $act) {
      $role_contact_id = false;
      try {
        $relParams = array(
          'case_id' => $id,
          'relationship_type_id' => $act['relationship_type_id'],
          'is_active' => 1,
          'return' => 'contact_id_b',
        );
        $role_contact_id = civicrm_api3('Relationship', 'getvalue', $relParams);
      } catch (Exception $e) {
        //do nothing
      }
      
      $act_params = array();
      $act_params['activity_type_id'] = $act['activity_type_id'];
      $act_params['case_id'] = $id;
      $act_params['status_id'] = 1; //scheduled
      $act_params['activity_date_time'] = $date->format('YmdHis');
      if ($role_contact_id) {
        $act_params['assignee_contact_id'] = $role_contact_id;
      }
        
      civicrm_api3('Activity', 'create', $act_params);
    }
  }
  
}

