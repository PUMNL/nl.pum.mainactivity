<?php

function finalise_businesslink_programme_proposal_edit($form, &$form_state, $activity_id) {
    $config = businessconfig::singleton();

    $company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['id'];
    $name_contact = $config->custom_groups['Business_Programme']['fields']['Name_Company_Contact']['id'];
    $prefix_contact = $config->custom_groups['Business_Programme']['fields']['Prefix_Company_Contact']['id'];
    $initials_contact = $config->custom_groups['Business_Programme']['fields']['First_name_Initials_Company_Contact']['id'];
    $email_contact = $config->custom_groups['Business_Programme']['fields']['Email_Company_Contact']['id'];
    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $intended_result = $config->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];
    $thank_you = $config->custom_groups['Business_Programme']['fields']['Thank_you_Note']['id'];
    $cancelled = $config->custom_groups['Business_Programme']['fields']['Business_Visit_Cancelled']['id'];

    $params['id'] = $activity_id;
    $params['return.custom_'.$company] = 1;
    $params['return.custom_'.$location] = 1;
    $params['return.custom_'.$name_contact] = 1;
    $params['return.custom_'.$prefix_contact] = 1;
    $params['return.custom_'.$initials_contact] = 1;
    $params['return.custom_'.$email_contact] = 1;
    $params['return.custom_'.$from] = 1;
    $params['return.custom_'.$to] = 1;
    $params['return.custom_'.$aim] = 1;
    $params['return.custom_'.$intended_result] = 1;
    $params['return.custom_'.$result] = 1;
    $params['return.custom_'.$thank_you] = 1;
    $params['return.custom_'.$cancelled] = 1;

    $activity = civicrm_api3('Activity', 'getsingle', $params);

    $form['activity_id']['#type'] = 'hidden';
    $form['activity_id']['#name'] = 'activity_id';
    $form['activity_id']['#value'] = $activity_id;

    $form['company'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('Company'),
      '#default_value' => !empty($activity['custom_'.$company]) ? $activity['custom_'.$company] : '',
    );

    $form['location'] = array(
        '#type' => 'textfield',
        '#required' => true,
        '#title' => t('Location'),
        '#default_value' => !empty($activity['custom_'.$location]) ? $activity['custom_'.$location] : '',
    );

    $form['prefix_contact'] = array(
      '#type' => 'select',
      '#required' => true,
      '#title' => t('Prefix of Company Contact'),
      '#default_value' => !empty($activity['custom_'.$prefix_contact]) ? $activity['custom_'.$prefix_contact] : '',
      '#options' => array('' => t('- None -')) + CRM_Core_OptionGroup::valuesByID($config->custom_groups['Business_Programme']['fields']['Prefix_Company_Contact']['option_group_id']),
    );

    $form['initials_contact'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('First name / Initials of Company Contact'),
      '#default_value' => !empty($activity['custom_'.$initials_contact]) ? $activity['custom_'.$initials_contact] : '' ,
    );

    $form['name_contact'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('Name of Company Contact'),
      '#default_value' => !empty($activity['custom_'.$name_contact]) ? $activity['custom_'.$name_contact] : '',
    );

    $form['email_contact'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('E-mail of Company Contact'),
      '#default_value' => !empty($activity['custom_'.$email_contact]) ? $activity['custom_'.$email_contact] : '',
      '#rules' => array(
        'email',
      ),
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
        '#type' => 'markup',
        '#markup' => '<p><strong>'.t('Short description Aim of Visit').'</strong></p><p>'.(!empty($activity['custom_'.$aim]) ? $activity['custom_'.$aim] : '').'</p>'
    );

    $form['intended_result'] = array(
        '#type' => 'markup',
        '#markup' => '<p><strong>'.t('Short description of Intended Result of Visit').'</strong></p><p>'.(!empty($activity['custom_'.$intended_result]) ? $activity['custom_'.$intended_result] : '').'</p>'
    );

    $form['result'] = array(
      '#type' => 'textarea',
      '#required' => true,
      '#title' => t('Short description of Result of Visit'),
      '#default_value' => !empty($activity['custom_'.$result]) ? $activity['custom_'.$result] : '',
    );

    $form['thankyou'] = array(
      '#type' => 'select',
      '#required' => true,
      '#title' => t('Send thank you note?'),
      '#options' => array('' => t(' - Select -'), 'yes' => t('Yes'), 'no' => t('No')),
      '#default_value' => !empty($activity['custom_'.$thank_you]) ? 'yes': '',
    );

    $form['cancelled'] = array(
      '#type' => 'select',
      '#required' => true,
      '#title' => t('Cancelled?'),
      '#options' => array('yes' => t('Yes'), 'no' => t('No')),
      '#default_value' => !empty($activity['custom_'.$cancelled]) ? 'yes' : 'no',
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

function finalise_businesslink_programme_proposal($form, &$form_state, $case_id) {
    $config = businessconfig::singleton();

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

    $form['prefix_contact'] = array(
      '#type' => 'select',
      '#required' => true,
      '#title' => t('Prefix of Company Contact'),
      '#options' => array('' => t('- None -')) + CRM_Core_OptionGroup::valuesByID($config->custom_groups['Business_Programme']['fields']['Prefix_Company_Contact']['option_group_id']),
    );

    $form['initials_contact'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('First name / Initials of Company Contact'),
    );

    $form['name_contact'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('Name of Company Contact'),
    );

    $form['email_contact'] = array(
      '#type' => 'textfield',
      '#required' => true,
      '#title' => t('E-mail of Company Contact'),
      '#rules' => array(
        'email',
      ),
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

    $form['result'] = array(
      '#type' => 'textarea',
      '#required' => true,
      '#title' => t('Short description of Result of Visit'),
    );

    $form['thankyou'] = array(
      '#type' => 'select',
      '#required' => true,
      '#title' => t('Send thank you note?'),
      '#options' => array('' => t(' - Select -'), 'yes' => t('Yes'), 'no' => t('No')),
      '#default_value' => 'yes'
    );

    $form['cancelled'] = array(
      '#type' => 'select',
      '#required' => true,
      '#title' => t('Cancelled?'),
      '#options' => array('yes' => t('Yes'), 'no' => t('No')),
      '#default_value' => 'no',
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

function finalise_businesslink_programme_proposal_submit($form, &$form_state) {
    $config = businessconfig::singleton();
    $case_id = $form_state['values']['case_id'];

    $company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['id'];
    $name_contact = $config->custom_groups['Business_Programme']['fields']['Name_Company_Contact']['id'];
    $prefix_contact = $config->custom_groups['Business_Programme']['fields']['Prefix_Company_Contact']['id'];
    $initials_contact = $config->custom_groups['Business_Programme']['fields']['First_name_Initials_Company_Contact']['id'];
    $email_contact = $config->custom_groups['Business_Programme']['fields']['Email_Company_Contact']['id'];
    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $intended_result = $config->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];
    $thank_you = $config->custom_groups['Business_Programme']['fields']['Thank_you_Note']['id'];
    $cancelled = $config->custom_groups['Business_Programme']['fields']['Business_Visit_Cancelled']['id'];

    $params = array();
    $params['case_id'] = $case_id;
    $params['status_id'] = $form_state['values']['cancelled'] == 'yes' ? 3 : 2; //cancelled / completed
    $params['activity_type_id'] = $config->programme_activity_type_id;
    $params['custom_'.$company] = $form_state['values']['company'];
    $params['custom_'.$location] = $form_state['values']['location'];
    $params['custom_'.$name_contact] = $form_state['values']['name_contact'];
    $params['custom_'.$prefix_contact] = $form_state['values']['prefix_contact'];
    $params['custom_'.$initials_contact] = $form_state['values']['initials_contact'];
    $params['custom_'.$email_contact] = $form_state['values']['email_contact'];
    $params['custom_'.$from] = _mainactivity_date_submitted_string_value($form_state['values']['from_date']);
    $params['custom_'.$to] = _mainactivity_date_submitted_string_value($form_state['values']['to_date']);
    $params['custom_'.$result] = $form_state['values']['result'];
    $params['custom_'.$thank_you] = $form_state['values']['thankyou'];
    $params['custom_'.$cancelled] = $form_state['values']['cancelled'];

    $result = civicrm_api3('Activity', 'create', $params);
}

function finalise_businesslink_programme_proposal_edit_submit($form, &$form_state) {
    $config = businessconfig::singleton();
    $activity_id = $form_state['values']['activity_id'];

    $company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['id'];
    $name_contact = $config->custom_groups['Business_Programme']['fields']['Name_Company_Contact']['id'];
    $prefix_contact = $config->custom_groups['Business_Programme']['fields']['Prefix_Company_Contact']['id'];
    $initials_contact = $config->custom_groups['Business_Programme']['fields']['First_name_Initials_Company_Contact']['id'];
    $email_contact = $config->custom_groups['Business_Programme']['fields']['Email_Company_Contact']['id'];
    $location = $config->custom_groups['Business_Programme']['fields']['Location']['id'];
    $from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['id'];
    $to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['id'];
    $aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['id'];
    $intended_result = $config->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit']['id'];
    $result = $config->custom_groups['Business_Programme']['fields']['Result_of_Visit']['id'];
    $thank_you = $config->custom_groups['Business_Programme']['fields']['Thank_you_Note']['id'];
    $cancelled = $config->custom_groups['Business_Programme']['fields']['Business_Visit_Cancelled']['id'];

    $params = array();
    $params['id'] = $activity_id;
    $params['status_id'] = $form_state['values']['cancelled'] == 'yes' ? 3 : 2; //cancelled / completed
    $params['activity_type_id'] = $config->programme_activity_type_id;
    $params['custom_'.$company] = $form_state['values']['company'];
    $params['custom_'.$location] = $form_state['values']['location'];
    $params['custom_'.$name_contact] = $form_state['values']['name_contact'];
    $params['custom_'.$prefix_contact] = $form_state['values']['prefix_contact'];
    $params['custom_'.$initials_contact] = $form_state['values']['initials_contact'];
    $params['custom_'.$email_contact] = $form_state['values']['email_contact'];
    $params['custom_'.$from] = _mainactivity_date_submitted_string_value($form_state['values']['from_date']);
    $params['custom_'.$to] = _mainactivity_date_submitted_string_value($form_state['values']['to_date']);
    $params['custom_'.$result] = $form_state['values']['result'];
    $params['custom_'.$thank_you] = $form_state['values']['thankyou'];
    $params['custom_'.$cancelled] = $form_state['values']['cancelled'];
    $result = civicrm_api3('Activity', 'create', $params);
}