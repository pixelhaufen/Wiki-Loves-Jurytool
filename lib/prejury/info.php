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

function info_round($db)
{
	global $config;
	global $text;
	
	$info = "<table><tr><td>".$text["round"]."</td><td>".$text["photos"]."</td></tr>";
	$sql = "SELECT count(`vote`) AS votes, `vote` AS roud FROM `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online`= 2) AND `exclude`=0 GROUP BY `vote` ORDER BY roud  ASC";
	$res = $db->query($sql);
	
	$loop = $res->num_rows;

	if ($loop != 0)
	{
		while($row = $res->fetch_array(MYSQLI_ASSOC))
		{
			$info .= "<tr><td>" . $row['roud'] . "</td><td>" . $row['votes'] . "</td></tr>";
		}
	}
	$info .= "</table>";
	
	$res->close();
	
	return $info;
}

function info_votes($db)
{
	global $config;
	global $text;
	
	$info = "<table><tr><td>".$text["username"]."</td><td>".$text["votes"]."</td></tr>";
	$sql = "SELECT `user`, count(`user`) AS count FROM `" . $config['dbprefix'] . "v_votes` WHERE `vote`!=0 GROUP BY `user` ORDER BY count ASC";
	$res = $db->query($sql);

	$loop = $res->num_rows;

	if ($loop != 0)
	{
		while($row = $res->fetch_array(MYSQLI_ASSOC))
		{
			$info .= "<tr><td>" . $row['user'] . "</td><td>" . $row['count'] . "</td></tr>";
		}
	}
	$res->close();
	$info .= "</table><p> </p>";
	
	return $info;
}

?>