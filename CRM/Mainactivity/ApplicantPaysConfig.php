<?php

class CRM_Mainactivity_ApplicantPaysConfig {
  
  protected static $singleton;
  
  protected $activity_type_id;
  
  protected function __construct() {
    $this->activity_type_id = civicrm_api3('OptionValue', 'getvalue', array('name' => 'Condition: Applicant Pays', 'option_group_id' => 2, 'return' => 'value'));
  }
  
  /**
   * 
   * @return CRM_Mainactivity_ApplicantPaysConfig
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Mainactivity_ApplicantPaysConfig();
    }
    return self::$singleton;
  }
  
  /**
   * 
   * @return id of the applicant pays activity
   */
  public function getActivityTypeId() {
    return $this->activity_type_id;
  }
  
}

