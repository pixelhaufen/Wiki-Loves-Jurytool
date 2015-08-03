<?php session_start(); 
/**
 * Wiki Loves Jurytool prejury
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

require_once "../lib/lib.php"; // file functions
require_once "../config/config.php"; // config stuff
require_once "../l10n/".$config['language'].".php"; // lang
require_once "../lib/login.php"; // login functions
require_once "../lib/meeting/meeting.php"; // meeting functions

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?php echo $config['language'];?>" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $config['title']; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../theme/styles.css">


<?php

$log = "-";

// mysql
$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpassword'], $config['dbname']);

if ($db->connect_error)
{
	// log error
	if($config['log']!="NO")
	{
		append_file("log/admin.txt","\n".date(DATE_RFC822)."\tdb connect_error\tmain()");
	}
}
else
{
	$uploader = "";
	
	$log = login($db,"");
	
	if($log=="OK")
	{
		
		// admin 1 or manager 2
		if(($_SESSION['userlevel']==1)||($_SESSION['userlevel']==2))
		{
			$uploader = get_meeting($db);
		}
		else // user
		{
			$uploader = $text["contact_admin"] . ": " . $config['mail'];
		} // end admin
	} // end loggeg in
} // end $db

$db->close();

?>

</head>

<body style="background-color: white; text-align: center;">

	<center>
		
		<?php echo $uploader; ?>
		
		<p style="color: #000;"><?php echo $text['footer'];?>, <a href="https://www.gnu.org/licenses/agpl-3.0.en.html">GNU AGPL v3</a> </p>

		</center>

</body>
