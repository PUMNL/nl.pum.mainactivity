<?php

class mainactivity_debriefing {

  protected static $singleton;

  public $advice;

  public $remote_coaching;

  public $seminar;

  public $business;

  public $factfinding;

  public $business_debriefing_expert;

  public $business_debriefing_customer;

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
    $og_casetype = civicrm_api3('OptionValue', 'get', array('option_group_name' => 'case_type', 'rowCount'=>0));
    $og_activitytype = civicrm_api3('OptionValue', 'get', array('option_group_name' => 'activity_type', 'rowCount'=>0));
    $case_type_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_type'));

    foreach($og_casetype['values'] as $key => $value){
      if($value['name'] == 'Advice'){
        $this->advice = $value['value'];
      }
      if($value['name'] == 'Seminar'){
        $this->seminar = $value['value'];
      }
      if($value['name'] == 'Business'){
        $this->business = $value['value'];
      }
      if($value['name'] == 'FactFinding'){
        $this->factfinding = $value['value'];
      }
      if($value['name'] == 'RemoteCoaching'){
        $this->remote_coaching = $value['value'];
      }
    }

    foreach($og_activitytype['values'] as $key => $value){
      if($value['name'] == 'Business Debriefing Expert'){
        $this->business_debriefing_expert = $value['value'];
      }
      if($value['name'] == 'Business Debriefing Customer'){
        $this->business_debriefing_customer = $value['value'];
      }
      if($value['name'] == 'Advice Debriefing Expert'){
        $this->advice_debriefing_expert = $value['value'];
      }
      if($value['name'] == 'Advice Debriefing Customer'){
        $this->advice_debriefing_customer = $value['value'];
      }
      if($value['name'] == 'Advice Debriefing Representative'){
        $this->advice_debriefing_rep = $value['value'];
      }
      if($value['name'] == 'Seminar Debriefing Expert'){
        $this->seminar_debriefing_expert = $value['value'];
      }
      if($value['name'] == 'Seminar Debriefing Customer'){
        $this->seminar_debriefing_customer = $value['value'];
      }
      if($value['name'] == 'Seminar Debriefing Representative'){
        $this->seminar_debriefing_rep = $value['value'];
      }
      if($value['name'] == 'Remote Coaching Debriefing Expert'){
        $this->remote_coaching_debriefing_expert = $value['value'];
      }
      if($value['name'] == 'Remote Coaching Debriefing Customer'){
        $this->remote_coaching_debriefing_customer = $value['value'];
      }
      if($value['name'] == 'Remote Coaching Debriefing Representative'){
        $this->remote_coaching_debriefing_rep = $value['value'];
      }
      if($value['name'] == 'Briefing Expert'){
        $this->briefing_expert_act = $value['value'];
      }
    }
  }

  public function getValidCaseTypesForView() {
    return array (
      $this->advice => $this->advice,
      $this->remote_coaching => $this->remote_coaching,
      $this->seminar => $this->seminar,
      $this->business => $this->business,
      $this->factfinding => $this->factfinding,
    );
  }

  public function getValidDebriefingActivityTypesForView() {
    return array (
      $this->business_debriefing_expert => $this->business_debriefing_expert,
      $this->business_debriefing_customer => $this->business_debriefing_customer,
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

