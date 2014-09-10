<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'Cron:MainActivity.AutomaticCaseStatus',
    'entity' => 'Job',
    'params' => 
    array (
      'version' => 3,
      'name' => 'Call MainActivity.AutomaticCaseStatus API',
      'description' => 'Change status of Main activity cases from preperation to execution when travel date is passed. And from execution to debriefing when activity end date has passed',
      'run_frequency' => 'Daily',
      'api_entity' => 'MainActivity',
      'api_action' => 'AutomaticCaseStatus',
      'parameters' => '',
    ),
  ),
);