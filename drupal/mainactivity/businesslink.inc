<?php

function businesslink_change_person_form_validate($form, &$form_state) {
  if (!valid_email_address($form_state['values']['email'])) {
    form_set_error('email', 'Enter a valid e-mail address');
  }
}

function businesslink_change_person_form_submit($form, &$form_state) {
  $config = businessconfig::singleton();
  $case_id = $form_state['values']['case_id'];
  $contact_id = $form_state['values']['contact_id'];
  $businessCase = new businesscase($case_id);
  
  if ($businessCase->isOpenForRegistration()) {
    $contact = $config::getContactDetails($contact_id);
    
    $parameters['id'] = $contact_id;
    $birth_date = new DateTime();
    $birth_date->setDate($form_state['values']['birth_date']['year'],$form_state['values']['birth_date']['month'],$form_state['values']['birth_date']['day']);
    $parameters['birth_date'] = $birth_date->format('d-m-Y');
    $parameters['gender_id'] = $form_state['values']['gender'];
    $parameters['job_title'] = $form_state['values']['job_title'];
    $parameters['custom_'.$config->custom_groups['Nationality']['fields']['Nationality']['id']] = $form_state['values']['nationality'];
    
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name_Last_Name']['id']] = $form_state['values']['passport_last_name'];
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name']['id']] = $form_state['values']['passport_first_name'];
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Number']['id']] = $form_state['values']['passport_number'];
    $expire_date = new DateTime();
    $expire_date->setDate($form_state['values']['passport_expire_date']['year'],$form_state['values']['passport_expire_date']['month'],$form_state['values']['passport_expire_date']['day']);
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Valid_until']['id']] = $expire_date->format('d-m-Y');

    $c = civicrm_api3('Contact', 'create', $parameters);

    $address_parameters['id'] = $contact['address_id'];
    $address_parameters['street_address'] = $form_state['values']['address'];
    $address_parameters['postal_code'] = $form_state['values']['postal_code'];
    $address_parameters['city'] = $form_state['values']['city'];
    $address_parameters['country_id'] = $form_state['values']['Country'];
    $address_parameters['contact_id'] = $contact['id'];
    $address_parameters['is_primary'] = 1;
    $address_parameters['location_type_id'] = 1; //home

    civicrm_api3('Address', 'create', $address_parameters);

    if ($contact['phone_id']) {
      $phone_parameters['id'] = $contact['phone_id'];
    }
    if (!empty($form_state['values']['telephone'])) {
      $phone_parameters['phone'] = $form_state['values']['telephone'];
      $phone_parameters['contact_id'] = $contact['id'];
      $phone_parameters['is_primary'] = 1;
      
      civicrm_api3('Phone', 'create', $phone_parameters);
    } elseif (empty($form_state['values']['telephone']) && $contact['phone_id']) {
      civicrm_api3('Phone', 'delete', array('id' => $contact['phone_id']));
    }

    $email_parameters['id'] = $contact['email_id'];
    $email_parameters['email'] = $form_state['values']['email'];
    $email_parameters['contact_id'] = $contact['id'];
    $email_parameters['is_primary'] = 1;

    civicrm_api3('Email', 'create', $email_parameters);

  }
  drupal_goto('customer/business/participants/'.$case_id);
}

