<?php

$cgroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Education'));
$name = civicrm_api3('CustomField', 'getvalue', array('name' => 'Name_of_Institution', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$level = civicrm_api3('CustomField', 'getvalue', array('name' => 'Education_level', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$major = civicrm_api3('CustomField', 'getvalue', array('name' => 'Field_of_study_major', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$city = civicrm_api3('CustomField', 'getvalue', array('name' => 'City', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$country = civicrm_api3('CustomField', 'getvalue', array('name' => 'Country', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$diploma = civicrm_api3('CustomField', 'getvalue', array('name' => 'Diploma_Degree', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$from = civicrm_api3('CustomField', 'getvalue', array('name' => 'Years_From', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$to = civicrm_api3('CustomField', 'getvalue', array('name' => 'To', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));

$view = new view();
$view->name = 'expert_cv_education_';
$view->description = 'Education of expert cv';
$view->tag = 'expert cv';
$view->base_table = 'civicrm_contact';
$view->human_name = 'Expert CV (Education)';
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
/* Field: CiviCRM Custom: Education: Name of Institution */
$handler->display->display_options['fields']['name_of_institution_586']['id'] = 'name_of_institution_586';
$handler->display->display_options['fields']['name_of_institution_586']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['name_of_institution_586']['field'] = $name;
$handler->display->display_options['fields']['name_of_institution_586']['label'] = '';
$handler->display->display_options['fields']['name_of_institution_586']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['name_of_institution_586']['element_type'] = 'strong';
$handler->display->display_options['fields']['name_of_institution_586']['element_label_colon'] = FALSE;
/* Field: CiviCRM Custom: Education: Education level */
$handler->display->display_options['fields']['education_level_590']['id'] = 'education_level_590';
$handler->display->display_options['fields']['education_level_590']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['education_level_590']['field'] = $level;
$handler->display->display_options['fields']['education_level_590']['alter']['strip_tags'] = TRUE;
/* Field: CiviCRM Custom: Education: Field of study/major */
$handler->display->display_options['fields']['field_of_study_major_587']['id'] = 'field_of_study_major_587';
$handler->display->display_options['fields']['field_of_study_major_587']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['field_of_study_major_587']['field'] = $major;
$handler->display->display_options['fields']['field_of_study_major_587']['alter']['strip_tags'] = TRUE;
/* Field: CiviCRM Custom: Education: City */
$handler->display->display_options['fields']['city_588']['id'] = 'city_588';
$handler->display->display_options['fields']['city_588']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['city_588']['field'] = $city;
$handler->display->display_options['fields']['city_588']['alter']['strip_tags'] = TRUE;
/* Field: CiviCRM Custom: Education: Country */
$handler->display->display_options['fields']['country_589']['id'] = 'country_589';
$handler->display->display_options['fields']['country_589']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['country_589']['field'] = $country;
/* Field: CiviCRM Custom: Education: Diploma/Degree */
$handler->display->display_options['fields']['diploma_degree_593']['id'] = 'diploma_degree_593';
$handler->display->display_options['fields']['diploma_degree_593']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['diploma_degree_593']['field'] = $diploma;
/* Field: CiviCRM Custom: Education: Years - From */
$handler->display->display_options['fields']['years_from_591']['id'] = 'years_from_591';
$handler->display->display_options['fields']['years_from_591']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['years_from_591']['field'] = $from;
$handler->display->display_options['fields']['years_from_591']['label'] = '';
$handler->display->display_options['fields']['years_from_591']['exclude'] = TRUE;
$handler->display->display_options['fields']['years_from_591']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['years_from_591']['element_label_colon'] = FALSE;
/* Field: CiviCRM Custom: Education: To */
$handler->display->display_options['fields']['to_592']['id'] = 'to_592';
$handler->display->display_options['fields']['to_592']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['to_592']['field'] = $to;
$handler->display->display_options['fields']['to_592']['label'] = '';
$handler->display->display_options['fields']['to_592']['exclude'] = TRUE;
$handler->display->display_options['fields']['to_592']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['to_592']['element_label_colon'] = FALSE;
/* Field: Global: Custom text */
$handler->display->display_options['fields']['nothing']['id'] = 'nothing';
$handler->display->display_options['fields']['nothing']['table'] = 'views';
$handler->display->display_options['fields']['nothing']['field'] = 'nothing';
$handler->display->display_options['fields']['nothing']['label'] = 'Period';
$handler->display->display_options['fields']['nothing']['alter']['text'] = '[years_from_591] - [to_592]';
/* Contextual filter: CiviCRM Contacts: Contact ID */
$handler->display->display_options['arguments']['id']['id'] = 'id';
$handler->display->display_options['arguments']['id']['table'] = 'civicrm_contact';
$handler->display->display_options['arguments']['id']['field'] = 'id';
$handler->display->display_options['arguments']['id']['default_action'] = 'access denied';
$handler->display->display_options['arguments']['id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['id']['summary_options']['items_per_page'] = '25';
$translatables['expert_cv_education_'] = array(
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
  t('Education level'),
  t('Field of study/major'),
  t('City'),
  t('Country'),
  t('Diploma/Degree'),
  t('Period'),
  t('[years_from_591] - [to_592]'),
  t('All'),
);
