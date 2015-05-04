<?php
$config = businessconfig::singleton();
$business_programm_data = $config->custom_groups['Business_Programme'];
$company = $config->custom_groups['Business_Programme']['fields']['Naam_bedrijf']['column_name'];
$location = $config->custom_groups['Business_Programme']['fields']['Location']['column_name'];
$from = $config->custom_groups['Business_Programme']['fields']['Visit_from']['column_name'];
$to = $config->custom_groups['Business_Programme']['fields']['Visit_to']['column_name'];
$aim = $config->custom_groups['Business_Programme']['fields']['Aim_of_Visit']['column_name'];
$result = $config->custom_groups['Business_Programme']['fields']['Intended_Result_of_Visit']['column_name'];




$view = new view();
$view->name = 'expert_business_link_program_details';
$view->description = '';
$view->tag = 'default';
$view->base_table = 'civicrm_activity';
$view->human_name = 'Business link programme details (for expert)';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Business programme proposal';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'none';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['pager']['options']['items_per_page'] = '10';
$handler->display->display_options['style_plugin'] = 'table';
$handler->display->display_options['style_options']['columns'] = array(
  'visit_from_869' => 'visit_from_869',
  'visit_ot_870' => 'visit_ot_870',
  'location_868' => 'location_868',
  'short_description_aim_of_visit_871' => 'short_description_aim_of_visit_871',
  'short_description_result_of_visi_872' => 'short_description_result_of_visi_872',
);
$handler->display->display_options['style_options']['default'] = 'visit_from_869';
$handler->display->display_options['style_options']['info'] = array(
  'visit_from_869' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'visit_ot_870' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'location_868' => array(
    'sortable' => 1,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'short_description_aim_of_visit_871' => array(
    'sortable' => 0,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
  'short_description_result_of_visi_872' => array(
    'sortable' => 0,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
);
/* Header: Global: Text area */
$handler->display->display_options['header']['area']['id'] = 'area';
$handler->display->display_options['header']['area']['table'] = 'views';
$handler->display->display_options['header']['area']['field'] = 'area';
$handler->display->display_options['header']['area']['empty'] = TRUE;
$handler->display->display_options['header']['area']['content'] = '<a href="/expert/business-programme-proposal/add/!1?destination=/expert/business-programme-proposal/!1" class="button">Add a visit</a> <a href="/expert/business-programme-proposal/request-approval/!1?destination=/expert/business-programme-proposal/!1" class="button">Request approval</a>';
$handler->display->display_options['header']['area']['format'] = 'filtered_html';
$handler->display->display_options['header']['area']['tokenize'] = TRUE;
/* No results behavior: Global: Text area */
$handler->display->display_options['empty']['area']['id'] = 'area';
$handler->display->display_options['empty']['area']['table'] = 'views';
$handler->display->display_options['empty']['area']['field'] = 'area';
$handler->display->display_options['empty']['area']['empty'] = TRUE;
$handler->display->display_options['empty']['area']['content'] = 'No program defined yet';
$handler->display->display_options['empty']['area']['format'] = 'filtered_html';
/* Relationship: CiviCRM Case Activity: Case ID */
$handler->display->display_options['relationships']['case_id']['id'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['table'] = 'civicrm_case_activity';
$handler->display->display_options['relationships']['case_id']['field'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['required'] = TRUE;
/* Field: CiviCRM Custom: Business Programme: Visit from */
$handler->display->display_options['fields']['visit_from_869']['id'] = 'visit_from_869';
$handler->display->display_options['fields']['visit_from_869']['table'] = $business_programm_data['table_name'];
$handler->display->display_options['fields']['visit_from_869']['label'] = 'Visit from';
$handler->display->display_options['fields']['visit_from_869']['field'] = $from;
$handler->display->display_options['fields']['visit_from_869']['date_format'] = 'custom';
$handler->display->display_options['fields']['visit_from_869']['custom_date_format'] = 'Y-m-d';
$handler->display->display_options['fields']['visit_from_869']['second_date_format'] = 'long';
/* Field: CiviCRM Custom: Business Programme: Visit ot */
$handler->display->display_options['fields']['visit_ot_870']['id'] = 'visit_ot_870';
$handler->display->display_options['fields']['visit_ot_870']['table'] = $business_programm_data['table_name'];
$handler->display->display_options['fields']['visit_ot_870']['field'] = $to;
$handler->display->display_options['fields']['visit_ot_870']['label'] = 'Visit to';
$handler->display->display_options['fields']['visit_ot_870']['date_format'] = 'custom';
$handler->display->display_options['fields']['visit_ot_870']['custom_date_format'] = 'Y-m-d';
$handler->display->display_options['fields']['visit_ot_870']['second_date_format'] = 'long';
/* Field: CiviCRM Custom: Business Programme: Location */
$handler->display->display_options['fields']['company']['id'] = 'company';
$handler->display->display_options['fields']['company']['table'] = $business_programm_data['table_name'];
$handler->display->display_options['fields']['company']['field'] = $company;
$handler->display->display_options['fields']['company']['label'] = 'Company';
$handler->display->display_options['fields']['company']['alter']['strip_tags'] = TRUE;
/* Field: CiviCRM Custom: Business Programme: Location */
$handler->display->display_options['fields']['location_868']['id'] = 'location_868';
$handler->display->display_options['fields']['location_868']['table'] = $business_programm_data['table_name'];
$handler->display->display_options['fields']['location_868']['field'] = $location;
$handler->display->display_options['fields']['location_868']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['location_868']['empty'] = '<em>No location given</em>';
$handler->display->display_options['fields']['location_868']['empty_zero'] = TRUE;
/* Field: CiviCRM Custom: Business Programme: Short description Aim of Visit */
$handler->display->display_options['fields']['short_description_aim_of_visit_871']['id'] = 'short_description_aim_of_visit_871';
$handler->display->display_options['fields']['short_description_aim_of_visit_871']['table'] = $business_programm_data['table_name'];
$handler->display->display_options['fields']['short_description_aim_of_visit_871']['field'] = $aim;
$handler->display->display_options['fields']['short_description_aim_of_visit_871']['label'] = 'Aim of Visit';
$handler->display->display_options['fields']['short_description_aim_of_visit_871']['alter']['strip_tags'] = TRUE;
/* Field: CiviCRM Custom: Business Programme: Short description Result of Visit */
$handler->display->display_options['fields']['short_description_result_of_visi_872']['id'] = 'short_description_result_of_visi_872';
$handler->display->display_options['fields']['short_description_result_of_visi_872']['table'] = $business_programm_data['table_name'];
$handler->display->display_options['fields']['short_description_result_of_visi_872']['field'] = $result;
$handler->display->display_options['fields']['short_description_result_of_visi_872']['label'] = 'Intended Result of Visit';
$handler->display->display_options['fields']['short_description_result_of_visi_872']['alter']['strip_tags'] = TRUE;
/* Field: CiviCRM Activities: Activity ID */
$handler->display->display_options['fields']['id']['id'] = 'id';
$handler->display->display_options['fields']['id']['table'] = 'civicrm_activity';
$handler->display->display_options['fields']['id']['field'] = 'id';
$handler->display->display_options['fields']['id']['label'] = '';
$handler->display->display_options['fields']['id']['exclude'] = TRUE;
$handler->display->display_options['fields']['id']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['id']['separator'] = '';
/* Field: Global: Custom text */
$handler->display->display_options['fields']['nothing']['id'] = 'nothing';
$handler->display->display_options['fields']['nothing']['table'] = 'views';
$handler->display->display_options['fields']['nothing']['field'] = 'nothing';
$handler->display->display_options['fields']['nothing']['label'] = '';
$handler->display->display_options['fields']['nothing']['alter']['text'] = 'Edit';
$handler->display->display_options['fields']['nothing']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['nothing']['alter']['path'] = 'expert/business-programme-proposal/edit/[id]?destination=/expert/business-programme-proposal/!1';
$handler->display->display_options['fields']['nothing']['alter']['absolute'] = TRUE;
$handler->display->display_options['fields']['nothing']['element_label_colon'] = FALSE;
/* Field: Global: Custom text */
$handler->display->display_options['fields']['nothing_1']['id'] = 'nothing_1';
$handler->display->display_options['fields']['nothing_1']['table'] = 'views';
$handler->display->display_options['fields']['nothing_1']['field'] = 'nothing';
$handler->display->display_options['fields']['nothing_1']['label'] = '';
$handler->display->display_options['fields']['nothing_1']['alter']['text'] = 'Remove';
$handler->display->display_options['fields']['nothing_1']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['nothing_1']['alter']['path'] = 'expert/business-programme-proposal/delete/[id]?destination=/expert/business-programme-proposal/!1';
$handler->display->display_options['fields']['nothing_1']['alter']['absolute'] = TRUE;
$handler->display->display_options['fields']['nothing_1']['element_label_colon'] = FALSE;
/* Contextual filter: CiviCRM Cases: Case ID */
$handler->display->display_options['arguments']['id']['id'] = 'id';
$handler->display->display_options['arguments']['id']['table'] = 'civicrm_case';
$handler->display->display_options['arguments']['id']['field'] = 'id';
$handler->display->display_options['arguments']['id']['relationship'] = 'case_id';
$handler->display->display_options['arguments']['id']['default_action'] = 'not found';
$handler->display->display_options['arguments']['id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['id']['summary_options']['items_per_page'] = '25';
/* Filter criterion: CiviCRM Activities: Activity Type */
$handler->display->display_options['filters']['activity_type']['id'] = 'activity_type';
$handler->display->display_options['filters']['activity_type']['table'] = 'civicrm_activity';
$handler->display->display_options['filters']['activity_type']['field'] = 'activity_type';
$handler->display->display_options['filters']['activity_type']['value'] = array(
  $config->programme_activity_type_id => $config->programme_activity_type_id,
);
/* Filter criterion: CiviCRM Activities: Current Revision */
$handler->display->display_options['filters']['is_current_revision']['id'] = 'is_current_revision';
$handler->display->display_options['filters']['is_current_revision']['table'] = 'civicrm_activity';
$handler->display->display_options['filters']['is_current_revision']['field'] = 'is_current_revision';
$handler->display->display_options['filters']['is_current_revision']['value'] = '1';

/* Display: Page */
$handler = $view->new_display('page', 'Page', 'page');
$handler->display->display_options['path'] = 'expert/business-programme-proposal';
$translatables['business_link_program_details'] = array(
  t('Master'),
  t('Business programme'),
  t('more'),
  t('Apply'),
  t('Reset'),
  t('Sort by'),
  t('Asc'),
  t('Desc'),
  t('Items per page'),
  t('- All -'),
  t('Offset'),
  t('« first'),
  t('‹ previous'),
  t('next ›'),
  t('last »'),
  t('CiviCRM Case, with custom fields'),
  t('Visit from'),
  t('Visit ot'),
  t('Location'),
  t('Aim of Visit'),
  t('Result of Visit'),
  t('All'),
  t('Page'),
);
