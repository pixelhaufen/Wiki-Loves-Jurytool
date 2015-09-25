<?php
/**
 * Wiki Loves Jurytool
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

function login($db, $type = "v_")
{
	global $config;
	global $text;
	$numuser = 0;
	
	if(isset($_GET["p"]))
	{
		if($_GET["p"] == "logout")
		{
			if(version_compare(phpversion(), '5.4.0', '>='))
			{
				if(session_status() != PHP_SESSION_NONE)
				{
					session_destroy();
				}
			}
			else
			{
				if(session_id() != "")
				{
					session_destroy();
				}
			}
			$_SESSION['us'] = "";
			$_SESSION['pw'] = "";
		}
	}
	if(isset($_POST["name"]))
	{
		$_SESSION['us'] = $_POST["name"];
		$_SESSION['pw'] = $db->real_escape_string(sha1($_POST["pw"].$config["salt"]));
	}
	
	if(isset($_SESSION['us']))
	{
		$user = $db->real_escape_string($_SESSION['us']);
		$pw = $db->real_escape_string($_SESSION['pw']);
		$sql = "SELECT `width`, `info`, `userlevel` FROM `" . $config['dbprefix'] . $type . "jury` WHERE `lname` LIKE '$user' AND `pw` LIKE '$pw'";
		$res = $db->query($sql);
		$numuser = $res->num_rows;
	}
	
	if($numuser == 1)
	{
		$row = $res->fetch_array(MYSQLI_ASSOC);
		$user = $db->real_escape_string($_SESSION['us']);
		$_SESSION['width'] = $row['width'];
		$_SESSION['info'] = $row['info'];
		$_SESSION['userlevel'] = $row['userlevel'];

		$sql = "UPDATE `" . $config['dbprefix'] . $type . "jury` SET `time`='".time()."' WHERE `lname`= '".$user."'";
		$db->query($sql);
		
		return "OK";
	}
	else
	{
		if(isset($_POST["name"]) && ($config['log']!="NO"))
		{
			append_file("../log/login.txt","\n" . date(DATE_RFC822) . "\t" . $_SESSION['us'] . "\t" . $_SESSION['pw']);
		}
	return "<p style=\"color: orange;\"> <br><b>Login</b><br> </p>
		<form action=\"index.php\" method=\"post\">

		<table width=\"180\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr valign=\"top\">
				<td>
					" . $text["username"] . ":<br>&nbsp;
				</td>
				<td>
					<input name=\"name\"><br> 
				</td>
			</tr>
			<tr valign=\"top\">
				<td>
					" . $text["password"] . ":<br>
				</td>
				<td>
					<input type=\"password\" name=\"pw\"><br> 
				</td>
			</tr>
			<tr valign=\"top\">
				<td> <br> 
				</td>
				<td>
					<div align=\"right\"><input type=\"submit\" value=\" " . $text["login"] . " \"><div>
				</td>
			</tr>
		</table>
		</form></div>";
	}
	$res->close();
}

?>