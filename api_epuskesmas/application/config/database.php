<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = "default";
$active_record = TRUE;

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "root";
$db['default']['password'] = "";
$db['default']['database'] = "epuskesmas_live_jaktim_P3172080101";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$cl_phc = array('P3172080101','P3172040201','P3172010202','P3172010203');

foreach ($cl_phc as $key) {
	$db_name = 'epuskesmas_live_jaktim_'.$key;
	$db[$db_name]['hostname'] = $db['default']['hostname'];
	$db[$db_name]['username'] = $db['default']['username'];
	$db[$db_name]['password'] = $db['default']['password'];
	$db[$db_name]['database'] = $db_name;
	$db[$db_name]['dbdriver'] = "mysql";
	$db[$db_name]['dbprefix'] = "";
	$db[$db_name]['pconnect'] = FALSE;
	$db[$db_name]['db_debug'] = TRUE;
	$db[$db_name]['cache_on'] = FALSE;
	$db[$db_name]['cachedir'] = "";
	$db[$db_name]['char_set'] = "utf8";
	$db[$db_name]['dbcollat'] = "utf8_general_ci";
	$db[$db_name]['swap_pre'] = '';
	$db[$db_name]['autoinit'] = TRUE;
	$db[$db_name]['stricton'] = FALSE;
}

