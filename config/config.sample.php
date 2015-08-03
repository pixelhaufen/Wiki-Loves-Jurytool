<?php
/*
* use install.php 
*/

$config = array(
	// Database
	'dbhost' => 'localhost',
	'dbname' => 'WLJ',
	'dbuser' => 'WLJdbUser',
	'dbpassword' => '',
	'dbprefix' => 'wlx_2015_',

	// User
	'salt' => '0e2011e76b771e9b92d96c9ea93c7ba8be3d9d16',

	// Logfiles
	'log' => 'NORMAL', // NO, NORMAL, PARANOID, DEBUG

	// deadline Server time
	'dlyear' => 2038,
	'dlday' => 19,
	'dlmonth' => 1,
	'dlhour' => 3,
	'dlminute' => 14,
	'dlsecond' => 8,

	// jury goal
	'goal' => 10,
	
	// language
	'language' => 'en', // de-at, de, en

	// category Commons
	'catadd' => array(
		'Images_from_Wiki_Loves_XXX_20XX_in_XXX',
		),
	'catremove' => array(
		'Images_from_Wiki_Loves_XXX_20XX_in_XXX_not_for_prejury',
		),

	'license' => array(
		'cc-by-sa-3',
		'cc-by-sa-4',
		'pd-self',
		'cc-zero',
		),

	// general settings
	'title' => '',
	'name' => '',
	'version' => '0.1.0',
	'url' => 'http://localhost/WLJurytool',
	'mail' => 'tooladmin@localhost',
	'logo' => 'http://localhost/WLJurytool/theme/img/WLX.svg',
	'icon' => 'http://localhost/WLJurytool/theme/img/WLX.svg',
	'time' => '+2 hour', // commons loves UTC but MESZ is +2

);

?>