function businesslink_change_person_form($form, &$form_state, $case_id, $contact_id) {
  $config = businessconfig::singleton();
  $businessCase = new businesscase($case_id);
  
  $contact = $config::getContactDetails($contact_id);
  
  $form['contact_id']['#type'] = 'hidden';
  $form['contact_id']['#name'] = 'contact_id';
  $form['contact_id']['#value'] = $contact_id;
    
  $form['case_id']['#type'] = 'hidden';
  $form['case_id']['#name'] = 'case_id';
  $form['case_id']['#value'] = $case_id;
    
  $birth_date = new DateTime($contact['birth_date']);
  $form['birth_date'] = array(
    '#type' => 'date',
    '#required' => true,
    '#title' => t('Birthdate'),
    '#default_value' => array(
      'day' => $birth_date->format('d'),
      'month' => $birth_date->format('m'),
      'year' => $birth_date->format('Y'),
    ),
  );
  
  $form['gender'] = array(
    '#type' => 'select',
    '#required' => true,
    '#title' => t('Gender'),
    '#options' => $config->gender_options,
    '#default_value' => $contact['gender_id'],
  );
  
  $form['nationality'] = array(
    '#type' => 'select',
    '#required' => true,
    '#title' => t('Nationality'),
    '#options' => $config->nationality_options,
    '#default_value' => $contact['custom_'.$config->custom_groups['Nationality']['fields']['Nationality']['id']],
  );
  
  $form['address'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Address'),
    '#default_value' => $contact['address'],
  );
  
  $form['postal_code'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Postal code'),
    '#default_value' => $contact['postal_code'],
  );
  
  $form['city'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('City'),
    '#default_value' => $contact['city'],
  );
  
  $form['Country'] = array(
    '#type' => 'select',
    '#required' => true,
    '#title' => t('Country'),
    '#options' => $config->country_options,
    '#default_value' => $contact['country'],
  );
  
  $form['telephone'] = array(
    '#type' => 'textfield',
    '#required' => false,
    '#title' => t('Telephone'),
    '#default_value' => $contact['phone'],
  );
  
  $form['email'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('E-mail address'),
    '#default_value' => $contact['email'],
  );
  
  $form['job_title'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Job title'),
    '#default_value' => $contact['job_title'],
  );
  
  $form['passport_last_name'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Last name in passport'),
    '#default_value' => $contact['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name_Last_Name']['id']],
  );
  
  $form['passport_first_name'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('First name in passport'),
    '#default_value' => $contact['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name']['id']],
  );
  
  $form['passport_number'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Passport number'),
    '#default_value' => $contact['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Number']['id']],
  );
  
  $expire_date = new DateTime($contact['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Valid_until']['id']]);
  $form['passport_expire_date'] = array(
    '#type' => 'date',
    '#required' => true,
    '#title' => t('Passport expire date'),
    '#default_value' => array(
       'day' => $expire_date->format('d'),
      'month' => $expire_date->format('m'),
      'year' => $expire_date->format('Y'),
    )
  );
  
  $form['submit_button'] = array(
    '#type' => 'submit',
    '#value' => t('Update info'),
  );
  
  return $form;
}

function businesslink_register_newperson_form_validate($form, &$form_state) {
  if (!valid_email_address($form_state['values']['email'])) {
    form_set_error('email', 'Enter a valid e-mail address');
  }
}

function businesslink_register_newperson_form_submit($form, &$form_state) {
  $config = businessconfig::singleton();
  $case_id = $form_state['values']['case_id'];
  $businessCase = new businesscase($case_id);
  
  if ($businessCase->isOpenForRegistration()) {
    $parameters['contact_type'] = 'Individual';
    $parameters['first_name'] = $form_state['values']['first_name'];
    $parameters['last_name'] = $form_state['values']['last_name'];
    $birth_date = new DateTime();
    $birth_date->setDate($form_state['values']['birth_date']['year'],$form_state['values']['birth_date']['month'],$form_state['values']['birth_date']['day']);
    $parameters['birth_date'] = $birth_date->format('d-m-Y');
    $parameters['gender'] = $form_state['values']['gender'];
    $parameters['job_title'] = $form_state['values']['job_title'];
    $parameters['custom_'.$config->custom_groups['Nationality']['fields']['Nationality']['id']] = $form_state['values']['nationality'];
    
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name_Last_Name']['id']] = $form_state['values']['passport_last_name'];
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name']['id']] = $form_state['values']['passport_first_name'];
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Number']['id']] = $form_state['values']['passport_number'];
    $expire_date = new DateTime();
    $expire_date->setDate($form_state['values']['passport_expire_date']['year'],$form_state['values']['passport_expire_date']['month'],$form_state['values']['passport_expire_date']['day']);
    $parameters['custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Valid_until']['id']] = $expire_date->format('d-m-Y');

    $contact = civicrm_api3('Contact', 'create', $parameters);

    $address_parameters['street_address'] = $form_state['values']['address'];
    $address_parameters['postal_code'] = $form_state['values']['postal_code'];
    $address_parameters['city'] = $form_state['values']['city'];
    $address_parameters['country_id'] = $form_state['values']['Country'];
    $address_parameters['contact_id'] = $contact['id'];
    $address_parameters['is_primary'] = 1;
    $address_parameters['location_type_id'] = 1; //home

    civicrm_api3('Address', 'create', $address_parameters);

    if (!empty($form_state['values']['telephone'])) {
      $phone_parameters['phone'] = $form_state['values']['telephone'];
      $phone_parameters['contact_id'] = $contact['id'];
      $phone_parameters['is_primary'] = 1;
      $phone_parameters['location_type_id'] = 1; //home

      civicrm_api3('Phone', 'create', $phone_parameters);
    }

    $email_parameters['email'] = $form_state['values']['email'];
    $email_parameters['contact_id'] = $contact['id'];
    $email_parameters['is_primary'] = 1;
    $email_parameters['location_type_id'] = 1; //home

    civicrm_api3('Email', 'create', $email_parameters);
    
    $relationship['relationship_type_id'] = $config->participant_relationship_type['id'];
    $relationship['contact_id_a'] = $businessCase->getClientId();
    $relationship['contact_id_b'] = $contact['id'];
    civicrm_api3('Relationship', 'create', $relationship);

    $businessCase->createNewTravelCase($contact['id']);
  }
  drupal_goto('customer/business/participants/'.$case_id);
}

