<?php

class CRM_Mainactivity_Hooks_BriefingExpertDefaultAssigned {

  public static function buildForm($formName, &$form) {
    if ($formName != 'CRM_Case_Form_Activity') {
      return;
    }

    $config = CRM_Mainactivity_BriefingConfig::singleton();
    if ($form->_activityTypeId != $config->getBriefingExpertActivityId()) {
      return;
    }

    $caseId = $form->_caseId;

    $expert_contact_id = false;
    try {
      $expert_contact_id = civicrm_api3('Relationship', 'getvalue', array(
        'return' => 'contact_id_b',
        'is_active' => 1,
        'relationship_type_id' => $config->getExpertRelationshipTypeId(),
        'case_id' => $caseId,
      ));
    } catch (Exception $e) {
      //do nothing
    }

    if ($expert_contact_id) {
      $defaults['assignee_contact'] = array($expert_contact_id);
      $form->setDefaults($defaults);
      $formattedContacts = array(
        array(
          'id' => $expert_contact_id,
          'name' => CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', $expert_contact_id, 'display_name', 'id'),
        ),
      );
      $form->assign('assignee_contact', json_encode($formattedContacts));
    }

  }

}