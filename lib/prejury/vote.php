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
		$user = $db->real_escape_string($_SESSION['us']);
		$status = $db->real_escape_string($_GET["a"]);
		$sql = "UPDATE `" . $config['dbprefix'] . "v_votes` SET online='3' WHERE `name` = '$file' AND user = '$user' AND vote = '0'";
		$db->query($sql);
		$was_ignore = $db->affected_rows; // 1 yes(war 0) 0 no
		$sql = "UPDATE `" . $config['dbprefix'] . "v_votes` SET online='3' WHERE `name` = '$file' AND user = '$user'";
		$db->query($sql);
		$is_new_vote = $db->affected_rows; // 0 yes 1 no
		$sql = "INSERT INTO " . $config['dbprefix'] . "v_votes(name,user,vote,time,online) VALUES ('$file','$user','$status','".time()."','1')";
		$db->query($sql);
		$sql = "DELETE FROM `" . $config['dbprefix'] . "v_votes` WHERE `online` = '3'";
		$db->query($sql);
		
		if($is_new_vote == 0) // new or was 0
		{
			if ($status!=0) // not 0 -> +1
			{
				$sql = "UPDATE " . $config['dbprefix'] . "fotos SET vote = vote + 1 WHERE name = '$file'";
				$db->query($sql);
			} // is 0 -> stays
		}
		else // change vote
		{
			if (($status == 0)&&($was_ignore == 0)) // from vote to 0 -> -1
			{
				$sql = "UPDATE " . $config['dbprefix'] . "fotos SET vote = vote - 1 WHERE name = '$file'";
				$db->query($sql);
			} // was 0 -> no change
		}
	}
}

?>