<?php
/**
 * Wiki Loves Jurytool cronjob
 *
 * @author Ruben Demus
 * @copyright 2015 Ruben Demus
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// some libs
require_once "config/config.php"; // config stuff
require_once "lib/lib.php"; // file functions
require_once "lib/commons/list.php"; // get files from commons
require_once "lib/commons/list_exclude.php"; // get files from commons not_for_prejury
require_once "lib/commons/info.php"; // get info from commons

// user_agent for bot
$user_agent = $config['name'] .  "/" . $config['version']. " (" . $config['url'] . "; " . $config['mail'] . ")";
ini_set('user_agent', $user_agent);

// mysql
$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpassword'], $config['dbname']);

if ($db->connect_error)
{
	// log error
	if($config['log']!="NO")
	{
		append_file("log/cron.txt","\n".date(DATE_RFC822)."\tdb connect_error\tmain()");
	}
}
else
{
	if(commons_get_list($db) == "STOP") // lib/getcommons_list.php
	{
		$db->close();
		return;
	}

	if(commons_get_list_exclude($db) == "STOP") // lib/getcommons_list_exclude.php
	{
		$db->close();
		return;
	}

	// lib/getcommons_info.php
	commons_get_new_info($db);
	commons_get_licence($db);
	
	$db->close();
} // $db


?>

