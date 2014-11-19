<?php

class mainactivity_debriefing {
  
  protected static $singleton;
  
  public $advice;
  
  public $remote_coaching;
  
  public $seminar;
  
  public $advice_debriefing_expert;
  
  public $advice_debriefing_customer;
  
  public $advice_debriefing_rep;
  
  public $seminar_debriefing_expert;
  
  public $seminar_debriefing_customer;
  
  public $seminar_debriefing_rep;
  
  public $remote_coaching_debriefing_expert;
  
  public $remote_coaching_debriefing_customer;
  
  public $remote_coaching_debriefing_rep;
  
  public $briefing_expert_act;
  
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
    $this->seminar = civicrm_api3('OptionValue', 'getvalue', array('name' => 'Seminar', 'option_group_id' => $case_type_id, 'return' => 'value'));
    $this->remote_coaching = civicrm_api3('OptionValue', 'getvalue', array('name' => 'RemoteCoaching', 'option_group_id' => $case_type_id, 'return' => 'value'));
    $this->advice_debriefing_expert = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Advice Debriefing Expert', 'option_group_id' => 2));
    $this->advice_debriefing_customer = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Advice Debriefing Customer', 'option_group_id' => 2));
    $this->advice_debriefing_rep = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Advice Debriefing Representative', 'option_group_id' => 2));
    $this->seminar_debriefing_expert = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Seminar Debriefing Expert', 'option_group_id' => 2));
    $this->seminar_debriefing_customer = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Seminar Debriefing Customer', 'option_group_id' => 2));
    $this->seminar_debriefing_rep = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Seminar Debriefing Representative', 'option_group_id' => 2));
    $this->remote_coaching_debriefing_expert = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Remote Coaching Debriefing Expert', 'option_group_id' => 2));
    $this->remote_coaching_debriefing_customer = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Remote Coaching Debriefing Customer', 'option_group_id' => 2));
    $this->remote_coaching_debriefing_rep = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Remote Coaching Debriefing Representative', 'option_group_id' => 2));
    
    $this->briefing_expert_act = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Briefing Expert', 'option_group_id' => 2));
  }
  
  public function getValidCaseTypesForView() {
    return array (
      $this->advice => $this->advice,
      $this->remote_coaching => $this->remote_coaching,
      $this->seminar => $this->seminar,
    );
  }
  
  public function getValidDebriefingActivityTypesForView() {
    return array (
      $this->advice_debriefing_expert => $this->advice_debriefing_expert,
      $this->advice_debriefing_customer => $this->advice_debriefing_customer,
      $this->advice_debriefing_rep => $this->advice_debriefing_rep,
      $this->seminar_debriefing_expert => $this->seminar_debriefing_expert,
      $this->seminar_debriefing_customer => $this->seminar_debriefing_customer,
      $this->seminar_debriefing_rep => $this->seminar_debriefing_rep,
      $this->remote_coaching_debriefing_expert => $this->remote_coaching_debriefing_expert,
      $this->remote_coaching_debriefing_customer => $this->remote_coaching_debriefing_customer,
      $this->remote_coaching_debriefing_rep => $this->remote_coaching_debriefing_rep,
      );
  }
  
  public function validCaseTypes($case_types) {
    foreach($case_types as $case_type) {
      if (in_array($case_type, $this->getValidCaseTypesForView())) {
        return true;
      }
    }
  }
  
}

