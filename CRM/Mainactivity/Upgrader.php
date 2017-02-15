<?php

/**
 * Collection of upgrade steps
 */
class CRM_Mainactivity_Upgrader extends CRM_Mainactivity_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).
  
  protected $case_type_id;

  public function install() {
    $this->executeCustomDataFile('xml/main_activity_info.xml');
    $this->executeCustomDataFile('xml/main_activity_visibility.xml');
  }
  
  public function upgrade_1001() {
    $this->executeCustomDataFile('xml/main_activity_visibility.xml');
    return true;
  }
  
  public function upgrade_1002() {
    $this->executeCustomDataFile('xml/sponsor_info.xml');
    return true;
  }
  
  public function upgrade_1004() {
    $case_types = $this->getCaseTypeIds();
    $case_type_ids = CRM_Core_DAO::VALUE_SEPARATOR . implode(CRM_Core_DAO::VALUE_SEPARATOR, $case_types) . CRM_Core_DAO::VALUE_SEPARATOR;
    $sql = "UPDATE `civicrm_custom_group` SET `extends_entity_column_value` = '".$case_type_ids."' WHERE `name` = 'visibility_of_main_activity'";
    CRM_Core_DAO::executeQuery($sql);
    return true;
  }
  
  public function upgrade_1005() {
    $case_types_main_activity_visibility = array('Advice', 'RemoteCoaching', 'Seminar', 'Business');
    $case_types = $this->getCaseTypeIds($case_types_main_activity_visibility);
    $case_type_ids = CRM_Core_DAO::VALUE_SEPARATOR . implode(CRM_Core_DAO::VALUE_SEPARATOR, $case_types) . CRM_Core_DAO::VALUE_SEPARATOR;
    $sql = "UPDATE `civicrm_custom_group` SET `extends_entity_column_value` = '".$case_type_ids."' WHERE `name` = 'visibility_of_main_activity'";
    CRM_Core_DAO::executeQuery($sql);
    return true;
  }
  
  public function upgrade_1006() {
    $case_types_main_activity_info = array('Advice', 'RemoteCoaching', 'Seminar', 'Business', 'CTM', 'PDV', 'Projectevaluation');
    $case_types = $this->getCaseTypeIds($case_types_main_activity_info);
    $case_type_ids = CRM_Core_DAO::VALUE_SEPARATOR . implode(CRM_Core_DAO::VALUE_SEPARATOR, $case_types) . CRM_Core_DAO::VALUE_SEPARATOR;
    $sql = "UPDATE `civicrm_custom_group` SET `extends_entity_column_value` = '".$case_type_ids."' WHERE `name` = 'visibility_of_main_activity'";
    CRM_Core_DAO::executeQuery($sql);
    return true;
  }

  /**
   * Upgrades for adding a Bussiness Coordinator in the Business case
   * @return bool
   */
  public function upgrade_1007() {
    $gid = CRM_Core_DAO::singleValueQuery("SELECT id from civicrm_custom_group where name = 'Add_Keyqualifications'");
    CRM_Core_DAO::executeQuery("UPDATE `civicrm_custom_field` SET label = 'Assessment SC/BC' WHERE `name` = 'Assessment_SC' AND custom_group_id = '".$gid."'");
    CRM_Core_DAO::executeQuery("UPDATE `civicrm_custom_field` SET label = 'Remarks SC/BC' WHERE `name` = 'Remarks' AND custom_group_id = '".$gid."'");

    CRM_Utils_System::flushCache();

    CRM_Core_DAO::executeQuery("UPDATE `civicrm_option_value` SET label = 'Request Approval Business Programme BC' WHERE label = 'Request Approval Business Programme SC' and option_group_id = 2");
    CRM_Core_DAO::executeQuery("UPDATE `civicrm_option_value` SET label = 'Business Debriefing BC', name = 'Business Debriefing BC' WHERE name = 'Business Debriefing SC' and option_group_id = 2");

    CRM_Utils_System::flushCache();

    $this->executeCustomDataFile('xml/request_approval_bc.xml');

    CRM_Utils_System::flushCache();

    $this->executeCustomDataFile('xml/request_approval_cc.xml');

    CRM_Utils_System::flushCache();

    $checkQry = 'SELECT COUNT(*) AS relCount FROM civicrm_relationship_type WHERE name_a_b = %1';
    $countRelType = CRM_Core_DAO::singleValueQuery($checkQry, array(1 => array('Business Coordinator', 'String')));
    if ($countRelType == 0) {
      CRM_Core_DAO::executeQuery("INSERT INTO civicrm_relationship_type
        (name_a_b, label_a_b, name_b_a, label_b_a, description, contact_type_a, contact_type_b, contact_sub_type_a, contact_sub_type_b, is_reserved, is_active)
        VALUES
        ('Business Coordinator', 'Business Coordinator', 'Business Coordinator', 'Business Coordinator', 'Business Coordinator relationship', NULL, 'Individual', NULL, NULL, NULL, '1')");
    }

    CRM_Utils_System::flushCache();

    $gid = CRM_Core_DAO::singleValueQuery("SELECT id from civicrm_custom_group where name = 'Business_Data'");
    $fid = CRM_Core_DAO::singleValueQuery("SELECT id from civicrm_custom_field where custom_group_id = '".$gid."' and name = 'Position_of_Visitors'");
    if ($fid) {
      civicrm_api3('CustomField', 'delete', array('id' => $fid));
    }

    return true;
  }

  public function upgrade_1008() {
    // Remove group with business coordinators.
    $group = civicrm_api3('Group', 'getsingle', array('title' => 'Business Link Coordinators'));
    civicrm_api3('Group', 'delete', $group);
    return true;
  }

  public function upgrade_1009() {
    CRM_Core_DAO::executeQuery("UPDATE `civicrm_option_value` SET `name` = 'Business Debriefing SC', `label` = 'Business Debriefing SC' WHERE `name` = 'Business Debriefing BC'");
    CRM_Core_DAO::executeQuery("UPDATE `civicrm_custom_group` SET `title` = 'Business Debriefing SC' WHERE `name` = 'Business_Debriefing_SC'");
    return true;
  }
  
  private function getCaseTypeIds($case_types) {
    if (empty($this->case_type_id)) {
      $this->case_type_id = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_type'));
    }
    
    $case_type_ids = array();
    foreach($case_types as $case_type) {
      $case_type_ids[] = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'option_group_id' => $this->case_type_id, 'name' => $case_type));
    }
    
    return $case_type_ids;
  }

  /**
   * Example: Run an external SQL script when the module is uninstalled
   *
  public function uninstall() {
   $this->executeSqlFile('sql/myuninstall.sql');
  }

  /**
   * Example: Run a simple query when a module is enabled
   *
  public function enable() {
    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a simple query when a module is disabled
   *
  public function disable() {
    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a couple simple queries
   *
   * @return TRUE on success
   * @throws Exception
   *
  public function upgrade_4200() {
    $this->ctx->log->info('Applying update 4200');
    CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
    CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
    return TRUE;
  } // */


  /**
   * Example: Run an external SQL script
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4201() {
    $this->ctx->log->info('Applying update 4201');
    // this path is relative to the extension base dir
    $this->executeSqlFile('sql/upgrade_4201.sql');
    return TRUE;
  } // */


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4202() {
    $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

    $this->addTask(ts('Process first step'), 'processPart1', $arg1, $arg2);
    $this->addTask(ts('Process second step'), 'processPart2', $arg3, $arg4);
    $this->addTask(ts('Process second step'), 'processPart3', $arg5);
    return TRUE;
  }
  public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  public function processPart3($arg5) { sleep(10); return TRUE; }
  // */


  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4203() {
    $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

    $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
    $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
    for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
      $endId = $startId + self::BATCH_SIZE - 1;
      $title = ts('Upgrade Batch (%1 => %2)', array(
        1 => $startId,
        2 => $endId,
      ));
      $sql = '
        UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
        WHERE id BETWEEN %1 and %2
      ';
      $params = array(
        1 => array($startId, 'Integer'),
        2 => array($endId, 'Integer'),
      );
      $this->addTask($title, 'executeSql', $sql, $params);
    }
    return TRUE;
  } // */

}
