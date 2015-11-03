<?php


/**
 * Class handling the expert approves main activity proposal
 * (issue 2995 <http://redmine.pum.nl/issues/2995>)
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 3 Nov 2015
 * @license AGPL-3.0
 */
class CRM_Mainactivity_MainActivityProposal {

  protected $_approveActivityTypeId = NULL;
  protected $_rejectActivityTypeId = NULL;
  protected $_approveCustomGroup = array();
  protected $_scApprovesColumn = NULL;
  protected $_ccApprovesColumn = NULL;

  /**
   * CRM_Mainactivity_MainActivityProporal constructor
   */
  function __construct() {
    // get activity types
    $approveActivityType = CRM_Threepeas_Utils::getActivityTypeWithName("Accept Main Activity Proposal");
    if (!empty($approveActivityType)) {
      $this->_approveActivityTypeId = $approveActivityType['value'];
    }
    $rejectActivityType = CRM_Threepeas_Utils::getActivityTypeWithName("Reject Main Activity Proposal");
    if (!empty($rejectActivityType)) {
      $this->_rejectActivityTypeId = $rejectActivityType['value'];
    }

    // get custom group
    $this->_approveCustomGroup = CRM_Threepeas_Utils::getCustomGroup("Add_Keyqualifications");

    // get custom fields for approve CC and SC
    $ccCustomField = CRM_Threepeas_Utils::getCustomField($this->_approveCustomGroup['id'], "Assessment_CC");
    $this->_ccApprovesColumn = $ccCustomField['column_name'];
    $scCustomField = CRM_Threepeas_Utils::getCustomField($this->_approveCustomGroup['id'], "Assessment_SC");
    $this->_scApprovesColumn = $scCustomField['column_name'];
  }

  /**
   * Method to determine if the expert has approved the Main Activity Proposal
   *
   * @param int $caseId
   * @return string
   */
  public function expertApproves($caseId) {
    if (!empty($caseId)) {
      $approveParams = array(
        'case_id' => $caseId,
        'activity_type_id' => $this->_approveActivityTypeId
      );
      $approveCount = $this->countApproveActivities($caseId);
      if ($approveCount > 0) {
        return "Yes";
      }
      $rejectParams = array(
        'case_id' => $caseId,
        'activity_type_id' => $this->_rejectActivityTypeId
      );
      try {
        $rejectCount = civicrm_api3("CaseActivity", "Getcount", $rejectParams);
        if ($rejectCount > 0) {
          return "No";
        }
      } catch (CiviCRM_API3_Exception $ex) {}
    }
    return "n/a";
  }

  /**
   * Method to count the number of expert approves proposal acitivties
   *
   * @param int $caseId
   * @return int
   * @acces private
   */
  private function countApproveActivities($caseId) {
    $approveParams = array(
      'case_id' => $caseId,
      'activity_type_id' => $this->_approveActivityTypeId
    );
    try {
      $approveCount = civicrm_api3("CaseActivity", "Getcount", $approveParams);
    } catch (CiviCRM_API3_Exception $ex) {
      $approveCount = 0;
    }
    return $approveCount;
  }

  /**
   * Method to determine if the Country Coordinator approves the main activity proposal
   *
   * @param int $caseId
   * @return string
   */
  public function ccApproves($caseId) {
    if ($this->countApproveActivities($caseId) > 0) {
      $activityId = $this->getApproveActivityId($caseId);
      if ($activityId != FALSE) {
        $query = "SELECT " . $this->_ccApprovesColumn . " FROM " . $this->_approveCustomGroup['table_name'] . " WHERE entity_id = %1";
        $params = array(1 => array($activityId, "Integer"));
        return CRM_Core_DAO::singleValueQuery($query, $params);
      }
    }
    return "n/a";
  }

  /**
   * Method to determine if the Sector Coordinator approves the main activity proposal
   *
   * @param int $caseId
   * @return string
   */
  public function scApproves($caseId) {
    if ($this->countApproveActivities($caseId) > 0) {
      $activityId = $this->getApproveActivityId($caseId);
      if ($activityId != FALSE) {
        $query = "SELECT " . $this->_scApprovesColumn . " FROM " . $this->_approveCustomGroup['table_name'] . " WHERE entity_id = %1";
        $params = array(1 => array($activityId, "Integer"));
        return CRM_Core_DAO::singleValueQuery($query, $params);
      }
    }
    return "n/a";
  }

  /**
   * Method to get the activity id of the Accept Main Activity Proposal
   * (handles the fact that I might have more than 1 so takes only the latest one)
   *
   * @param int $caseId
   * @return bool|int
   */
  private function getApproveActivityId($caseId) {
    if (empty($caseId)) {
      return FALSE;
    }
    $activityParams = array(
      'case_id' => $caseId,
      'activity_type_id' => $this->_approveActivityTypeId
    );
    try {
      $activities = civicrm_api3("CaseActivity", "Get", $activityParams);
      foreach ($activities['values'] as $activityId => $activityValues) {
        return $activityId;
      }
    } catch (CiviCRM_API3_Exception $ex) {
      return FALSE;
    }
  }

}