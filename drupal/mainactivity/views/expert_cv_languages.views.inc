<?php

$cgroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Languages'));
$lang = civicrm_api3('CustomField', 'getvalue', array('name' => 'Language', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$level = civicrm_api3('CustomField', 'getvalue', array('name' => 'Level', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));

$view = new view();
$view->name = 'expert_cv_languages_';
$view->description = 'Languages of expert CV';
$view->tag = 'expert cv';
$view->base_table = 'civicrm_contact';
$view->human_name = 'Expert CV (Languages)';
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
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Field: CiviCRM Custom: Languages: Languages */
$handler->display->display_options['fields']['language_431']['id'] = 'language_431';
$handler->display->display_options['fields']['language_431']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['language_431']['field'] = $lang;
/* Field: CiviCRM Custom: Languages: Level */
$handler->display->display_options['fields']['level_432']['id'] = 'level_432';
$handler->display->display_options['fields']['level_432']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['level_432']['field'] = $level;
/* Contextual filter: CiviCRM Contacts: Contact ID */
$handler->display->display_options['arguments']['id']['id'] = 'id';
$handler->display->display_options['arguments']['id']['table'] = 'civicrm_contact';
$handler->display->display_options['arguments']['id']['field'] = 'id';
$handler->display->display_options['arguments']['id']['default_action'] = 'access denied';
$handler->display->display_options['arguments']['id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['id']['summary_options']['items_per_page'] = '25';
$translatables['expert_cv_languages_'] = array(
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
  t('Languages'),
  t('Level'),
  t('All'),
);
