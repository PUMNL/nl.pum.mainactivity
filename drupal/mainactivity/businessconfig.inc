<?php

class businessconfig
{

    protected static $singleton;

    public $custom_groups = array();

    public $visitors_relationship_types = array();

    public $gender_options = array();

    public $country_options = array();

    public $nationality_options = array();

    public $participant_relationship_type;

    public $sc_relationship_type;

    public $request_sc_approval_activity_type;

    public $programme_activity_type_id;

    public $programme_activity_type_name;

    public $waiting_approval_status;

    protected function __construct()
    {
        civicrm_initialize();

        $this->custom_groups['Passport_Information'] = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Passport_Information'));
        $this->custom_groups['Passport_Information']['fields'] = array();
        $passport_fields = civicrm_api3('CustomField', 'get', array('custom_group_id' => $this->custom_groups['Passport_Information']['id']));
        foreach ($passport_fields['values'] as $field) {
            $this->custom_groups['Passport_Information']['fields'][$field['name']] = $field;
        }

        $this->custom_groups['Nationality'] = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Nationality'));
        $this->custom_groups['Nationality']['fields']['Nationality'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Nationality', 'custom_group_id' => $this->custom_groups['Nationality']['id']));

        $this->custom_groups['business_data'] = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Business_Data'));
        $this->custom_groups['business_data']['fields']['Number_of_Visitors'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Number_of_Visitors', 'custom_group_id' => $this->custom_groups['business_data']['id']));
        $this->custom_groups['business_data']['fields']['Open_for_Registration'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Open_for_Registration', 'custom_group_id' => $this->custom_groups['business_data']['id']));

        $this->custom_groups['Business_Programme'] = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Business_Programme'));
        $this->custom_groups['Business_Programme']['fields']['Naam_bedrijf'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Naam_bedrijf', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Location'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Location', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Visit_from'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Visit_from', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Visit_to'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Visit_ot', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Aim_of_Visit'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Short_description_Aim_of_Visit', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Result_of_Visit'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Short_description_Result_of_Visit', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Intended_Result', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));

        $this->custom_groups['Business_Programme']['fields']['Name_Company_Contact'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Name_Company_Contact', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['First_name_Initials_Company_Contact'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'First_name_Initials_Company_Contact', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Prefix_Company_Contact'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Prefix_Company_Contact', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Email_Company_Contact'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Email_Company_Contact', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));

        $this->custom_groups['Business_Programme']['fields']['Thank_you_Note'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Thank_you_Note', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Business_Visit_Cancelled'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Business_Visit_Cancelled', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));
        $this->custom_groups['Business_Programme']['fields']['Thank_you_Note_sent'] = civicrm_api3('CustomField', 'getsingle', array('name' => 'Thank_you_Note_sent', 'custom_group_id' => $this->custom_groups['Business_Programme']['id']));

        $this->visitors_relationship_types[] = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Has authorised'));
        $this->visitors_relationship_types[] = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Business participant is'));

        $this->participant_relationship_type = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Business participant is'));

        $this->sc_relationship_type = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Sector Coordinator'));

        $gender_option_group_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'gender'));
        $gender_options = civicrm_api3('OptionValue', 'get', array('option_group_id' => $gender_option_group_id));
        foreach ($gender_options['values'] as $gender) {
            $this->gender_options[$gender['value']] = $gender['label'];
        }

        $nationality_option_group_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'nationalities'));
        $nationality_options = civicrm_api3('OptionValue', 'get', array('option_group_id' => $nationality_option_group_id, 'options' => array('limit' => 1000)));
        foreach ($nationality_options['values'] as $nationality) {
            $this->nationality_options[$nationality['value']] = $nationality['label'];
        }

        $country_options = civicrm_api3('Country', 'get', array('options' => array('limit' => 1000)));
        foreach ($country_options['values'] as $country) {
            $this->country_options[$country['id']] = $country['name'];
        }

        $this->programme_activity_type_name = 'Business Programme';
        $activity_type_option_group = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'activity_type'));
        $this->programme_activity_type_id = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => $this->programme_activity_type_name, 'option_group_id' => $activity_type_option_group));
        $this->request_sc_approval_activity_type = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Request Approval Business Programme SC', 'option_group_id' => $activity_type_option_group));

        $activity_status_option_group = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'activity_status'));
        $this->waiting_approval_status = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Waiting Approval', 'option_group_id' => $activity_status_option_group));
    }

    /**
     *
     * @return businessconfig
     */
    public static function singleton()
    {
        if (!self::$singleton) {
            self::$singleton = new businessconfig();
        }
        return self::$singleton;
    }

    public static function getContactDetails($contact_id)
    {
        $config = businessconfig::singleton();

        $parameters['id'] = $contact_id;
        $parameters['return.display_name'] = 1;
        $parameters['return.birth_date'] = 1;
        $parameters['return.gender_id'] = 1;
        $parameters['return.job_title'] = 1;
        $parameters['return.custom_' . $config->custom_groups['Nationality']['fields']['Nationality']['id']] = 1;
        $parameters['return.custom_' . $config->custom_groups['Passport_Information']['fields']['Passport_Name_Last_Name']['id']] = 1;
        $parameters['return.custom_' . $config->custom_groups['Passport_Information']['fields']['Passport_Name']['id']] = 1;
        $parameters['return.custom_' . $config->custom_groups['Passport_Information']['fields']['Passport_Number']['id']] = 1;
        $parameters['return.custom_' . $config->custom_groups['Passport_Information']['fields']['Passport_Valid_until']['id']] = 1;

        $contact = civicrm_api3('Contact', 'getsingle', $parameters);

        $email = false;
        try {
            $email = civicrm_api3('Email', 'getsingle', array('contact_id' => $contact_id, 'is_primary' => 1));
        } catch (Exception $e) {
            //do nothing
        }

        $contact['email_id'] = $email ? $email['id'] : false;
        $contact['email'] = $email ? $email['email'] : '';

        return $contact;
    }

}