<?php

$cgroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Workhistory'));
$name = civicrm_api3('CustomField', 'getvalue', array('name' => 'Name_of_Organisation', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$title = civicrm_api3('CustomField', 'getvalue', array('name' => 'Job_Title', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$city = civicrm_api3('CustomField', 'getvalue', array('name' => 'City', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$country = civicrm_api3('CustomField', 'getvalue', array('name' => 'Country', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$responsebilities = civicrm_api3('CustomField', 'getvalue', array('name' => 'Responsibilities', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$description = civicrm_api3('CustomField', 'getvalue', array('name' => 'Description', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$competences = civicrm_api3('CustomField', 'getvalue', array('name' => 'Competences_used_in_this_job', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$from = civicrm_api3('CustomField', 'getvalue', array('name' => 'Yeard_From', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$to = civicrm_api3('CustomField', 'getvalue', array('name' => 'To', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));
$countries_visited = civicrm_api3('CustomField', 'getvalue', array('name' => 'Countries_visited_in_relation_to_the_job', 'return' => 'column_name', 'custom_group_id' => $cgroup['id']));

$view = new view();
$view->name = 'expert_cv_work_experience_';
$view->description = 'Work history of expert CV';
$view->tag = 'expert cv';
$view->base_table = 'civicrm_contact';
$view->human_name = 'Expert CV (Work experience)';
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
/* Field: CiviCRM Custom: Workhistory: Name of Organisation */
$handler->display->display_options['fields']['name_of_organisation_622']['id'] = 'name_of_organisation_622';
$handler->display->display_options['fields']['name_of_organisation_622']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['name_of_organisation_622']['field'] = $name;
$handler->display->display_options['fields']['name_of_organisation_622']['label'] = '';
$handler->display->display_options['fields']['name_of_organisation_622']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['name_of_organisation_622']['element_type'] = 'strong';
$handler->display->display_options['fields']['name_of_organisation_622']['element_label_colon'] = FALSE;
/* Field: CiviCRM Custom: Workhistory: Job Title */
$handler->display->display_options['fields']['job_title_627']['id'] = 'job_title_627';
$handler->display->display_options['fields']['job_title_627']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['job_title_627']['field'] = $title;
$handler->display->display_options['fields']['job_title_627']['label'] = '';
$handler->display->display_options['fields']['job_title_627']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['job_title_627']['element_type'] = 'em';
$handler->display->display_options['fields']['job_title_627']['element_label_colon'] = FALSE;
/* Field: CiviCRM Custom: Workhistory: City */
$handler->display->display_options['fields']['city_623']['id'] = 'city_623';
$handler->display->display_options['fields']['city_623']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['city_623']['field'] = $city;
$handler->display->display_options['fields']['city_623']['alter']['strip_tags'] = TRUE;
/* Field: CiviCRM Custom: Workhistory: Country */
$handler->display->display_options['fields']['country_624']['id'] = 'country_624';
$handler->display->display_options['fields']['country_624']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['country_624']['field'] = $country;
/* Field: CiviCRM Custom: Workhistory: Responsibilities */
$handler->display->display_options['fields']['responsibilities_634']['id'] = 'responsibilities_634';
$handler->display->display_options['fields']['responsibilities_634']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['responsibilities_634']['field'] = $responsebilities;
/* Field: CiviCRM Custom: Workhistory: Description */
$handler->display->display_options['fields']['description_632']['id'] = 'description_632';
$handler->display->display_options['fields']['description_632']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['description_632']['field'] = $description;
/* Field: CiviCRM Custom: Workhistory: Competences used in this job */
$handler->display->display_options['fields']['competences_used_in_this_job_633']['id'] = 'competences_used_in_this_job_633';
$handler->display->display_options['fields']['competences_used_in_this_job_633']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['competences_used_in_this_job_633']['field'] = $competences;
/* Field: CiviCRM Custom: Workhistory: Countries visited in relation to the job */
$handler->display->display_options['fields']['countries_visited_in_relation_to_635']['id'] = 'countries_visited_in_relation_to_635';
$handler->display->display_options['fields']['countries_visited_in_relation_to_635']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['countries_visited_in_relation_to_635']['field'] = $countries_visited;
/* Field: CiviCRM Custom: Workhistory: Years - From */
$handler->display->display_options['fields']['yeard_from_625']['id'] = 'yeard_from_625';
$handler->display->display_options['fields']['yeard_from_625']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['yeard_from_625']['field'] = $from;
$handler->display->display_options['fields']['yeard_from_625']['label'] = '';
$handler->display->display_options['fields']['yeard_from_625']['exclude'] = TRUE;
$handler->display->display_options['fields']['yeard_from_625']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['yeard_from_625']['element_type'] = '0';
$handler->display->display_options['fields']['yeard_from_625']['element_label_colon'] = FALSE;
/* Field: CiviCRM Custom: Workhistory: To */
$handler->display->display_options['fields']['to_626']['id'] = 'to_626';
$handler->display->display_options['fields']['to_626']['table'] = $cgroup['table_name'];
$handler->display->display_options['fields']['to_626']['field'] = $to;
$handler->display->display_options['fields']['to_626']['label'] = '';
$handler->display->display_options['fields']['to_626']['exclude'] = TRUE;
$handler->display->display_options['fields']['to_626']['alter']['strip_tags'] = TRUE;
$handler->display->display_options['fields']['to_626']['element_type'] = '0';
$handler->display->display_options['fields']['to_626']['element_label_colon'] = FALSE;
/* Field: Global: Custom text */
$handler->display->display_options['fields']['nothing']['id'] = 'nothing';
$handler->display->display_options['fields']['nothing']['table'] = 'views';
$handler->display->display_options['fields']['nothing']['field'] = 'nothing';
$handler->display->display_options['fields']['nothing']['label'] = 'Period';
$handler->display->display_options['fields']['nothing']['alter']['text'] = '[yeard_from_625] - [to_626]';
/* Contextual filter: CiviCRM Contacts: Contact ID */
$handler->display->display_options['arguments']['id']['id'] = 'id';
$handler->display->display_options['arguments']['id']['table'] = 'civicrm_contact';
$handler->display->display_options['arguments']['id']['field'] = 'id';
$handler->display->display_options['arguments']['id']['default_action'] = 'access denied';
$handler->display->display_options['arguments']['id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['id']['summary_options']['items_per_page'] = '25';
$translatables['expert_cv_work_experience_'] = array(
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
  t('City'),
  t('Country'),
  t('Responsibilities'),
  t('Description'),
  t('Competences used in this job'),
  t('Countries visited in relation to the job'),
  t('Period'),
  t('[yeard_from_625] - [to_626]'),
  t('All'),
);
