<?php

/**
 * MainActivity.AutomaticCaseStatus API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_main_activity_automaticcasestatus_spec(&$spec) {
}

/**
 * MainActivity.AutomaticCaseStatus API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_main_activity_automaticcasestatus($params) {
  $returnValues = array();
  
  $auto_case_status = new CRM_Mainactivity_AutomaticCaseStatus();
  $auto_case_status->parseFromExecutionToDebriefing();
  $auto_case_status->parseFromPreperationToExecution();  

  return civicrm_api3_create_success($returnValues, $params, 'MainActivity', 'AutomaticCaseStatus');

}

