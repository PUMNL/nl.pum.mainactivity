<?php
/**
 * Class for defaults for Approve Customer By Expert
 *
 * @author Erik Hommel (CiviCooP)
 * @date 15 Feb 2017
 * @license AGPL-3.0
 * @link https://redmine.pum.nl/issues/3740
 */


class CRM_Mainactivity_Hooks_ApproveCustomerByExpertDefaultsAssigned {

  /**
   * Method to process civicrm hook buildForm
   *
   * @param $form
   */
  public static function buildForm(&$form) {
    $config = CRM_Mainactivity_ApproveCustomerByExpertConfig::singleton();
    $formAction = $form->getVar('_action');
    $formCaseType = $form->getVar('_caseType');
    $mainActivities = $config->getMainActivities();
    // case type main activity and action is add
    if (in_array($formCaseType, $mainActivities) && $formAction == CRM_Core_Action::ADD) {
      $formActivityType = $form->getVar('_activityTypeName');
      // activity type is Approve Expert by Customer
      if ($formActivityType == 'Approve Expert by Customer') {
        self::setDefaultsApproveExpertByCustomer($form);
      }
    }
  }

  /**
   * Method to set defaults for approve expert by customer
   *
   * @param $form
   */
  private static function setDefaultsApproveExpertByCustomer(&$form) {
    $defaults = array();
    $caseId = $form->getVar('_caseId');
    // set default medium to webform if exists
    try {
      $defaults['medium_id'] = civicrm_api3('OptionValue', 'getvalue', array(
        'option_group_id' => 'encounter_medium',
        'name' => 'webform',
        'return' => 'value'));
    } catch (CiviCRM_API3_Exception $ex) {}
    // set default assignee to authorised contact for case (or case client if role not on case)
    $authorizedContactId = self::getAuthorisedContactId($form->_relatedContacts, $caseId);
    if ($authorizedContactId) {
      $defaults['assignee_contact_id'] = $authorizedContactId;
    }
    // set default subject to Approve Expert By Customer for <case_id> - <case_type> - <case subject>
    try {
      $caseSubject = civicrm_api3('Case', 'getvalue', array('id' => $caseId, 'return' => 'subject'));
      $defaults['subject'] = 'Approve Expert By Customer for Main Activity '.$caseSubject;
    } catch (CiviCRM_API3_Exception $ex) {}
    if (!empty($defaults)) {
      $form->setDefaults($defaults);
    }
  }

  /**
   * Method to get authorised contact -first try to get authorizedContactId from _relatedContact in Form,
   * if not found get from api on case, if not found get on customer
   *
   * @param array $relatedContacts
   * @param int $caseId
   * @return int
   */
  private static function getAuthorisedContactId($relatedContacts, $caseId) {
    $config = CRM_Mainactivity_ApproveCustomerByExpertConfig::singleton();
    $authorisedContactId = NULL;
    // return from relatedContacts if role is correct
    foreach ($relatedContacts as $key => $relatedContact) {
      if ($relatedContact['role'] == 'Authorised contact for') {
        return $relatedContact['contact_id'];
      }
    }
    // retrieve from case relationship
    try {
      $authorisedContactId = civicrm_api3('Relationship', 'getvalue', array(
        'relationship_type_id' => $config->getAuthorisedRelationshipTypeId(),
        'case_id' => $caseId,
        'return' => 'contact_id_b'));
    } catch (CiviCRM_API3_Exception $ex) {
      // get from customer
      foreach ($relatedContacts as $key => $relatedContact) {
        if ($relatedContact['role'] == 'Client') {
          $caseClientId = $relatedContact['contact_id'];
        }
      }
      if (method_exists('CRM_Threepeas_BAO_PumCaseRelation', 'getAuthorisedContactId') && isset($caseClientId)) {
        $authorisedContactId = CRM_Threepeas_BAO_PumCaseRelation::getAuthorisedContactId($caseClientId);
      }
    }
    return $authorisedContactId;
  }
}