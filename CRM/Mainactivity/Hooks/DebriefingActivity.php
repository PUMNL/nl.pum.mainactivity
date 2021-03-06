<?php

class CRM_Mainactivity_Hooks_DebriefingActivity {

  public static function createDebriefingActivities($new_status_id, $old_status_id, $case_type_id, $id) {

    if ($new_status_id == $old_status_id) {
      return; //case status is not changed
    }

    $config = CRM_Mainactivity_DebriefingConfig::singleton();
    if (!$config->isValidCaseType($case_type_id)) {
      return;
    }
    /*
     * issue 2857 if new status = expertDebriefingStatus then create debriefing activity for expert
     */
    if (method_exists('CRM_Casestatus_Execution', 'createDebriefingActivity')) {
      if ($new_status_id == $config->getDebriefingExpertStatusId()) {
        $expertDebriefingCaseTypes = $config->getDebriefingExpertActivityTypes();
        if (array_key_exists($case_type_id, $expertDebriefingCaseTypes)) {
          CRM_Casestatus_Execution::createDebriefingActivity($id, $case_type_id);
        }
      }
    }

    if ($new_status_id != $config->getCaseStatusDebriefing('value', $case_type_id)) {
      return;
    }

    $date = new DateTime();
    $case = civicrm_api3('Case', 'getsingle', array('id' => $id));
    foreach($config->getDebriefingActivityDefinition($case_type_id) as $act) {
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
      $act_params['target_id'] = $case['client_id'];

      // only create debriefing activity if no activity of the type on the case
      $activityCount = civicrm_api3("CaseActivity", "Getcount", $act_params);
      if ($activityCount == 0) {
        $act_params['status_id'] = 1; //scheduled
        $act_params['activity_date_time'] = $date->format('YmdHis');
        if ($role_contact_id) {
          $act_params['assignee_contact_id'] = $role_contact_id;
        }
        civicrm_api3('Activity', 'create', $act_params);
      }
    }
  }
  
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
    self::createDebriefingActivities($params['case_status_id'], $currentCase['status_id'], $currentCase['case_type_id'], $id);
  }
}

