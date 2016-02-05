<?php

class CRM_Mainactivity_BriefingConfig {

  private static $singleton;

  private $briefingExpert;

  private $expert_relationship_type;

  private function __construct() {
    $activity_type_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'activity_type'));
    $this->briefingExpert = civicrm_api3('OptionValue', 'getsingle', array('name' => 'Briefing Expert', 'option_group_id' => $activity_type_id));
    $this->expert_relationship_type = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Expert'));
  }

  /**
   * @return CRM_Mainactivity_BriefingConfig
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Mainactivity_BriefingConfig();
    }
    return self::$singleton;
  }

  public function getBriefingExpertActivityId() {
    return $this->briefingExpert['value'];
  }

  public function getExpertRelationshipTypeId() {
    return $this->expert_relationship_type['id'];
  }

}