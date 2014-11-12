<?php

class mainactivity_debriefing {
  
  protected static $singleton;
  
  public $advice;
  
  public $expert_act;
  
  public $customer_act;
  
  public $rep_act;
  
  /**
   * 
   * @return mainactivity_debriefing
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new mainactivity_debriefing();
    }
    return self::$singleton;
  }
  
  protected function __construct() {
    $case_type_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_type'));
    $this->advice = civicrm_api3('OptionValue', 'getvalue', array('name' => 'Advice', 'option_group_id' => $case_type_id, 'return' => 'value'));
    $this->expert_act = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Debriefing Expert', 'option_group_id' => 2));
    $this->customer_act = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Debriefing Customer', 'option_group_id' => 2));
    $this->rep_act = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Debriefing Representative', 'option_group_id' => 2));
  }
  
}

