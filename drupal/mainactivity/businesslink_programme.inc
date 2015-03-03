<?php

function businesslink_programme_proposal_delete($form, &$form_state, $activity_id) {

    $config = businessconfig::singleton();

    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];

    $params['id'] = $activity_id;
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
        '#markup' => '<p></p><em>'.t('Are you sure you want to remove the visit to @location on @date', array(
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

    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];

    $params['id'] = $activity_id;
    $params['return.custom_'.$location] = 1;
    $params['return.custom_'.$from] = 1;
    $params['return.custom_'.$to] = 1;
    $params['return.custom_'.$aim] = 1;
    $params['return.custom_'.$result] = 1;

    $activity = civicrm_api3('Activity', 'getsingle', $params);

    $form['activity_id']['#type'] = 'hidden';
    $form['activity_id']['#name'] = 'activity_id';
    $form['activity_id']['#value'] = $activity_id;

    $form['location'] = array(
        '#type' => 'textfield',
        '#required' => true,
        '#title' => t('Location'),
        '#default_value' => $activity['custom_'.$location],
    );

    $from_date = new DateTime($activity['custom_'.$from]);
    $form['from_date'] = array(
        '#type' => 'date',
        '#required' => true,
        '#title' => t('Visit from'),
        '#pre_render' => array('_mainactivity_form_pre_render_date'),
        '#default_value' => array(
            'day' => (int) $from_date->format('d'),
            'month' => (int) $from_date->format('m'),
            'year' => (int) $from_date->format('Y'),
        ),
    );

    $to_date = new DateTime($activity['custom_'.$to]);
    $form['to_date'] = array(
        '#type' => 'date',
        '#required' => true,
        '#title' => t('Visit to'),
        '#pre_render' => array('_mainactivity_form_pre_render_date'),
        '#default_value' => array(
            'day' => (int) $to_date->format('d'),
            'month' => (int) $to_date->format('m'),
            'year' => (int) $to_date->format('Y'),
        ),
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
        '#title' => t('Short description Result of Visit'),
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

    $form['location'] = array(
        '#type' => 'textfield',
        '#required' => true,
        '#title' => t('Location'),
    );

    $form['from_date'] = array(
        '#type' => 'date',
        '#required' => true,
        '#title' => t('Visit from'),
        '#pre_render' => array('_mainactivity_form_pre_render_date'),
    );

    $form['to_date'] = array(
        '#type' => 'date',
        '#required' => true,
        '#title' => t('Visit to'),
        '#pre_render' => array('_mainactivity_form_pre_render_date'),
    );

    $form['aim'] = array(
        '#type' => 'textarea',
        '#required' => true,
        '#title' => t('Short description Aim of Visit'),
    );

    $form['result'] = array(
        '#type' => 'textarea',
        '#required' => true,
        '#title' => t('Short description Result of Visit'),
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

    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];

    $params = array();
    $params['case_id'] = $case_id;
    $params['status_id'] = 1; //scheduled
    $params['activity_type_id'] = $config->programme_activity_type_id;
    $params['custom_'.$location] = $form_state['values']['location'];
    $from_date = new DateTime();
    $from_date->setDate($form_state['values']['from_date']['year'],$form_state['values']['from_date']['month'],$form_state['values']['from_date']['day']);
    $params['custom_'.$from] = $from_date->format('Ymd');
    $to_date = new DateTime();
    $to_date->setDate($form_state['values']['to_date']['year'],$form_state['values']['to_date']['month'],$form_state['values']['to_date']['day']);
    $params['custom_'.$to] = $to_date->format('Ymd');
    $params['custom_'.$aim] = $form_state['values']['aim'];
    $params['custom_'.$result] = $form_state['values']['result'];
    $result = civicrm_api3('Activity', 'create', $params);
}

function businesslink_programme_proposal_edit_submit($form, &$form_state) {
    $config = businessconfig::singleton();
    $activity_id = $form_state['values']['activity_id'];

    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];

    $params = array();
    $params['id'] = $activity_id;
    $params['status_id'] = 1; //scheduled
    $params['activity_type_id'] = $config->programme_activity_type_id;
    $params['custom_'.$location] = $form_state['values']['location'];
    $from_date = new DateTime();
    $from_date->setDate($form_state['values']['from_date']['year'],$form_state['values']['from_date']['month'],$form_state['values']['from_date']['day']);
    $params['custom_'.$from] = $from_date->format('Ymd');
    $to_date = new DateTime();
    $to_date->setDate($form_state['values']['to_date']['year'],$form_state['values']['to_date']['month'],$form_state['values']['to_date']['day']);
    $params['custom_'.$to] = $to_date->format('Ymd');
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