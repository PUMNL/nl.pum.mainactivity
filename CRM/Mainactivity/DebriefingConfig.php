<?php

class CRM_Mainactivity_DebriefingConfig {
  
  protected static $singleton;

  protected $debriefing;
  
  protected $execution;
  
  protected $valid_case_types = array();
  
  protected $debriefing_act_rel = array();

  protected $debriefingExpertStatusId = NULL;
  protected $debriefingExpertActivityTypes = array();
  
  protected function __construct() {
    $case_status_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_status'));
    $this->debriefing = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Debriefing', 'option_group_id' => $case_status_id));
    $this->execution = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Execution', 'option_group_id' => $case_status_id));
    
    $case_type_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_type'));
    $advice = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Advice', 'option_group_id' => $case_type_id));
    $this->valid_case_types[$advice['value']] = $advice;
    $this->valid_case_types[$advice['value']]['case_status'] = $this->debriefing;
    
    $seminar = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Seminar', 'option_group_id' => $case_type_id));
    $this->valid_case_types[$seminar['value']] = $seminar;
    $this->valid_case_types[$seminar['value']]['case_status'] = $this->debriefing;
    
    $RemoteCoaching = civicrm_api3('OptionValue', 'getsingle', array('name' => 'RemoteCoaching', 'option_group_id' => $case_type_id));
    $this->valid_case_types[$RemoteCoaching['value']] = $RemoteCoaching;
    $this->valid_case_types[$RemoteCoaching['value']]['case_status'] = $this->debriefing;
    
    $Business = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Business', 'option_group_id' => $case_type_id));
    $this->valid_case_types[$Business['value']] = $Business;
    $this->valid_case_types[$Business['value']]['case_status'] = $this->execution;

    /*
     * issue 2857 different config for expert debriefing (to be done at execution status)
     */
    $this->debriefingExpertStatusId = $this->execution['value'];
    $optionValueName = $advice['label']." Debriefing Expert";
    $optionValueParams = array('option_group_id' => 2, 'name' => $optionValueName, 'return' => 'value');
    $this->debriefingExpertActivityTypes[$advice['value']] = civicrm_api3('OptionValue', 'Getvalue', $optionValueParams);
    $optionValueName = $seminar['label']." Debriefing Expert";
    $optionValueParams = array('option_group_id' => 2, 'name' => $optionValueName, 'return' => 'value');
    $this->debriefingExpertActivityTypes[$seminar['value']] = civicrm_api3('OptionValue', 'Getvalue', $optionValueParams);
    $optionValueName = $Business['label']." Debriefing Expert";
    $optionValueParams = array('option_group_id' => 2, 'name' => $optionValueName, 'return' => 'value');
    $this->debriefingExpertActivityTypes[$Business['value']] = civicrm_api3('OptionValue', 'Getvalue', $optionValueParams);

    $this->loadDebriefingActivities();
  }

  private function loadDebriefingExpertActivityTypes() {
  }
  /**
   * 
   * @return CRM_Mainactivity_DebriefingConfig
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Mainactivity_DebriefingConfig();
    }
    return self::$singleton;
  }
  
  public function getCaseStatusDebriefing($key, $case_type_id) {
    return $this->valid_case_types[$case_type_id]['case_status'][$key];
  }
  
  public function isValidCaseType($case_type_id) {
    if (isset($this->valid_case_types[$case_type_id])) {
      return true;
    }
    return false;
  }
  public function getDebriefingExpertStatusId() {
    return $this->debriefingExpertStatusId;
  }
  public function getDebriefingExpertActivityTypes() {
    return $this->debriefingExpertActivityTypes;
  }
  
  public function getDebriefingActivityDefinition($case_type_id) {
    $case_name = false;
    if (isset($this->valid_case_types[$case_type_id]) && isset($this->valid_case_types[$case_type_id]['name'])) {
      $case_name = $this->valid_case_types[$case_type_id]['name'];
    }
    if (!$case_name) {
      return false;
    }
    if (!isset($this->debriefing_act_rel[$case_name])) {
      return false;
    }
    return $this->debriefing_act_rel[$case_name];
  }

  public function getCaseTypeNameByCaseTypeId($case_type_id) {
    if (isset($this->valid_case_types[$case_type_id]) && isset($this->valid_case_types[$case_type_id]['name'])) {
      return $this->valid_case_types[$case_type_id]['name'];
    }
    return false;
  }

  public function debriefingActivityDefinition() {
    return array (
      'Advice' =>
        array(
          array(
            'activity_type' => 'Advice Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
            'is_debriefing_cc' => true,
            'custom_group_name' => 'Advice_Debriefing_CC',
            'summary_field_name' => 'Summary',
            'follow_up_field_name' => 'Follow_up_Activities',
          ),
          array(
            'activity_type' => 'Advice Debriefing Customer',
            'relationship_type' => 'Has authorised',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Advice Debriefing Representative',
            'relationship_type' => 'Representative is',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Advice Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
            'is_debriefing_cc' => false,
          ),
        ),
      'Seminar' =>
        array(
          array(
            'activity_type' => 'Seminar Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
            'is_debriefing_cc' => true,
            'custom_group_name' => 'Seminar_Debriefing_CC',
            'summary_field_name' => 'Summary',
            'follow_up_field_name' => 'Follow_up_Activities',
          ),
          array(
            'activity_type' => 'Seminar Debriefing Customer',
            'relationship_type' => 'Has authorised',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Seminar Debriefing Representative',
            'relationship_type' => 'Representative is',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Seminar Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
            'is_debriefing_cc' => false,
          ),
        ),
      'RemoteCoaching' =>
        array(
          array(
            'activity_type' => 'Remote Coaching Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
            'is_debriefing_cc' => true,
            'custom_group_name' => 'Remote_Coaching_Debriefing_CC',
            'summary_field_name' => 'Summary',
            'follow_up_field_name' => 'Follow_up_Activities',
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing Customer',
            'relationship_type' => 'Has authorised',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing Representative',
            'relationship_type' => 'Representative is',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing Expert',
            'relationship_type' => 'Expert',
            'is_debriefing_cc' => false,
          ),
        ),
      'Business' =>
        array(
          array(
            'activity_type' => 'Business Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
            'is_debriefing_cc' => true,
            'custom_group_name' => 'Business_Debriefing_CC',
            'summary_field_name' => false,
            'follow_up_field_name' => false,
          ),
          array(
            'activity_type' => 'Business Debriefing Customer',
            'relationship_type' => 'Has authorised',
            'is_debriefing_cc' => false,
          ),
          array(
            'activity_type' => 'Business Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
            'is_debriefing_cc' => false,
          ),
        ),
      );
  }
  
  protected function loadDebriefingActivities() {
    $case_activities = $this->debriefingActivityDefinition();
    foreach ($case_activities as $case => $activities) {
      foreach($activities as $key => $act) {
        $case_activities[$case][$key]['activity_type_id'] = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => $act['activity_type'], 'option_group_id' => 2));
        $case_activities[$case][$key]['relationship_type_id'] = civicrm_api3('RelationshipType', 'getvalue', array('return' => 'id', 'name_a_b' => $act['relationship_type']));
      }
    }
    
    $this->debriefing_act_rel = $case_activities;
  }
  
}

