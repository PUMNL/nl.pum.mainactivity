<?php

require_once 'mainactivity.civix.php';

function mainactivity_civicrm_pre($op, $objectName, $id, &$params ) {
  //create debriefing activities upon debriefing status change
  CRM_Mainactivity_Hooks_DebriefingActivity::pre($op, $objectName, $id, $params);
  //check if a status change of activity is allowed
  CRM_Mainactivity_Hooks_ConditionStatusChangeCheck::pre($op, $objectName, $id, $params);
}

function mainactivity_civicrm_permission( &$permissions ) {
  $prefix = ts('CiviCRM Main activity') . ': ';
  if (!is_array($permissions)) {
    $permissions = array();
  }
  $permissions['approve MA condition/contribution activity'] = $prefix . ts('approve condition/contribution activity');
}

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function mainactivity_civicrm_config(&$config) {
  _mainactivity_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function mainactivity_civicrm_xmlMenu(&$files) {
  _mainactivity_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function mainactivity_civicrm_install() {
  return _mainactivity_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function mainactivity_civicrm_uninstall() {
  return _mainactivity_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function mainactivity_civicrm_enable() {
  return _mainactivity_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function mainactivity_civicrm_disable() {
  return _mainactivity_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function mainactivity_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _mainactivity_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function mainactivity_civicrm_managed(&$entities) {
  return _mainactivity_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function mainactivity_civicrm_caseTypes(&$caseTypes) {
  _mainactivity_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function mainactivity_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _mainactivity_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
