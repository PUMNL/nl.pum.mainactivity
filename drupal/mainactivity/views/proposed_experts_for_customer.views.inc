<?php

$expert_rel_type_id = civicrm_api3('RelationshipType', 'getvalue', array('name_a_b' => 'Expert', 'return' => 'id'));
$authorised_contact_rel_type_id = civicrm_api3('RelationshipType', 'getvalue', array('name_a_b' => 'Has authorised', 'return' => 'id'));
$main_activity_info = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'main_activity_info'));
$start_date = civicrm_api3('CustomField', 'getsingle', array('name' => 'main_activity_start_date', 'custom_group_id' => $main_activity_info['id']));
$end_date = civicrm_api3('CustomField', 'getsingle', array('name' => 'main_activity_end_date', 'custom_group_id' => $main_activity_info['id']));
$case_status_option_group = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'case_status'));
$matching_case_status_id = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Matching', 'option_group_id' => $case_status_option_group));
$activity_type_option_group = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'activity_type'));
$accept_ma_proposal_id = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Accept Main Activity Proposal', 'option_group_id' => $activity_type_option_group));
$visibility_data = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'visibility_of_main_activity'));
$show_to_customer = civicrm_api3('CustomField', 'getvalue', array('name' => 'show_proposed_expert_to_customer', 'return' => 'column_name', 'custom_group_id' => $visibility_data['id']));

