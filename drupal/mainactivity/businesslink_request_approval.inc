<?php

function businesslink_request_approval_for_programme($form, &$form_state, $case_id) {

  $form['case_id']['#type'] = 'hidden';
  $form['case_id']['#name'] = 'case_id';
  $form['case_id']['#value'] = $case_id;

  $sc_id = _businesslink_request_approval_for_programme_get_sc_id($case_id);
  if (!$sc_id) {
    drupal_set_message(t('This business link does not have a sector coordinator'), 'error');
    drupal_goto(drupal_get_destination());
  }

  $sc = civicrm_api3('Contact', 'getvalue', array('return' => 'display_name', 'id' => $sc_id));
  $form['are_you_sure'] = array(
    '#type' => 'markup',
    '#markup' => '<p></p><em>'.t('Request approval for this business programme from your sector coordinator (@sc)', array(
        '@sc' => $sc,
      )).'</em></p>',
  );

  $form['submit_button'] = array(
    '#type' => 'submit',
    '#value' => t('Request approval'),
  );

  $form_state['redirect'] = array(
    array('query' => drupal_get_destination()),
  );

  return $form;

}

function businesslink_request_approval_for_programme_submit($form, &$form_state) {
  $config = businessconfig::singleton();
  $case_id = $form_state['values']['case_id'];

  $params = array();
  $params['case_id'] = $case_id;
  $params['status_id'] = $config->waiting_approval_status['value'];
  $params['activity_type_id'] = $config->request_sc_approval_activity_type['value'];
  $params['assignee_contact_id'] = _businesslink_request_approval_for_programme_get_sc_id($case_id);
  $result = civicrm_api3('Activity', 'create', $params);
}

function _businesslink_request_approval_for_programme_get_sc_id($case_id) {
  $config = businessconfig::singleton();

  $sql = "SELECT contact_id_b
          from civicrm_relationship
          where case_id = %1 and relationship_type_id = %2
          and is_active = 1
          AND (start_date is null or start_date <= NOW())
          and (end_date is null or end_date >= NOW())";
  $params[1] = array($case_id, 'Integer');
  $params[2] = array($config->sc_relationship_type['id'], 'Integer');
  return CRM_Core_DAO::singleValueQuery($sql, $params);
}