<?php

class CRM_Mainactivity_ApproveCustomerByExpertConfig {
  
  protected static $_singleton;

  protected $_activityTypeName = NULL;
  protected $_mainActivities = array();
  protected $_authorisedRelationshipTypeId = NULL;
  
  protected function __construct() {
    // set authorized relationship type id
    try {
      $this->_authorisedRelationshipTypeId = civicrm_api3('RelationshipType', 'getvalue', array(
        'name_a_b' => 'Has authorised',
        'name_b_a' => 'Authorised contact for',
        'return' => 'id'
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not find a relationship with name_a_b Has authorised in '.__METHOD__
        .', this is unexpected. Contact your system administrator'));
    }
    $this->_activityTypeName = 'Approve Expert by Customer';
    $this->_mainActivities = array('Advice', 'Business', 'RemoteCoaching', 'Seminar');
  }
  
  /**
   * Method for singleton pattern
   *
   * @return CRM_Mainactivity_ApproveCustomerByExpertConfig
   */
  public static function singleton() {
    if (!self::$_singleton) {
      self::$_singleton = new CRM_Mainactivity_ApproveCustomerByExpertConfig();
    }
    return self::$_singleton;
  }

  /**
   * Getter for authorisedRelationshipTypeId
   *
   * @return null
   */
  public function getAuthorisedRelationshipTypeId() {
    return $this->_authorisedRelationshipTypeId;
  }
  
  /**
   * Getter for activityTypeName
   *
   * @return string
   */
  public function getActivityTypeId() {
    return $this->_activityTypeName;
  }

  /**
   * Getter for main activities
   * @return mixed
   */
  public function getMainActivities() {
    return $this->_mainActivities;
  }
  
}

