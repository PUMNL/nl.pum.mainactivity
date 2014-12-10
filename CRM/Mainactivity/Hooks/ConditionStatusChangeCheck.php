<?php

class CRM_Mainactivity_Hooks_ConditionStatusChangeCheck {
  
  public static function pre($op, $objectName, $id, &$params) {
    if ($objectName != 'Activity') {
      return;
    }
    
    if ($op != 'edit') {
      return;
    }
    
    if (empty($params['original_id'])) {
      return;
    }
    
    if (CRM_Mainactivity_Hooks_ConditionStatusChangeCheck::hasPermissionToStatusChange()) {
      //do not check further user has permission to change status
      return;
    }
    
    //ok check the original activity type and status
    $activity = civicrm_api3('Activity', 'getsingle', array('id' => $params['original_id']));
    $config = CRM_Mainactivity_ApplicantPaysConfig::singleton();
    if ($activity['activity_type_id'] != $config->getActivityTypeId()) {
      //activity is not a condition activity
      return; 
    }
    
    //activity is a condition activity and user has not a permission to do a status change
    $params['status_id'] = $activity['status_id'];
  }
  
  protected static function hasPermissionToStatusChange() {
    if (CRM_Core_Permission::check('approve MA condition/contribution activity')) {
      return true;
    }
    return false;
  }
  
  
}