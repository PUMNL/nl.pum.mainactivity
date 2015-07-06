<?php

class CRM_Mainactivity_DebriefingConfig {
  
  protected static $singleton;

  protected $debriefing;
  
  protected $execution;
  
  protected $valid_case_types = array();
  
  protected $debriefing_act_rel = array();
  
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
    
    $this->loadDebriefingActivities();
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
  
  protected function debriefingActivityDefinition() {
    return array (
      'Advice' =>
        array(
          array(
            'activity_type' => 'Advice Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
          ),
          array(
            'activity_type' => 'Advice Debriefing Customer',
            'relationship_type' => 'Has authorised',
          ),
          array(
            'activity_type' => 'Advice Debriefing Expert',
            'relationship_type' => 'Expert',
          ),
          array(
            'activity_type' => 'Advice Debriefing PrOf',
            'relationship_type' => 'Project Officer for',
          ),
          array(
            'activity_type' => 'Advice Debriefing Representative',
            'relationship_type' => 'Representative is',
          ),
          array(
            'activity_type' => 'Advice Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
          ),
        ),
      'Seminar' =>
        array(
          array(
            'activity_type' => 'Seminar Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
          ),
          array(
            'activity_type' => 'Seminar Debriefing Customer',
            'relationship_type' => 'Has authorised',
          ),
          array(
            'activity_type' => 'Seminar Debriefing Expert',
            'relationship_type' => 'Expert',
          ),
          array(
            'activity_type' => 'Seminar Debriefing PrOf',
            'relationship_type' => 'Project Officer for',
          ),
          array(
            'activity_type' => 'Seminar Debriefing Representative',
            'relationship_type' => 'Representative is',
          ),
          array(
            'activity_type' => 'Seminar Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
          ),
        ),
      'RemoteCoaching' =>
        array(
          array(
            'activity_type' => 'Remote Coaching Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing Customer',
            'relationship_type' => 'Has authorised',
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing Expert',
            'relationship_type' => 'Expert',
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing PrOf',
            'relationship_type' => 'Project Officer for',
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing Representative',
            'relationship_type' => 'Representative is',
          ),
          array(
            'activity_type' => 'Remote Coaching Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
          ),
        ),
      'Business' =>
        array(
          array(
            'activity_type' => 'Business Debriefing CC',
            'relationship_type' => 'Country Coordinator is',
          ),
          array(
            'activity_type' => 'Business Debriefing Customer',
            'relationship_type' => 'Has authorised',
          ),
          array(
            'activity_type' => 'Business Debriefing Expert',
            'relationship_type' => 'Expert',
          ),
          array(
            'activity_type' => 'Business Debriefing PrOf',
            'relationship_type' => 'Project Officer for',
          ),
          array(
            'activity_type' => 'Business Debriefing SC',
            'relationship_type' => 'Sector Coordinator',
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

