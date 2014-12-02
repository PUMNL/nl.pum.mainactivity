<?php

$case_status_option_group = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_status'));
$matching_case_status_id = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Matching', 'option_group_id' => $case_status_option_group));
$expert_rel_type_id = civicrm_api3('RelationshipType', 'getvalue', array('name_a_b' => 'Expert', 'return' => 'id'));
$sc_rel_type_id = civicrm_api3('RelationshipType', 'getvalue', array('name_a_b' => 'Sector Coordinator', 'return' => 'id'));
$cc_rel_type_id = civicrm_api3('RelationshipType', 'getvalue', array('name_a_b' => 'Country Coordinator is', 'return' => 'id'));
$prof_rel_type_id = civicrm_api3('RelationshipType', 'getvalue', array('name_a_b' => 'Project Officer for', 'return' => 'id'));
$visibility_data = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'visibility_of_main_activity'));
$show_to_expert = civicrm_api3('CustomField', 'getvalue', array('name' => 'show_proposed_project_to_expert', 'return' => 'column_name', 'custom_group_id' => $visibility_data['id']));

$view = new view();
$view->name = 'main_activity_proposals_for_expert';
$view->description = '';
$view->tag = 'main activity';
$view->base_table = 'civicrm_contact';
$view->human_name = 'Main activity proposals (for experts)';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Main activity proposals';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'role';
$handler->display->display_options['access']['role'] = mainactivity_get_role_ids(array('Expert'));
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'none';
$handler->display->display_options['style_plugin'] = 'table';
/* Header: Global: Text area */
$handler->display->display_options['header']['area']['id'] = 'area';
$handler->display->display_options['header']['area']['table'] = 'views';
$handler->display->display_options['header']['area']['field'] = 'area';
$handler->display->display_options['header']['area']['label'] = 'Explenation';
$handler->display->display_options['header']['area']['content'] = 'If you have any questions you can contact the Sector Coordinator (SC), the Country Coordinator (CC) or the Project Officer at Pum.';
$handler->display->display_options['header']['area']['format'] = 'filtered_html';
/* No results behavior: Global: Text area */
$handler->display->display_options['empty']['area']['id'] = 'area';
$handler->display->display_options['empty']['area']['table'] = 'views';
$handler->display->display_options['empty']['area']['field'] = 'area';
$handler->display->display_options['empty']['area']['label'] = 'No results';
$handler->display->display_options['empty']['area']['empty'] = TRUE;
$handler->display->display_options['empty']['area']['content'] = 'Currently you don\'t have any main activity proposals';
$handler->display->display_options['empty']['area']['format'] = 'filtered_html';
/* Relationship: CiviCRM Contacts: CiviCRM Relationship (starting from contact A) */
$handler->display->display_options['relationships']['relationship_id_a']['id'] = 'relationship_id_a';
$handler->display->display_options['relationships']['relationship_id_a']['table'] = 'civicrm_contact';
$handler->display->display_options['relationships']['relationship_id_a']['field'] = 'relationship_id_a';
$handler->display->display_options['relationships']['relationship_id_a']['label'] = 'Expert relationship';
$handler->display->display_options['relationships']['relationship_id_a']['required'] = TRUE;
$handler->display->display_options['relationships']['relationship_id_a']['relationship_type'] = $expert_rel_type_id;
/* Relationship: CiviCRM Relationships: Contact ID B */
$handler->display->display_options['relationships']['contact_id_b_']['id'] = 'contact_id_b_';
$handler->display->display_options['relationships']['contact_id_b_']['table'] = 'civicrm_relationship';
$handler->display->display_options['relationships']['contact_id_b_']['field'] = 'contact_id_b_';
$handler->display->display_options['relationships']['contact_id_b_']['relationship'] = 'relationship_id_a';
$handler->display->display_options['relationships']['contact_id_b_']['label'] = 'Expert';
$handler->display->display_options['relationships']['contact_id_b_']['required'] = TRUE;
/* Relationship: CiviCRM Contacts: Drupal ID */
$handler->display->display_options['relationships']['drupal_id']['id'] = 'drupal_id';
$handler->display->display_options['relationships']['drupal_id']['table'] = 'civicrm_contact';
$handler->display->display_options['relationships']['drupal_id']['field'] = 'drupal_id';
$handler->display->display_options['relationships']['drupal_id']['relationship'] = 'contact_id_b_';
$handler->display->display_options['relationships']['drupal_id']['required'] = TRUE;
/* Relationship: CiviCRM Relationships: Case ID */
$handler->display->display_options['relationships']['case_id']['id'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['table'] = 'civicrm_relationship';
$handler->display->display_options['relationships']['case_id']['field'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['relationship'] = 'relationship_id_a';
$handler->display->display_options['relationships']['case_id']['required'] = TRUE;
/* Relationship: CiviCRM Contacts: CiviCRM Relationship (starting from contact case ID) */
$handler->display->display_options['relationships']['relationship_id_a_1']['id'] = 'relationship_id_a_1';
$handler->display->display_options['relationships']['relationship_id_a_1']['table'] = 'civicrm_case';
$handler->display->display_options['relationships']['relationship_id_a_1']['field'] = 'relationship_id';
$handler->display->display_options['relationships']['relationship_id_a_1']['relationship'] = 'case_id';
$handler->display->display_options['relationships']['relationship_id_a_1']['label'] = 'SC of customer';
$handler->display->display_options['relationships']['relationship_id_a_1']['relationship_type'] = $sc_rel_type_id;
/* Relationship: CiviCRM Relationships: Contact ID B */
$handler->display->display_options['relationships']['contact_id_b__1']['id'] = 'contact_id_b__1';
$handler->display->display_options['relationships']['contact_id_b__1']['table'] = 'civicrm_relationship';
$handler->display->display_options['relationships']['contact_id_b__1']['field'] = 'contact_id_b_';
$handler->display->display_options['relationships']['contact_id_b__1']['relationship'] = 'relationship_id_a_1';
$handler->display->display_options['relationships']['contact_id_b__1']['label'] = 'SC';
/* Relationship: CiviCRM Contacts: CiviCRM Relationship (starting from contact case ID) */
$handler->display->display_options['relationships']['relationship_id_a_2']['id'] = 'relationship_id_a_2';
$handler->display->display_options['relationships']['relationship_id_a_2']['table'] = 'civicrm_case';
$handler->display->display_options['relationships']['relationship_id_a_2']['field'] = 'relationship_id';
$handler->display->display_options['relationships']['relationship_id_a_2']['relationship'] = 'case_id';
$handler->display->display_options['relationships']['relationship_id_a_2']['label'] = 'CC of customer';
$handler->display->display_options['relationships']['relationship_id_a_2']['relationship_type'] = $cc_rel_type_id;
/* Relationship: CiviCRM Relationships: Contact ID B */
$handler->display->display_options['relationships']['contact_id_b__2']['id'] = 'contact_id_b__2';
$handler->display->display_options['relationships']['contact_id_b__2']['table'] = 'civicrm_relationship';
$handler->display->display_options['relationships']['contact_id_b__2']['field'] = 'contact_id_b_';
$handler->display->display_options['relationships']['contact_id_b__2']['relationship'] = 'relationship_id_a_2';
$handler->display->display_options['relationships']['contact_id_b__2']['label'] = 'CC';
/* Relationship: CiviCRM Contacts: CiviCRM Relationship (starting from contact case ID) */
$handler->display->display_options['relationships']['relationship_id_a_3']['id'] = 'relationship_id_a_3';
$handler->display->display_options['relationships']['relationship_id_a_3']['table'] = 'civicrm_case';
$handler->display->display_options['relationships']['relationship_id_a_3']['field'] = 'relationship_id';
$handler->display->display_options['relationships']['relationship_id_a_3']['relationship'] = 'case_id';
$handler->display->display_options['relationships']['relationship_id_a_3']['label'] = 'Prof of customer';
$handler->display->display_options['relationships']['relationship_id_a_3']['relationship_type'] = $prof_rel_type_id;
/* Relationship: CiviCRM Relationships: Contact ID B */
$handler->display->display_options['relationships']['contact_id_b__3']['id'] = 'contact_id_b__3';
$handler->display->display_options['relationships']['contact_id_b__3']['table'] = 'civicrm_relationship';
$handler->display->display_options['relationships']['contact_id_b__3']['field'] = 'contact_id_b_';
$handler->display->display_options['relationships']['contact_id_b__3']['relationship'] = 'relationship_id_a_3';
$handler->display->display_options['relationships']['contact_id_b__3']['label'] = 'Proj. off.';
/* Field: CiviCRM Contacts: Contact ID */
$handler->display->display_options['fields']['id']['id'] = 'id';
$handler->display->display_options['fields']['id']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['id']['field'] = 'id';
$handler->display->display_options['fields']['id']['exclude'] = TRUE;
$handler->display->display_options['fields']['id']['separator'] = '';
/* Field: CiviCRM Cases: Case ID */
$handler->display->display_options['fields']['id_1']['id'] = 'id_1';
$handler->display->display_options['fields']['id_1']['table'] = 'civicrm_case';
$handler->display->display_options['fields']['id_1']['field'] = 'id';
$handler->display->display_options['fields']['id_1']['relationship'] = 'relationship_id_a';
$handler->display->display_options['fields']['id_1']['exclude'] = TRUE;
$handler->display->display_options['fields']['id_1']['separator'] = '';
/* Field: CiviCRM Contacts: Contact ID */
$handler->display->display_options['fields']['id_2']['id'] = 'id_2';
$handler->display->display_options['fields']['id_2']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['id_2']['field'] = 'id';
$handler->display->display_options['fields']['id_2']['relationship'] = 'contact_id_b_';
$handler->display->display_options['fields']['id_2']['label'] = 'Expert Contact ID';
$handler->display->display_options['fields']['id_2']['exclude'] = TRUE;
$handler->display->display_options['fields']['id_2']['separator'] = '';
/* Field: CiviCRM Contacts: Display Name */
$handler->display->display_options['fields']['display_name']['id'] = 'display_name';
$handler->display->display_options['fields']['display_name']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['display_name']['field'] = 'display_name';
$handler->display->display_options['fields']['display_name']['label'] = 'Customer';
$handler->display->display_options['fields']['display_name']['link_to_civicrm_contact'] = 1;
/* Field: CiviCRM Cases: Case Type */
$handler->display->display_options['fields']['case_type']['id'] = 'case_type';
$handler->display->display_options['fields']['case_type']['table'] = 'civicrm_case';
$handler->display->display_options['fields']['case_type']['field'] = 'case_type';
$handler->display->display_options['fields']['case_type']['relationship'] = 'case_id';
$handler->display->display_options['fields']['case_type']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['case_type']['alter']['path'] = 'civicrm/contact/view/case?reset=1&action=view&cid=[id]&id=[id_1]&show=1';
$handler->display->display_options['fields']['case_type']['alter']['absolute'] = TRUE;
/* Field: CiviCRM Email: Email Address */
$handler->display->display_options['fields']['email_1']['id'] = 'email_1';
$handler->display->display_options['fields']['email_1']['table'] = 'civicrm_email';
$handler->display->display_options['fields']['email_1']['field'] = 'email';
$handler->display->display_options['fields']['email_1']['relationship'] = 'contact_id_b__1';
$handler->display->display_options['fields']['email_1']['label'] = '';
$handler->display->display_options['fields']['email_1']['exclude'] = TRUE;
$handler->display->display_options['fields']['email_1']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['email_1']['location_type'] = '0';
$handler->display->display_options['fields']['email_1']['location_op'] = '0';
$handler->display->display_options['fields']['email_1']['is_primary'] = 1;
/* Field: CiviCRM Contacts: Display Name */
$handler->display->display_options['fields']['display_name_1']['id'] = 'display_name_1';
$handler->display->display_options['fields']['display_name_1']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['display_name_1']['field'] = 'display_name';
$handler->display->display_options['fields']['display_name_1']['relationship'] = 'contact_id_b__1';
$handler->display->display_options['fields']['display_name_1']['label'] = 'SC';
$handler->display->display_options['fields']['display_name_1']['alter']['alter_text'] = TRUE;
$handler->display->display_options['fields']['display_name_1']['alter']['text'] = '[display_name_1] ([email_1])';
$handler->display->display_options['fields']['display_name_1']['link_to_civicrm_contact'] = 0;
/* Field: CiviCRM Email: Email Address */
$handler->display->display_options['fields']['email_2']['id'] = 'email_2';
$handler->display->display_options['fields']['email_2']['table'] = 'civicrm_email';
$handler->display->display_options['fields']['email_2']['field'] = 'email';
$handler->display->display_options['fields']['email_2']['relationship'] = 'contact_id_b__2';
$handler->display->display_options['fields']['email_2']['label'] = '';
$handler->display->display_options['fields']['email_2']['exclude'] = TRUE;
$handler->display->display_options['fields']['email_2']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['email_2']['location_type'] = '0';
$handler->display->display_options['fields']['email_2']['location_op'] = '0';
$handler->display->display_options['fields']['email_2']['is_primary'] = 1;
/* Field: CiviCRM Contacts: Display Name */
$handler->display->display_options['fields']['display_name_2']['id'] = 'display_name_2';
$handler->display->display_options['fields']['display_name_2']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['display_name_2']['field'] = 'display_name';
$handler->display->display_options['fields']['display_name_2']['relationship'] = 'contact_id_b__2';
$handler->display->display_options['fields']['display_name_2']['label'] = 'CC';
$handler->display->display_options['fields']['display_name_2']['alter']['alter_text'] = TRUE;
$handler->display->display_options['fields']['display_name_2']['alter']['text'] = '[display_name_2] ([email_2])';
$handler->display->display_options['fields']['display_name_2']['link_to_civicrm_contact'] = 0;
/* Field: CiviCRM Email: Email Address */
$handler->display->display_options['fields']['email_3']['id'] = 'email_3';
$handler->display->display_options['fields']['email_3']['table'] = 'civicrm_email';
$handler->display->display_options['fields']['email_3']['field'] = 'email';
$handler->display->display_options['fields']['email_3']['relationship'] = 'contact_id_b__3';
$handler->display->display_options['fields']['email_3']['label'] = '';
$handler->display->display_options['fields']['email_3']['exclude'] = TRUE;
$handler->display->display_options['fields']['email_3']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['email_3']['location_type'] = '0';
$handler->display->display_options['fields']['email_3']['location_op'] = '0';
$handler->display->display_options['fields']['email_3']['is_primary'] = 1;
/* Field: CiviCRM Contacts: Display Name */
$handler->display->display_options['fields']['display_name_3']['id'] = 'display_name_3';
$handler->display->display_options['fields']['display_name_3']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['display_name_3']['field'] = 'display_name';
$handler->display->display_options['fields']['display_name_3']['relationship'] = 'contact_id_b__3';
$handler->display->display_options['fields']['display_name_3']['label'] = 'Proj. off.';
$handler->display->display_options['fields']['display_name_3']['alter']['alter_text'] = TRUE;
$handler->display->display_options['fields']['display_name_3']['alter']['text'] = '[display_name_3] ([email_3])';
$handler->display->display_options['fields']['display_name_3']['link_to_civicrm_contact'] = 0;
/* Field: accept-main-proposal link */
$handler->display->display_options['fields']['php']['id'] = 'php';
$handler->display->display_options['fields']['php']['table'] = 'views';
$handler->display->display_options['fields']['php']['field'] = 'php';
$handler->display->display_options['fields']['php']['ui_name'] = 'accept_main_proposal_link';
$handler->display->display_options['fields']['php']['label'] = '';
$handler->display->display_options['fields']['php']['exclude'] = TRUE;
$handler->display->display_options['fields']['php']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['php']['use_php_setup'] = 0;
$handler->display->display_options['fields']['php']['php_value'] = 'return _mainactivity_accept_main_proposal_link($row->case_type);';
$handler->display->display_options['fields']['php']['use_php_click_sortable'] = '0';
$handler->display->display_options['fields']['php']['php_click_sortable'] = '';
/* Field: reject-main-proposal link */
$handler->display->display_options['fields']['php_1']['id'] = 'php_1';
$handler->display->display_options['fields']['php_1']['table'] = 'views';
$handler->display->display_options['fields']['php_1']['field'] = 'php';
$handler->display->display_options['fields']['php_1']['ui_name'] = 'reject_main_proposal_link';
$handler->display->display_options['fields']['php_1']['label'] = '';
$handler->display->display_options['fields']['php_1']['exclude'] = TRUE;
$handler->display->display_options['fields']['php_1']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['php_1']['use_php_setup'] = 0;
$handler->display->display_options['fields']['php_1']['php_value'] = 'return _mainactivity_reject_main_proposal_link($row->case_type);';
$handler->display->display_options['fields']['php_1']['use_php_click_sortable'] = '0';
$handler->display->display_options['fields']['php_1']['php_click_sortable'] = '';
/* Field: Global: Custom text */
$handler->display->display_options['fields']['nothing_1']['id'] = 'nothing_1';
$handler->display->display_options['fields']['nothing_1']['table'] = 'views';
$handler->display->display_options['fields']['nothing_1']['field'] = 'nothing';
$handler->display->display_options['fields']['nothing_1']['label'] = '';
$handler->display->display_options['fields']['nothing_1']['alter']['text'] = 'Accept proposal';
$handler->display->display_options['fields']['nothing_1']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['nothing_1']['alter']['path'] = '[php]?cid1=[id]&cid2=[id_2]&caseid=[id_1]';
$handler->display->display_options['fields']['nothing_1']['alter']['absolute'] = TRUE;
$handler->display->display_options['fields']['nothing_1']['element_label_colon'] = FALSE;
/* Field: Global: Custom text */
$handler->display->display_options['fields']['nothing']['id'] = 'nothing';
$handler->display->display_options['fields']['nothing']['table'] = 'views';
$handler->display->display_options['fields']['nothing']['field'] = 'nothing';
$handler->display->display_options['fields']['nothing']['label'] = '';
$handler->display->display_options['fields']['nothing']['alter']['text'] = 'Reject proposal';
$handler->display->display_options['fields']['nothing']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['nothing']['alter']['path'] = '[php_1]?cid1=[id]&cid2=[id_2]&caseid=[id_1]';
$handler->display->display_options['fields']['nothing']['alter']['absolute'] = TRUE;
$handler->display->display_options['fields']['nothing']['element_label_colon'] = FALSE;
/* Filter criterion: CiviCRM Cases: Case Status */
$handler->display->display_options['filters']['status']['id'] = 'status';
$handler->display->display_options['filters']['status']['table'] = 'civicrm_case';
$handler->display->display_options['filters']['status']['field'] = 'status';
$handler->display->display_options['filters']['status']['relationship'] = 'relationship_id_a';
$handler->display->display_options['filters']['status']['value'] = array(
  $matching_case_status_id => $matching_case_status_id,
);
/* Filter criterion: User: Current */
$handler->display->display_options['filters']['uid_current']['id'] = 'uid_current';
$handler->display->display_options['filters']['uid_current']['table'] = 'users';
$handler->display->display_options['filters']['uid_current']['field'] = 'uid_current';
$handler->display->display_options['filters']['uid_current']['relationship'] = 'drupal_id';
$handler->display->display_options['filters']['uid_current']['value'] = '1';
/* Filter criterion: CiviCRM Custom: Visibility of case: Show project as proposed to expert */
$handler->display->display_options['filters']['show_proposed_project_to_expert']['id'] = 'show_proposed_project_to_expert';
$handler->display->display_options['filters']['show_proposed_project_to_expert']['table'] = $visibility_data['table_name'];
$handler->display->display_options['filters']['show_proposed_project_to_expert']['field'] = $show_to_expert;
$handler->display->display_options['filters']['show_proposed_project_to_expert']['relationship'] = 'case_id';
$handler->display->display_options['filters']['show_proposed_project_to_expert']['value'] = '1';
/* Display: Page */
$handler = $view->new_display('page', 'Page', 'page');
$handler->display->display_options['path'] = 'expert/main-activity-proposals';
$handler->display->display_options['menu']['type'] = 'normal';
$handler->display->display_options['menu']['title'] = 'Main activity proposal';
$handler->display->display_options['menu']['weight'] = '0';
$handler->display->display_options['menu']['context'] = 0;
$handler->display->display_options['menu']['context_only_inline'] = 0;
