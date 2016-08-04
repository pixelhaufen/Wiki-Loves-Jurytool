<?php
$config = array(
	// Database
	'dbhost' => 'db',
	'dbname' => 'WLJ',
	'dbuser' => 'WLJdbUser',
	'dbpassword' => 'password',
	'dbprefix' => 'wlx_',

	// User
	'salt' => '7bd3a47b907d2d0149be01ce6d63df8f972e4990',

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
	'language' => 'en', // de-at, de, en, sr

	// category Commons
	'catadd' => array(
		'Foreground_flowers',
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
	'title' => 'Wiki Loves Jurytool',
	'name' => '',
	'version' => '0.3.0',
	'url' => 'http://localhost:8000',
	'mail' => 'tooladmin@localhost:8000',
	'logo' => 'http://localhost:8000/theme/img/WLX.svg',
	'icon' => 'http://localhost:8000/theme/img/WLX.svg',
	'time' => '+2 hour', // commons loves UTC but MESZ is +2
);
?>