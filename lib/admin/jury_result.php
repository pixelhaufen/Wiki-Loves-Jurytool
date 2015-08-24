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

function jury_result($db)
{
	global $config;
	global $text;
	
	$uploader = "";
	$sql = "SELECT MAX(`round`) AS 'max' FROM `".$config["dbprefix"]."points`";
	$res = $db->query($sql);
	
	$loop = $res->num_rows;

	$row = $res->fetch_array(MYSQLI_ASSOC);
	$max = $row["max"];
	$res->close();
	
	if($max != "")
	{
		$sql = "SELECT `name` FROM `".$config["dbprefix"]."points` WHERE `round` = ".$max." ORDER BY `sum` DESC";
		$res = $db->query($sql);
	
		$uploader .= "<p>";
		$i = 0;
		while($row = $res->fetch_array(MYSQLI_ASSOC))
		{
			$i++;
			$uploader .= $i . ". [[" . $row['name'] . "|thumb]]<br>";
		}
		$res->close();
		$uploader .= "</p>";
	}
	else
	{
		$uploader .= "<p> - - - - </p>";
	}
	
	return $uploader;
}

?>