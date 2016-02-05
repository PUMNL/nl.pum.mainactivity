<?php

function businesslink_programme_proposal_delete($form, &$form_state, $activity_id) {

    $config = businessconfig::singleton();

  $company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['id'];
    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];

    $params['id'] = $activity_id;
    $params['return.custom_'.$company] = 1;
    $params['return.custom_'.$location] = 1;
    $params['return.custom_'.$from] = 1;
    $params['return.custom_'.$to] = 1;
    $params['return.custom_'.$aim] = 1;
    $params['return.custom_'.$result] = 1;

    $activity = civicrm_api3('Activity', 'getsingle', $params);

    $form['activity_id']['#type'] = 'hidden';
    $form['activity_id']['#name'] = 'activity_id';
    $form['activity_id']['#value'] = $activity_id;

    $from_date = new DateTime($activity['custom_'.$from]);
    $form['are_you_sure'] = array(
        '#type' => 'markup',
        '#markup' => '<p></p><em>'.t('Are you sure you want to remove the visit to @company, @location on @date', array(
                '@company' => $activity['custom_'.$company],
                '@location' => $activity['custom_'.$location],
                '@date' => format_date($from_date->getTimestamp(), 'custom', 'd-m-Y'),
            )).'</em></p>',
    );

    $form['submit_button'] = array(
        '#type' => 'submit',
        '#value' => t('Remove visit'),
    );

    $form_state['redirect'] = array(
        array('query' => drupal_get_destination()),
    );

    return $form;

}

function businesslink_programme_proposal_edit($form, &$form_state, $activity_id) {
    $config = businessconfig::singleton();

    $company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['id'];
    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit']['id'];

    $params['id'] = $activity_id;
    $params['return.custom_'.$company] = 1;
    $params['return.custom_'.$location] = 1;
    $params['return.custom_'.$from] = 1;
    $params['return.custom_'.$to] = 1;
    $params['return.custom_'.$aim] = 1;
    $params['return.custom_'.$result] = 1;

    $activity = civicrm_api3('Activity', 'getsingle', $params);

    $form['activity_id']['#type'] = 'hidden';
    $form['activity_id']['#name'] = 'activity_id';
    $form['activity_id']['#value'] = $activity_id;

    $form['company'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('Company'),
      '#default_value' => $activity['custom_'.$company],
    );

    $form['location'] = array(
        '#type' => 'textfield',
        '#required' => true,
        '#title' => t('Location'),
        '#default_value' => $activity['custom_'.$location],
    );

    $form['from_date'] = array(
        '#type' => 'date_popup',
        '#required' => true,
        '#title' => t('Visit from'),
        '#default_value' => _mainactivity_date_string_value($activity['custom_'.$from]),
        '#date_format' => 'Y-m-d',
        '#date_increment' => 1,
        '#date_year_range' => '-2:+2',
    );

    $form['to_date'] = array(
        '#type' => 'date_popup',
        '#required' => true,
        '#title' => t('Visit to'),
        '#default_value' => _mainactivity_date_string_value($activity['custom_'.$to]),
        '#date_format' => 'Y-m-d',
        '#date_increment' => 1,
        '#date_year_range' => '-2:+2',
    );

    $form['aim'] = array(
        '#type' => 'textarea',
        '#required' => true,
        '#title' => t('Short description Aim of Visit'),
        '#default_value' => $activity['custom_'.$aim],
    );

    $form['result'] = array(
        '#type' => 'textarea',
        '#required' => true,
        '#title' => t('Short description of Intended Result of Visit'),
        '#default_value' => $activity['custom_'.$result],
    );

    $form['submit_button'] = array(
        '#type' => 'submit',
        '#value' => t('Save'),
    );

    $form_state['redirect'] = array(
        array('query' => drupal_get_destination()),
    );

    return $form;
}

function businesslink_programme_proposal($form, &$form_state, $case_id) {
    $form['case_id']['#type'] = 'hidden';
    $form['case_id']['#name'] = 'case_id';
    $form['case_id']['#value'] = $case_id;

    $form['company'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('Company'),
    );

    $form['location'] = array(
        '#type' => 'textfield',
        '#required' => true,
        '#title' => t('Location'),
    );

    $form['from_date'] = array(
        '#type' => 'date_popup',
        '#required' => true,
        '#title' => t('Visit from'),
        '#date_format' => 'Y-m-d',
        '#date_increment' => 1,
        '#date_year_range' => '-2:+2',
    );

    $form['to_date'] = array(
        '#type' => 'date_popup',
        '#required' => true,
        '#title' => t('Visit to'),
        '#date_format' => 'Y-m-d',
        '#date_increment' => 1,
        '#date_year_range' => '-2:+2',
    );

    $form['aim'] = array(
        '#type' => 'textarea',
        '#required' => true,
        '#title' => t('Short description Aim of Visit'),
    );

    $form['result'] = array(
        '#type' => 'textarea',
        '#required' => true,
        '#title' => t('Short description of Intended Result of Visit'),
    );

    $form['submit_button'] = array(
        '#type' => 'submit',
        '#value' => t('Add visit'),
    );

    $form_state['redirect'] = array(
        array('query' => drupal_get_destination()),
    );

    return $form;
}

function businesslink_programme_proposal_submit($form, &$form_state) {
    $config = businessconfig::singleton();
    $case_id = $form_state['values']['case_id'];

    $company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['id'];
    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit']['id'];

    $params = array();
    $params['case_id'] = $case_id;
    $params['status_id'] = $config->submitted_status['value'];
    $params['subject'] = $company;
    $params['activity_type_id'] = $config->programme_activity_type_id;
    $params['custom_'.$company] = $form_state['values']['company'];
    $params['custom_'.$location] = $form_state['values']['location'];
    $params['custom_'.$from] = _mainactivity_date_submitted_string_value($form_state['values']['from_date']);
    $params['custom_'.$to] = _mainactivity_date_submitted_string_value($form_state['values']['to_date']);
    $params['custom_'.$aim] = $form_state['values']['aim'];
    $params['custom_'.$result] = $form_state['values']['result'];
    $result = civicrm_api3('Activity', 'create', $params);
}

function businesslink_programme_proposal_edit_submit($form, &$form_state) {
    $config = businessconfig::singleton();
    $activity_id = $form_state['values']['activity_id'];

    $company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['id'];
    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit']['id'];

    $params = array();
    $params['id'] = $activity_id;
    $params['status_id'] = $config->submitted_status['value'];
    $params['subject'] = $company;
    $params['activity_type_id'] = $config->programme_activity_type_id;
    $params['custom_'.$company] = $form_state['values']['company'];
    $params['custom_'.$location] = $form_state['values']['location'];
    $params['custom_'.$from] = _mainactivity_date_submitted_string_value($form_state['values']['from_date']);
    $params['custom_'.$to] = _mainactivity_date_submitted_string_value($form_state['values']['to_date']);
    $params['custom_'.$aim] = $form_state['values']['aim'];
    $params['custom_'.$result] = $form_state['values']['result'];
    $result = civicrm_api3('Activity', 'create', $params);
}

function businesslink_programme_proposal_delete_submit($form, &$form_state) {
    $config = businessconfig::singleton();
    $activity_id = $form_state['values']['activity_id'];

    $params = array();
    $params['id'] = $activity_id;
    $result = civicrm_api3('Activity', 'delete', $params);
}