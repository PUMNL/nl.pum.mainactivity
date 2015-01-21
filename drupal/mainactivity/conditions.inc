<?php

function condition_update_form($form, &$form_state, $activity_id) {
  civicrm_initialize();
  $activity = civicrm_api3('Activity', 'getsingle', array('id' => $activity_id));
  
  $form['activity_id']['#type'] = 'hidden';
  $form['activity_id']['#name'] = 'activity_id';
  $form['activity_id']['#value'] = $activity_id;
  
  $form['condition_subject']['#type'] = 'markup';
  $form['condition_subject']['#markup'] = '<h2>'.$activity['subject'].'</h2>';
  
  $form['condition_details']['#type'] = 'markup';
  $form['condition_details']['#markup'] = '<div>'.$activity['details'].'</div>';
  
  $form['submit_button'] = array(
    '#type' => 'submit',
    '#value' => t('Mark condition as completed'),
  );
  return $form;
}

function condition_update_form_submit($form, &$form_state) {
  $activity_id = $form_state['values']['activity_id'];
  civicrm_api3('Activity', 'create', array('id' => $activity_id, 'status_id' => 2));
}