<?php

class businessconfig {
  
  protected static $singleton;
  
  public $custom_groups = array();
  
  public $visitors_relationship_types = array();
  
  public $gender_options = array();
  
  public $country_options = array();
  
  public $nationality_options = array();
  
  public $participant_relationship_type;
  
  protected function __construct() {
    civicrm_initialize();
    
    $this->custom_groups['Passport_Information'] = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Passport_Information'));
    $this->custom_groups['Passport_Information']['fields'] = array();
    $passport_fields = civicrm_api3('CustomField', 'get', array('custom_group_id' => $this->custom_groups['Passport_Information']['id']));
    foreach($passport_fields['values'] as $field) {
      $this->custom_groups['Passport_Information']['fields'][$field['name']] = $field;
    }
    
    $this->custom_groups['Nationality'] = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Nationality'));
    $this->custom_groups['Nationality']['fields']['Nationality'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Nationality', 'custom_group_id' => $this->custom_groups['Nationality']['id']));
    
    $this->custom_groups['business_data'] = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Business_Data'));
    $this->custom_groups['business_data']['fields']['Number_of_Visitors'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Number_of_Visitors', 'custom_group_id' => $this->custom_groups['business_data']['id']));
    $this->custom_groups['business_data']['fields']['Open_for_Registration'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Open_for_Registration', 'custom_group_id' => $this->custom_groups['business_data']['id']));
    
    $this->visitors_relationship_types[] = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Has authorised'));
    $this->visitors_relationship_types[] = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Business participant is'));
    
    $this->participant_relationship_type = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Business participant is'));
    
    $gender_option_group_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'gender'));
    $gender_options = civicrm_api3('OptionValue', 'get', array('option_group_id' => $gender_option_group_id));
    foreach($gender_options['values'] as $gender) {
      $this->gender_options[$gender['value']] = $gender['label'];
    }
    
    $nationality_option_group_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'nationalities'));
    $nationality_options = civicrm_api3('OptionValue', 'get', array('option_group_id' => $nationality_option_group_id, 'options' => array('limit'=>1000)));
    foreach($nationality_options['values'] as $nationality) {
      $this->nationality_options[$nationality['value']] = $nationality['label'];
    }
    
    $country_options = civicrm_api3('Country', 'get', array('options' => array('limit'=>1000)));
    foreach($country_options['values'] as $country) {
      $this->country_options[$country['id']] = $country['name'];
    }
  }
  
  /**
   * 
   * @return businessconfig
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new businessconfig();
    }
    return self::$singleton;
  }
  
  public static function getContactDetails($contact_id) {
    $config = businessconfig::singleton();
    
    $parameters['id'] = $contact_id;
    $parameters['return.display_name'] = 1;
    $parameters['return.birth_date'] = 1;
    $parameters['return.gender_id'] = 1;
    $parameters['return.job_title'] = 1;
    $parameters['return.custom_'.$config->custom_groups['Nationality']['fields']['Nationality']['id']] = 1;
    $parameters['return.custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name_Last_Name']['id']] = 1;
    $parameters['return.custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Name']['id']] = 1;
    $parameters['return.custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Number']['id']] = 1;
    $parameters['return.custom_'.$config->custom_groups['Passport_Information']['fields']['Passport_Valid_until']['id']] = 1;
    
    $contact = civicrm_api3('Contact', 'getsingle', $parameters);
    
    $addressParameters['contact_id'] = $contact_id;
    $addressParameters['is_primary'] = 1;
    
    $address = false;
    try {
      $address = civicrm_api3('Address', 'getsingle', $addressParameters);
    } catch (Exception $e) {
      //do nothing
    }
    
    $phone = false;
    try {
      $phone = civicrm_api3('Phone', 'getsingle', array('contact_id' => $contact_id, 'is_primary' => 1));
    } catch (Exception $e) {
      //do nothing
    }
    
    $email = false;
    try {
      $email = civicrm_api3('Email', 'getsingle', array('contact_id' => $contact_id, 'is_primary' => 1));
    } catch (Exception $e) {
      //do nothing
    }
    
    $contact['address_id'] = $address ? $address['id'] : false;
    $contact['address'] = $address ? $address['street_address'] : '';
    $contact['postal_code'] = $address ? $address['postal_code'] : '';
    $contact['city'] = $address ? $address['city'] : '';
    $contact['country'] = $address ? $address['country_id'] : false;
    
    if ($phone) {
      $contact['phone_id'] = $phone['id'];
      $contact['phone'] = $phone['phone'];
    } else {
      $contact['phone_id'] = false;
      $contact['phone'] = '';
    }
    $contact['email_id'] = $email ? $email['id'] : false;
    $contact['email'] = $email ? $email['email'] : '';
    
    return $contact;
  } 
  
}