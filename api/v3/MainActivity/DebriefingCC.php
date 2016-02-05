<?php

function civicrm_api3_main_activity_debriefingcc($params) {
  if (!isset($params['case_id'])) {
    return civicrm_api3_create_error('case_id is required');
  }

  $debriefing_cc = CRM_Mainactivity_DebriefingCC::singleton();
  $return = $debriefing_cc->getSummaryAndFollowUpByCaseId($params['case_id']);
  return $return;

}