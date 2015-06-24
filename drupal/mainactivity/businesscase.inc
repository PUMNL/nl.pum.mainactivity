<?php

class businesscase {
  
  protected $case_id;
  
  protected $open_for_registration;
  
  protected $number_of_participants;
  
  protected $travel_cases;
  
  protected $case;
  
  public function __construct($case_id) {
    civicrm_initialize();
    
    $this->case_id = $case_id;
    $this->case = civicrm_api3('Case', 'getsingle', array('id' => $this->case_id));
    
    $this->retrieveBusinessData();
    $this->travel_cases = $this->retrieveTravelCases();    
  }
  
  public function createNewTravelCase($client_id) {
    $config = CRM_Travelcase_Config::singleton();
    
    $parameters['client_id'] = $client_id;
    $parameters['case_type_id'] = $config->getCaseType('value');
    $parameters['status_id'] = $config->getOpenCaseStatus('value');
    $parameters['subject'] = '{contactName}-{caseType}-{caseId}';
    $case = civicrm_api3('Case', 'create', $parameters);

    /**
     * Issue 2630 Jaap Jansma (jaap.jansma@civicoop.org)
     *
     * Case status of new travel case does not get an automatic status of ongoing
     * so set it again after creation.
     */
    $updateParameters['id'] = $case['id'];
    $updateParameters['status_id'] = $config->getOpenCaseStatus('value');
    civicrm_api3('Case', 'create', $updateParameters);

    $custom['entity_id'] = $case['id'];
    $custom['entity_table'] = 'civicrm_case';
    $custom['custom_'.$config->getCustomFieldCaseId('id')] = $this->case_id;
    civicrm_api3('CustomValue', 'create', $custom);
  }
  
  public function closeTravelCase($case_id) {
    $parameters['id'] = $case_id;
    civicrm_api3('Case', 'delete', $parameters);
  }
  
  public function getMaxNumberOfParticipants() {
    return $this->number_of_participants;
  }
  
  public function getClientId() {
    return reset($this->case['client_id']);
  }
  
  public function isOpenForRegistration() {
    return $this->open_for_registration ? true : false;
  }
  
  protected function retrieveBusinessData() {
    $this->open_for_registration = 0;
    $this->number_of_participants = 0;
    
    $config = businessconfig::singleton();
    
    $sql = "SELECT `".$config->custom_groups['business_data']['fields']['Number_of_Visitors']['column_name']."` AS `number_of_visitor`,
                   `".$config->custom_groups['business_data']['fields']['Open_for_Registration']['column_name']."` AS `open_for_registration`
        FROM `".$config->custom_groups['business_data']['table_name']."` 
        WHERE `entity_id` = %1";
    $dao = CRM_Core_DAO::executeQuery($sql, array(1 => array($this->case_id, 'Integer')));
    if ($dao->fetch()) {
      $this->number_of_participants = $dao->number_of_visitor;
      $this->open_for_registration = $dao->open_for_registration;
    }
    
  }
  
  public function getTravelCaseFor($index) {
    if (isset($this->travel_cases[$index])) {
      return $this->travel_cases[$index];
    }
    return false;
  }
  
  protected function retrieveTravelCases() {
    $sys_config = CRM_Core_Config::singleton();
    $config = CRM_Travelcase_Config::singleton();
    $case_status = array();
    $params =array('name' => 'case_status');
    CRM_Core_BAO_OptionGroup::retrieve($params, $case_status);
    $sql = "SELECT civicrm_case.*, civicrm_case_contact.contact_id as client_id, civicrm_contact.display_name, ov.label as case_status_label "
        . ",`ta`.`".$config->getCustomFieldDestination('column_name')."` AS `destination`, `ta`.`".$config->getCustomFieldDepartureDate('column_name')."` AS `departure_date`, `ta`.`".$config->getCustomFieldReturnDate('column_name')."` AS `return_date`"        
        . "FROM `".$config->getCustomGroupLinkCaseTo('table_name')."` AS `case_link`
        INNER JOIN `civicrm_case` ON `case_link`.`entity_id` = `civicrm_case`.`id` AND civicrm_case.is_deleted = '0'"
        . "INNER JOIN `civicrm_case_contact` ON `civicrm_case`.`id` = `civicrm_case_contact`.`case_id`"
        . "INNER JOIN `civicrm_contact` ON `civicrm_case_contact`.`contact_id`  = `civicrm_contact`.`id`"
        . "LEFT JOIN `".$config->getCustomGroupTravelAgencyInfo('table_name')."` `ta` ON `civicrm_case`.`id` = `ta`.`entity_id`"
        . "LEFT JOIN  civicrm_option_value ov ON ( civicrm_case.status_id=ov.value AND ov.option_group_id='".$case_status['id']."')"
        . "WHERE `case_link`.`".$config->getCustomFieldCaseId('column_name')."` = '".$this->case_id."'";

    $dao = CRM_Core_DAO::executeQuery($sql);
    $cases = array();
    $i = 1;
    while($dao->fetch()) {
      $cases[$i] = array(
        'client_id' => $dao->client_id,
        'case_id' => $dao->id,
        'display_name' => $dao->display_name,
        'status' => $dao->case_status_label,
        'destination' => $dao->destination,
        'departure_date' => CRM_Utils_Date::customFormat($dao->departure_date, $sys_config->dateformatFull),
        'return_date' => CRM_Utils_Date::customFormat($dao->return_date, $sys_config->dateformatFull),
      );
      $i++;
    }
    return $cases;
  }
  
}