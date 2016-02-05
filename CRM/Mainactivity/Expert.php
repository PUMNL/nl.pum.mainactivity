<?php


/**
 * Class handling the expert for main activity
 * (issue 2995 <http://redmine.pum.nl/issues/2995>)
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 3 Nov 2015
 * @license AGPL-3.0
 */
class CRM_Mainactivity_Expert {

  protected $_customerApprovesCustomGroup = array();
  protected $_customerApprovesColumn = NULL;
  protected $_briefingActivityTypeId = NULL;

  /**
   * CRM_Mainactivity_Expert constructor
   */
  function __construct() {
    $this->_customerApprovesCustomGroup = CRM_Threepeas_Utils::getCustomGroup("Customer_dis_agreement_of_Proposed_Expert");
    $customField = CRM_Threepeas_Utils::getCustomField($this->_customerApprovesCustomGroup['id'], "Do_you_think_the_expert_matches_your_request");
    $this->_customerApprovesColumn = $customField['column_name'];
    $briefingActivityType = CRM_Threepeas_Utils::getActivityTypeWithName("Briefing Expert");
    if (!empty($briefingActivityType)) {
      $this->_briefingActivityTypeId = $briefingActivityType['value'];
    }
  }

  /**
   * Method to determine if the customer approves the expert
   *
   * @param int $caseId
   * @return string
   */
  public function customApprovesExpert($caseId) {
    if (!empty($caseId)) {
      $query = "SELECT " . $this->_customerApprovesColumn . " FROM " . $this->_customerApprovesCustomGroup['table_name'] . " WHERE entity_id = %1";
      $params = array(1 => array($caseId, "Integer"));
      return CRM_Core_DAO::singleValueQuery($query, $params);
    }
    return "n/a";
  }

  /**
   * Method to get the Briefing Expert activity from the Main Activity
   *
   * @param $caseId
   * @return array
   */
  public function getBriefingExpertActivity($caseId) {
    $activity = array();
    if (!empty($caseId)) {
      $activityParams = array(
        'case_id' => $caseId,
        'activity_type_id' => $this->_briefingActivityTypeId
      );
      try {
        $apiActivities = civicrm_api3("CaseActivity", "Get", $activityParams);
        foreach ($apiActivities['values'] as $apiActivity) {
          return $apiActivity;
        }
      } catch (CiviCRM_API3_Exception $ex) {}
    }
    return $activity;
  }
}