function businesslink_register_newperson_form($form, &$form_state, $case_id) {
  $config = businessconfig::singleton();
  $businessCase = new businesscase($case_id);
  
  $form['case_id']['#type'] = 'hidden';
  $form['case_id']['#name'] = 'case_id';
  $form['case_id']['#value'] = $case_id;
  
  $form['first_name'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('First name'),
  );
  
  $form['last_name'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Last name'),
  );
  
  $form['birth_date'] = array(
    '#type' => 'date',
    '#required' => true,
    '#title' => t('Birthdate'),
  );
  
  $form['gender'] = array(
    '#type' => 'select',
    '#required' => true,
    '#title' => t('Gender'),
    '#options' => $config->gender_options,
  );
  
  $form['nationality'] = array(
    '#type' => 'select',
    '#required' => true,
    '#title' => t('Nationality'),
    '#options' => $config->nationality_options,
  );
  
  $form['address'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Address'),
  );
  
  $form['postal_code'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Postal code'),
  );
  
  $form['city'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('City'),
  );
  
  $form['Country'] = array(
    '#type' => 'select',
    '#required' => true,
    '#title' => t('Country'),
    '#options' => $config->country_options,
  );
  
  $form['telephone'] = array(
    '#type' => 'textfield',
    '#required' => false,
    '#title' => t('Telephone'),
  );
  
  $form['email'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('E-mail address'),
  );
  
  $form['job_title'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Job title'),
  );
  
  $form['passport_last_name'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Last name in passport'),
  );
  
  $form['passport_first_name'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('First name in passport'),
  );
  
  $form['passport_number'] = array(
    '#type' => 'textfield',
    '#required' => true,
    '#title' => t('Passport number'),
  );
  
  $form['passport_expire_date'] = array(
    '#type' => 'date',
    '#required' => true,
    '#title' => t('Passport expire date'),
  );
  
  $form['submit_button'] = array(
    '#type' => 'submit',
    '#value' => t('Update registration'),
  );
  
  return $form;
}