$view = new view();
$view->name = 'proposed_expert';
$view->description = '';
$view->tag = 'main activity';
$view->base_table = 'civicrm_contact';
$view->human_name = 'Proposed experts for customer';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Proposed expert(s)';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'role';
$handler->display->display_options['access']['role'] = mainactivity_get_role_ids(array('Customer (contact)'));
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['query']['options']['distinct'] = TRUE;
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'none';
$handler->display->display_options['style_plugin'] = 'table';
/* No results behavior: Global: Text area */
$handler->display->display_options['empty']['area']['id'] = 'area';
$handler->display->display_options['empty']['area']['table'] = 'views';
$handler->display->display_options['empty']['area']['field'] = 'area';
$handler->display->display_options['empty']['area']['label'] = 'No proposed experts';
$handler->display->display_options['empty']['area']['empty'] = TRUE;
$handler->display->display_options['empty']['area']['content'] = 'There are no experts proposed';
$handler->display->display_options['empty']['area']['format'] = 'filtered_html';
/* Relationship: CiviCRM Contacts: CiviCRM Relationship (starting from Contact B) */
$handler->display->display_options['relationships']['relationship_id_b']['id'] = 'relationship_id_b';
$handler->display->display_options['relationships']['relationship_id_b']['table'] = 'civicrm_contact';
$handler->display->display_options['relationships']['relationship_id_b']['field'] = 'relationship_id_b';
$handler->display->display_options['relationships']['relationship_id_b']['label'] = 'Expert relationship';
$handler->display->display_options['relationships']['relationship_id_b']['required'] = TRUE;
$handler->display->display_options['relationships']['relationship_id_b']['relationship_type'] = $expert_rel_type_id;
/* Relationship: CiviCRM Relationships: Case ID */
$handler->display->display_options['relationships']['case_id']['id'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['table'] = 'civicrm_relationship';
$handler->display->display_options['relationships']['case_id']['field'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['relationship'] = 'relationship_id_b';
$handler->display->display_options['relationships']['case_id']['required'] = TRUE;
/* Relationship: CiviCRM Case Activity: Case Activity's Activity ID */
$handler->display->display_options['relationships']['activity_id']['id'] = 'activity_id';
$handler->display->display_options['relationships']['activity_id']['table'] = 'civicrm_case_activity';
$handler->display->display_options['relationships']['activity_id']['field'] = 'activity_id';
$handler->display->display_options['relationships']['activity_id']['relationship'] = 'case_id';
$handler->display->display_options['relationships']['activity_id']['label'] = 'Activity';
$handler->display->display_options['relationships']['activity_id']['required'] = TRUE;
/* Relationship: CiviCRM Case Contact: Case Contact's Contact ID */
$handler->display->display_options['relationships']['contact_id']['id'] = 'contact_id';
$handler->display->display_options['relationships']['contact_id']['table'] = 'civicrm_case_contact';
$handler->display->display_options['relationships']['contact_id']['field'] = 'contact_id';
$handler->display->display_options['relationships']['contact_id']['relationship'] = 'case_id';
$handler->display->display_options['relationships']['contact_id']['label'] = 'Customer';
$handler->display->display_options['relationships']['contact_id']['required'] = TRUE;
/* Relationship: CiviCRM Contacts: CiviCRM Relationship (starting from contact A) */
$handler->display->display_options['relationships']['relationship_id_a']['id'] = 'relationship_id_a';
$handler->display->display_options['relationships']['relationship_id_a']['table'] = 'civicrm_contact';
$handler->display->display_options['relationships']['relationship_id_a']['field'] = 'relationship_id_a';
$handler->display->display_options['relationships']['relationship_id_a']['relationship'] = 'contact_id';
$handler->display->display_options['relationships']['relationship_id_a']['label'] = 'Has authorsied relationship';
$handler->display->display_options['relationships']['relationship_id_a']['required'] = TRUE;
$handler->display->display_options['relationships']['relationship_id_a']['relationship_type'] = $authorised_contact_rel_type_id;
/* Relationship: CiviCRM Relationships: Contact ID B */
$handler->display->display_options['relationships']['contact_id_b_']['id'] = 'contact_id_b_';
$handler->display->display_options['relationships']['contact_id_b_']['table'] = 'civicrm_relationship';
$handler->display->display_options['relationships']['contact_id_b_']['field'] = 'contact_id_b_';
$handler->display->display_options['relationships']['contact_id_b_']['relationship'] = 'relationship_id_a';
$handler->display->display_options['relationships']['contact_id_b_']['label'] = 'Customer contact';
$handler->display->display_options['relationships']['contact_id_b_']['required'] = TRUE;
/* Relationship: CiviCRM Contacts: Drupal ID */
$handler->display->display_options['relationships']['drupal_id']['id'] = 'drupal_id';
$handler->display->display_options['relationships']['drupal_id']['table'] = 'civicrm_contact';
$handler->display->display_options['relationships']['drupal_id']['field'] = 'drupal_id';
$handler->display->display_options['relationships']['drupal_id']['relationship'] = 'contact_id_b_';
$handler->display->display_options['relationships']['drupal_id']['required'] = TRUE;
/* Field: CiviCRM Contacts: Contact ID */
$handler->display->display_options['fields']['id']['id'] = 'id';
$handler->display->display_options['fields']['id']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['id']['field'] = 'id';
$handler->display->display_options['fields']['id']['exclude'] = TRUE;
$handler->display->display_options['fields']['id']['separator'] = '';
/* Field: CiviCRM Cases: Case ID */
$handler->display->display_options['fields']['id_2']['id'] = 'id_2';
$handler->display->display_options['fields']['id_2']['table'] = 'civicrm_case';
$handler->display->display_options['fields']['id_2']['field'] = 'id';
$handler->display->display_options['fields']['id_2']['relationship'] = 'case_id';
$handler->display->display_options['fields']['id_2']['label'] = '';
$handler->display->display_options['fields']['id_2']['exclude'] = TRUE;
$handler->display->display_options['fields']['id_2']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['id_2']['separator'] = '';
/* Field: CiviCRM Contacts: Contact ID */
$handler->display->display_options['fields']['id_1']['id'] = 'id_1';
$handler->display->display_options['fields']['id_1']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['id_1']['field'] = 'id';
$handler->display->display_options['fields']['id_1']['relationship'] = 'contact_id';
$handler->display->display_options['fields']['id_1']['label'] = '';
$handler->display->display_options['fields']['id_1']['exclude'] = TRUE;
$handler->display->display_options['fields']['id_1']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['id_1']['separator'] = '';
/* Field: CiviCRM Cases: Case Type */
$handler->display->display_options['fields']['case_type']['id'] = 'case_type';
$handler->display->display_options['fields']['case_type']['table'] = 'civicrm_case';
$handler->display->display_options['fields']['case_type']['field'] = 'case_type';
$handler->display->display_options['fields']['case_type']['relationship'] = 'case_id';
$handler->display->display_options['fields']['case_type']['label'] = 'Expert for';
/* Field: CiviCRM Contacts: Display Name */
$handler->display->display_options['fields']['display_name']['id'] = 'display_name';
$handler->display->display_options['fields']['display_name']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['display_name']['field'] = 'display_name';
$handler->display->display_options['fields']['display_name']['label'] = 'Expert';
$handler->display->display_options['fields']['display_name']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['display_name']['alter']['path'] = 'customer/expert/[id]/[id_2]';
$handler->display->display_options['fields']['display_name']['alter']['absolute'] = TRUE;
$handler->display->display_options['fields']['display_name']['link_to_civicrm_contact'] = 0;
/* Field: CiviCRM Custom: (Main) Activity information: Activity Start Date */
$handler->display->display_options['fields']['start_date']['id'] = 'start_date';
$handler->display->display_options['fields']['start_date']['table'] = $main_activity_info['table_name'];
$handler->display->display_options['fields']['start_date']['field'] = $start_date['column_name'];
$handler->display->display_options['fields']['start_date']['relationship'] = 'case_id';
$handler->display->display_options['fields']['start_date']['label'] = 'Start Date';
$handler->display->display_options['fields']['start_date']['date_format'] = 'd_m_y';
/* Field: CiviCRM Custom: (Main) Activity information: Activity End Date */
$handler->display->display_options['fields']['end_date']['id'] = 'end_date';
$handler->display->display_options['fields']['end_date']['table'] = $main_activity_info['table_name'];
$handler->display->display_options['fields']['end_date']['field'] = $end_date['column_name'];
$handler->display->display_options['fields']['end_date']['relationship'] = 'case_id';
$handler->display->display_options['fields']['end_date']['label'] = 'End Date';
$handler->display->display_options['fields']['end_date']['date_format'] = 'd_m_y';
/* Field: disgaree expert link */
$handler->display->display_options['fields']['php']['id'] = 'php';
$handler->display->display_options['fields']['php']['table'] = 'views';
$handler->display->display_options['fields']['php']['field'] = 'php';
$handler->display->display_options['fields']['php']['ui_name'] = 'dis-agree-expert';
$handler->display->display_options['fields']['php']['label'] = '';
$handler->display->display_options['fields']['php']['exclude'] = TRUE;
$handler->display->display_options['fields']['php']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['php']['use_php_setup'] = 0;
$handler->display->display_options['fields']['php']['php_value'] = 'return _mainactivity_dis_agree_expert_link($row->case_type);';
$handler->display->display_options['fields']['php']['use_php_click_sortable'] = '0';
$handler->display->display_options['fields']['php']['php_click_sortable'] = '';
/* Field: Global: Custom text */
$handler->display->display_options['fields']['nothing']['id'] = 'nothing';
$handler->display->display_options['fields']['nothing']['table'] = 'views';
$handler->display->display_options['fields']['nothing']['field'] = 'nothing';
$handler->display->display_options['fields']['nothing']['label'] = '';
$handler->display->display_options['fields']['nothing']['alter']['text'] = '(Dis-) agree';
$handler->display->display_options['fields']['nothing']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['nothing']['alter']['path'] = '[php]?cid1=[id_1]&cid2=[id]';
$handler->display->display_options['fields']['nothing']['alter']['absolute'] = TRUE;
$handler->display->display_options['fields']['nothing']['element_label_colon'] = FALSE;
/* Filter criterion: User: Current */
$handler->display->display_options['filters']['uid_current']['id'] = 'uid_current';
$handler->display->display_options['filters']['uid_current']['table'] = 'users';
$handler->display->display_options['filters']['uid_current']['field'] = 'uid_current';
$handler->display->display_options['filters']['uid_current']['relationship'] = 'drupal_id';
$handler->display->display_options['filters']['uid_current']['value'] = '1';
/* Filter criterion: CiviCRM Activities: Activity Status */
$handler->display->display_options['filters']['status']['id'] = 'status';
$handler->display->display_options['filters']['status']['table'] = 'civicrm_activity';
$handler->display->display_options['filters']['status']['field'] = 'status';
$handler->display->display_options['filters']['status']['relationship'] = 'activity_id';
$handler->display->display_options['filters']['status']['value'] = array(
  2 => '2', //completed
);
/* Filter criterion: CiviCRM Activities: Activity Type */
$handler->display->display_options['filters']['activity_type']['id'] = 'activity_type';
$handler->display->display_options['filters']['activity_type']['table'] = 'civicrm_activity';
$handler->display->display_options['filters']['activity_type']['field'] = 'activity_type';
$handler->display->display_options['filters']['activity_type']['relationship'] = 'activity_id';
$handler->display->display_options['filters']['activity_type']['value'] = array(
  $accept_ma_proposal_id => $accept_ma_proposal_id,
);
/* Filter criterion: CiviCRM Cases: Case Status */
$handler->display->display_options['filters']['status_1']['id'] = 'status_1';
$handler->display->display_options['filters']['status_1']['table'] = 'civicrm_case';
$handler->display->display_options['filters']['status_1']['field'] = 'status';
$handler->display->display_options['filters']['status_1']['relationship'] = 'case_id';
$handler->display->display_options['filters']['status_1']['value'] = array(
  $matching_case_status_id => $matching_case_status_id,
);
/* Filter criterion: CiviCRM Custom: Visibility of case: Show project as proposed to expert */
$handler->display->display_options['filters']['show_proposed_project_to_expert']['id'] = 'show_proposed_project_to_expert';
$handler->display->display_options['filters']['show_proposed_project_to_expert']['table'] = $visibility_data['table_name'];
$handler->display->display_options['filters']['show_proposed_project_to_expert']['field'] = $show_to_customer;
$handler->display->display_options['filters']['show_proposed_project_to_expert']['relationship'] = 'case_id';
$handler->display->display_options['filters']['show_proposed_project_to_expert']['value'] = '1';

/* Display: Page */
$handler = $view->new_display('page', 'Page', 'page');
$handler->display->display_options['path'] = 'customer/proposed-experts';
$handler->display->display_options['menu']['type'] = 'normal';
$handler->display->display_options['menu']['title'] = 'Proposed expert(s)';
$handler->display->display_options['menu']['weight'] = '0';
$handler->display->display_options['menu']['context'] = 0;
$handler->display->display_options['menu']['context_only_inline'] = 0;
$translatables['proposed_expert'] = array(
  t('Master'),
  t('Proposed expert(s)'),
  t('more'),
  t('Apply'),
  t('Reset'),
  t('Sort by'),
  t('Asc'),
  t('Desc'),
  t('No proposed experts'),
  t('There are no experts proposed'),
  t('Expert relationship'),
  t('CiviCRM Case'),
  t('Activity'),
  t('Customer'),
  t('Has authorsied relationship'),
  t('Customer contact'),
  t('Drupal User'),
  t('Contact ID'),
  t('.'),
  t('Expert for'),
  t('Expert'),
  t('Start Date'),
  t('End Date'),
  t('(Dis-) agree'),
  t('Page'),
);
