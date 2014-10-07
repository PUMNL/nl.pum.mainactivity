<?php

$cgroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Add_Keyqualifications'));
$qualifications = civicrm_api3('CustomField', 'getvalue', array('name' => 'Key_Qualifications', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$activity_type_option_group = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'activity_type'));
$accept_ma_proposal_id = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Accept Main Activity Proposal', 'option_group_id' => $activity_type_option_group));

$view = new view();
$view->name = 'expert_cv_project_qualifications_';
$view->description = 'Project qualifications for expert cv';
$view->tag = 'exter cv';
$view->base_table = 'civicrm_activity';
$view->human_name = 'Expert CV (Project qualifications)';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'none';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['pager']['options']['items_per_page'] = '0';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['pager']['options']['id'] = '0';
$handler->display->display_options['pager']['options']['quantity'] = '9';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Relationship: CiviCRM Case Activity: Case ID */
$handler->display->display_options['relationships']['case_id']['id'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['table'] = 'civicrm_case_activity';
$handler->display->display_options['relationships']['case_id']['field'] = 'case_id';
$handler->display->display_options['relationships']['case_id']['required'] = TRUE;
/* Field: CiviCRM Activities: Scheduled Activity Date */
$handler->display->display_options['fields']['activity_date_time']['id'] = 'activity_date_time';
$handler->display->display_options['fields']['activity_date_time']['table'] = 'civicrm_activity';
$handler->display->display_options['fields']['activity_date_time']['field'] = 'activity_date_time';
$handler->display->display_options['fields']['activity_date_time']['label'] = '';
$handler->display->display_options['fields']['activity_date_time']['exclude'] = TRUE;
$handler->display->display_options['fields']['activity_date_time']['element_type'] = 'em';
$handler->display->display_options['fields']['activity_date_time']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['activity_date_time']['date_format'] = 'd_m_y';
/* Field: CiviCRM Custom: Accept Main Activity Proposal: Project Qualifications */
$handler->display->display_options['fields']['key_qualifications_313']['id'] = 'key_qualifications_313';
$handler->display->display_options['fields']['key_qualifications_313']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['key_qualifications_313']['field'] = $qualifications;
$handler->display->display_options['fields']['key_qualifications_313']['label'] = '';
$handler->display->display_options['fields']['key_qualifications_313']['alter']['alter_text'] = TRUE;
$handler->display->display_options['fields']['key_qualifications_313']['alter']['text'] = '[activity_date_time]: [key_qualifications_313]';
$handler->display->display_options['fields']['key_qualifications_313']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['key_qualifications_313']['element_label_colon'] = FALSE;
/* Sort criterion: CiviCRM Activities: Scheduled Activity Date */
$handler->display->display_options['sorts']['activity_date_time']['id'] = 'activity_date_time';
$handler->display->display_options['sorts']['activity_date_time']['table'] = 'civicrm_activity';
$handler->display->display_options['sorts']['activity_date_time']['field'] = 'activity_date_time';
$handler->display->display_options['sorts']['activity_date_time']['order'] = 'DESC';
$handler->display->display_options['sorts']['activity_date_time']['granularity'] = 'day';
/* Contextual filter: CiviCRM Activity Contacts: Activity Contact ID */
$handler->display->display_options['arguments']['contact_id']['id'] = 'contact_id';
$handler->display->display_options['arguments']['contact_id']['table'] = 'civicrm_activity_contact';
$handler->display->display_options['arguments']['contact_id']['field'] = 'contact_id';
$handler->display->display_options['arguments']['contact_id']['default_action'] = 'access denied';
$handler->display->display_options['arguments']['contact_id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['contact_id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['contact_id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['contact_id']['summary_options']['items_per_page'] = '25';
/* Contextual filter: CiviCRM Cases: Case ID */
$handler->display->display_options['arguments']['id']['id'] = 'id';
$handler->display->display_options['arguments']['id']['table'] = 'civicrm_case';
$handler->display->display_options['arguments']['id']['field'] = 'id';
$handler->display->display_options['arguments']['id']['relationship'] = 'case_id';
$handler->display->display_options['arguments']['id']['default_action'] = 'access denied';
$handler->display->display_options['arguments']['id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['id']['summary_options']['items_per_page'] = '25';
/* Filter criterion: CiviCRM Activities: Activity Status */
$handler->display->display_options['filters']['status']['id'] = 'status';
$handler->display->display_options['filters']['status']['table'] = 'civicrm_activity';
$handler->display->display_options['filters']['status']['field'] = 'status';
$handler->display->display_options['filters']['status']['value'] = array(
  2 => '2',
);
/* Filter criterion: CiviCRM Activities: Activity Type */
$handler->display->display_options['filters']['activity_type']['id'] = 'activity_type';
$handler->display->display_options['filters']['activity_type']['table'] = 'civicrm_activity';
$handler->display->display_options['filters']['activity_type']['field'] = 'activity_type';
$handler->display->display_options['filters']['activity_type']['value'] = array(
  $accept_ma_proposal_id => $accept_ma_proposal_id,
);
/* Filter criterion: CiviCRM Activity Contacts: Record Type */
$handler->display->display_options['filters']['record_type']['id'] = 'record_type';
$handler->display->display_options['filters']['record_type']['table'] = 'civicrm_activity_contact';
$handler->display->display_options['filters']['record_type']['field'] = 'record_type';
$handler->display->display_options['filters']['record_type']['value'] = array(
  2 => '2',
);
$translatables['expert_cv_project_qualifications_'] = array(
  t('Master'),
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
  t('[activity_date_time]: [key_qualifications_313]'),
  t('All'),
);
