<?php

class CRM_Mainactivity_CaseStatusConfig {
  
  protected static $singleton;
  
  protected $preperation;
  protected $execution;
  protected $debriefing;
  
  protected function __construct() {
    $case_status_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_status'));
     $this->preperation = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Preparation', 'option_group_id' => $case_status_id));
     $this->execution = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Execution', 'option_group_id' => $case_status_id));
     $this->debriefing = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Debriefing', 'option_group_id' => $case_status_id));
  }
  
  /**
   * 
   * @return CRM_Mainactivity_CaseStatusConfig
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Mainactivity_CaseStatusConfig();
    }
    return self::$singleton;
  }
  
  public function getCaseStatusPreperation($key) {
    return $this->preperation[$key];
  }
  
  public function getCaseStatusExecution($key) {
    return $this->execution[$key];
  }
  
  public function getCaseStatusDebriefing($key) {
    return $this->debriefing[$key];
  }
  
}