function businesslink_registration_form($form, &$form_state, $case_id) {
  $businessCase = new businesscase($case_id);
  $num_guest = $businessCase->getMaxNumberOfParticipants();
  $open_for_registration = $businessCase->isOpenForRegistration();
  
  $possible_visitors[] = t('-- select --');
  $possible_visitors = $possible_visitors + _businesslink_get_related_contacts($businessCase->getClientId());
  $possible_visitors[-1] = t('-- register new person --');
  
  $form['case_id']['#type'] = 'hidden';
  $form['case_id']['#name'] = 'case_id';
  $form['case_id']['#value'] = $case_id;
  
  $form['participants']['#theme'] = 'table';
  $form['participants']['#header'] = array(t(''), t('Participant'), t('Travel status'), t(''));
  $form['participants']['#rows'] = array();
  for($i = 1; $i <= $num_guest; $i++) {
    $travelCase = $businessCase->getTravelCaseFor($i);
    $participant = '';
    $change = '';
    $travel_status = t('Not started');
    if ($travelCase && strtolower($travelCase['status']) != 'ongoing') {
      $travel_status = $travelCase['status'];
    }
    if ($open_for_registration && (!$travelCase || strtolower($travelCase['status']) == 'ongoing')) {
      $participant['data']['participant_'.$i]['#type'] = 'select';
      $participant['data']['participant_'.$i]['#name'] = 'participant_'.$i;
      $participant['data']['participant_'.$i]['#options'] = $possible_visitors;
      $participant['data']['participant_'.$i]['#value'] = 0;
      $participant['data']['participant_'.$i]['#attributes'] = array('onchange' => "form.submit('".'participant_'.$i."')");
      if ($travelCase) {
        $participant['data']['participant_'.$i]['#value'] = $travelCase['client_id'];
        $change['data']['#type'] = 'link';
        $change['data']['#href'] = 'customer/business/participants/'.$case_id.'/change/'.$travelCase['client_id'];
        $change['data']['#title'] = 'Check/change personal data';
        $change['data']['#text'] = 'Check/change personal data';
      }      
    } elseif ($travelCase) {
      $participant = $travelCase['display_name'];
    }

    $form['participants']['#rows'][] = array($i, $participant, $travel_status, $change);
  }

  $form['submit_button'] = array(
    '#type' => 'submit',
    '#value' => t('Update registration'),
  );
  
  return $form;
}

function _businesslink_get_related_contacts($customer_id) {
  $config = businessconfig::singleton();
  $relationship_type_ids = array();
  foreach($config->visitors_relationship_types as $rel_type) {
    $relationship_type_ids[] = $rel_type['id'];
  }
  
  $sql = "SELECT c.display_name, c.id from civicrm_contact c inner join civicrm_relationship r on c.id = r.contact_id_b where contact_id_a = %1 AND is_active = 1 AND (start_date is NULL or start_date <= NOW()) AND (end_date IS NULL OR end_date >= NOW()) AND relationship_type_id IN (".implode($relationship_type_ids, ",").")";
  $dao = CRM_Core_DAO::executeQuery($sql, array(1=>array($customer_id, 'Integer')));
  $return = array();
  while($dao->fetch()) {
    $return[$dao->id] = $dao->display_name;
  }
  return $return;
}

function businesslink_registration_form_validate($form, &$form_state) {
  $participants = array();
  
  $case_id = $form_state['values']['case_id'];
  $businessCase = new businesscase($case_id);
  $num_guest = $businessCase->getMaxNumberOfParticipants();
  for($i = 1; $i <= $num_guest; $i++) {
    $travelCase = $businessCase->getTravelCaseFor($i);
    if ($travelCase && strtolower($travelCase['status']) != 'ongoing') {
      $participants[] = $travelCase['client_id'];
    }
  }
  
  for($i = 1; $i <= $num_guest; $i++) {
    $participant = !empty($form_state['input']['participant_'.$i]) ? $form_state['input']['participant_'.$i] : false;
    if ($participant && in_array($participant, $participants)) {
      form_set_error('participant_'.$i, 'Person is already regitsered');
    } elseif ($participant) {
      $participants[] = $participant;
    }
  }
}

function businesslink_registration_form_submit($form, &$form_state) {
  $go_to_new = false;
  $case_id = $form_state['values']['case_id'];
  $businessCase = new businesscase($case_id);
  $num_guest = $businessCase->getMaxNumberOfParticipants();
  for($i = 1; $i <= $num_guest; $i++) {
    $current_participant = $businessCase->getTravelCaseFor($i);
    $participant = !empty($form_state['input']['participant_'.$i]) ? $form_state['input']['participant_'.$i] : false;
    
    if ($current_participant && $participant !== false && $current_participant['client_id'] != $participant) {
      $businessCase->closeTravelCase($current_participant['case_id']);
      $current_participant = false;
    }
    
    if (!$current_participant && $participant > 0) {
      $businessCase->createNewTravelCase($participant);
    } 
    
    if ($participant == -1) {
      drupal_goto('customer/business/participants/'.$case_id.'/newperson', array('absolute' => true));
    }
  }
}