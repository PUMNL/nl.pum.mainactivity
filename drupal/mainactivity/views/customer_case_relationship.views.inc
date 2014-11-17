<?php

$view = new view();
$view->name = 'customer_cases_relationship_';
$view->description = '';
$view->tag = 'default';
$view->base_table = 'civicrm_contact';
$view->human_name = 'Customer cases (relationship)';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Customer cases (relationship)';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'role';
$handler->display->display_options['access']['role'] = mainactivity_get_role_ids(array('Customer (contact)'));
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['pager']['options']['items_per_page'] = '10';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Relationship: CiviCRM Contacts: CiviCRM Relationship (starting from Contact B) */
$handler->display->display_options['relationships']['relationship_id_b']['id'] = 'relationship_id_b';
$handler->display->display_options['relationships']['relationship_id_b']['table'] = 'civicrm_contact';
$handler->display->display_options['relationships']['relationship_id_b']['field'] = 'relationship_id_b';
$handler->display->display_options['relationships']['relationship_id_b']['required'] = TRUE;
$handler->display->display_options['relationships']['relationship_id_b']['relationship_type'] = '0';
/* Field: CiviCRM Relationships: Relationship Type A-to-B */
$handler->display->display_options['fields']['relationship_type']['id'] = 'relationship_type';
$handler->display->display_options['fields']['relationship_type']['table'] = 'civicrm_relationship';
$handler->display->display_options['fields']['relationship_type']['field'] = 'relationship_type';
$handler->display->display_options['fields']['relationship_type']['relationship'] = 'relationship_id_b';
$handler->display->display_options['fields']['relationship_type']['label'] = '';
$handler->display->display_options['fields']['relationship_type']['exclude'] = TRUE;
$handler->display->display_options['fields']['relationship_type']['element_label_colon'] = FALSE;
/* Field: CiviCRM Contacts: Display Name */
$handler->display->display_options['fields']['display_name']['id'] = 'display_name';
$handler->display->display_options['fields']['display_name']['table'] = 'civicrm_contact';
$handler->display->display_options['fields']['display_name']['field'] = 'display_name';
$handler->display->display_options['fields']['display_name']['label'] = '';
$handler->display->display_options['fields']['display_name']['alter']['alter_text'] = TRUE;
$handler->display->display_options['fields']['display_name']['alter']['text'] = '[relationship_type]: [display_name]';
$handler->display->display_options['fields']['display_name']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['display_name']['link_to_civicrm_contact'] = 0;
/* Contextual filter: CiviCRM Relationships: Case ID */
$handler->display->display_options['arguments']['case_id']['id'] = 'case_id';
$handler->display->display_options['arguments']['case_id']['table'] = 'civicrm_relationship';
$handler->display->display_options['arguments']['case_id']['field'] = 'case_id';
$handler->display->display_options['arguments']['case_id']['relationship'] = 'relationship_id_b';
$handler->display->display_options['arguments']['case_id']['default_action'] = 'access denied';
$handler->display->display_options['arguments']['case_id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['case_id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['case_id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['case_id']['summary_options']['items_per_page'] = '25';

/* Display: Page */
$handler = $view->new_display('page', 'Page', 'page');
$handler->display->display_options['path'] = 'customer-cases-relationship-';
$translatables['customer_cases_relationship_'] = array(
  t('Master'),
  t('Customer cases (relationship)'),
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
  t('CiviCRM Relationship (starting from contact B)'),
  t('[relationship_type]: [display_name]'),
  t('All'),
  t('Page'),
);
