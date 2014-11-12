<?php

class CRM_Mainactivity_DebriefingConfig {
  
  protected static $singleton;

  protected $debriefing;
  
  protected $valid_case_types = array();
  
  protected $debriefing_act_rel = array();
  
  protected function __construct() {
    $case_status_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_status'));
    $this->debriefing = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Debriefing', 'option_group_id' => $case_status_id));
    
    $case_type_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_type'));
    $advice = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Advice', 'option_group_id' => $case_type_id));
    $this->valid_case_types[$advice['value']] = $advice;
    
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
  
  public function getCaseStatusDebriefing($key) {
    return $this->debriefing[$key];
  }
  
  public function isValidCaseType($case_type_id) {
    if (isset($this->valid_case_types[$case_type_id])) {
      return true;
    }
    return false;
  }
  
  public function getDebriefingActivityDefinition() {
    return $this->debriefing_act_rel;
  }
  
  protected function debriefingActivityDefinition() {
    return array(
      array(
        'activity_type' => 'Debriefing CC',
        'relationship_type' => 'Country Coordinator is',
      ),
      array(
        'activity_type' => 'Debriefing Customer',
        'relationship_type' => 'Has authorised',
      ),
      array(
        'activity_type' => 'Debriefing Expert',
        'relationship_type' => 'Expert',
      ),
      array(
        'activity_type' => 'Debriefing PrOf',
        'relationship_type' => 'Project Officer for',
      ),
      array(
        'activity_type' => 'Debriefing Representative',
        'relationship_type' => 'Representative is',
      ),
      array(
        'activity_type' => 'Debriefing SC',
        'relationship_type' => 'Sector Coordinator',
      ),
    );
  }
  
  protected function loadDebriefingActivities() {
    $activities = $this->debriefingActivityDefinition();
    foreach($activities as $key => $act) {
      $activities[$key]['activity_type_id'] = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => $act['activity_type'], 'option_group_id' => 2));
      $activities[$key]['relationship_type_id'] = civicrm_api3('RelationshipType', 'getvalue', array('return' => 'id', 'name_a_b' => $act['relationship_type']));
    }
    
    $this->debriefing_act_rel = $activities;
  }
  
}

