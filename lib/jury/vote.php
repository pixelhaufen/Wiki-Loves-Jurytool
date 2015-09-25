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

function vote($db)
{
	global $config;
	global $text;
	
	if(isset($_GET["a"]))
	{
		$file = $db->real_escape_string($_GET["f"]);
		$status = $db->real_escape_string($_GET["a"]);
		$user = $db->real_escape_string($_SESSION['us']);
		$sql = "UPDATE `" . $config['dbprefix'] . "votes` SET online='3' WHERE `name` = '$file' AND user = '$user'";
		$db->query($sql);
		$sql = "INSERT INTO " . $config['dbprefix'] . "votes(name,user,vote,time,online) VALUES ('$file','$user','$status','".time()."','1')";
		$db->query($sql);
		$sql = "DELETE FROM `" . $config['dbprefix'] . "votes` WHERE `online` = '3'";
		$db->query($sql);
	}
}

?>