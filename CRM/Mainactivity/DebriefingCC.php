<?php

class CRM_Mainactivity_DebriefingCC {

  private static $singleton;

  private $custom_groups = array();

  private $custom_fields = array();

  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Mainactivity_DebriefingCC();
    }
    return self::$singleton;
  }

  public function getSummaryAndFollowUpByCaseId($case_id) {
    $default = array(
      'follow_up' => '',
      'summary' => '',
    );

    $case = civicrm_api3('Case', 'getsingle', array('id' => $case_id));
    $case_type_id = $case['case_type_id'];

    $config = CRM_Mainactivity_DebriefingConfig::singleton();
    if (!$config->isValidCaseType($case_type_id)) {
      return $default;
    }

    $case_type_name = $config->getCaseTypeNameByCaseTypeId($case_type_id);
    $case_definition = $config->debriefingActivityDefinition();
    $defintion = $config->getDebriefingActivityDefinition($case_type_id);
    if (!isset($case_definition[$case_type_name])) {
      return false;
    }

    foreach($case_definition[$case_type_name] as $key => $act) {
      if (!$act['is_debriefing_cc'] || !$act['summary_field_name'] || !$act['follow_up_field_name']) {
        continue;
      }
      $activity_type_id = $defintion[$key]['activity_type_id'];
      $cg = $this->getCustomGroupByName($act['custom_group_name']);
      $follow_up = $this->getCustomFieldByName($cg['id'], $act['follow_up_field_name']);
      $summary = $this->getCustomFieldByName($cg['id'], $act['summary_field_name']);

      $sql = "SELECT cg.{$follow_up['column_name']} as follow_up, cg.{$summary['column_name']} as summary
              FROM `{$cg['table_name']}` cg
              INNER JOIN civicrm_activity a ON cg.entity_id = a.id
              INNER JOIN civicrm_case_activity ca on ca.activity_id = a.id
              WHERE a.is_deleted = 0 and a.is_current_revision = 1 and a.activity_type_id = %1 and ca.case_id = %2 ORDER BY a.activity_date_time ASC";
      $params[1] = array($activity_type_id, 'Integer');
      $params[2] = array($case_id, 'Integer');
      $dao = CRM_Core_DAO::executeQuery($sql, $params);
      while ($dao->fetch()) {
        $default['follow_up'] .= $dao->follow_up . "\r\n\r\n";
        $default['summary'] .= $dao->summary . "\r\n\r\n";
      }
    }
    $default['follow_up'] = trim($default['follow_up']);
    $default['summary'] = trim($default['summary']);
    return $default;
  }

  protected function getCustomGroupByName($cg_name) {
    if (!isset($this->custom_groups[$cg_name])) {
      $this->custom_groups[$cg_name] = civicrm_api3('CustomGroup', 'getsingle', array('name' => $cg_name));
    }
    return $this->custom_groups[$cg_name];
  }

  protected function getCustomFieldByName($cg_id, $field_name) {
    if (!isset($this->custom_fields[$cg_id][$field_name])) {
      $this->custom_fields[$cg_id][$field_name] = civicrm_api3('CustomField', 'getsingle', array('name' => $field_name, 'custom_group_id' => $cg_id));
    }
    return $this->custom_fields[$cg_id][$field_name];
  }

}