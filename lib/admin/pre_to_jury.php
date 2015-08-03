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

function export_to_jury($db)
{
	global $config;
	global $text;
	
	// reset
	$sql = "UPDATE `".$config["dbprefix"]."fotos` SET `jury` = 0 WHERE 1";
	$db->query($sql);
	
	$sql = "SELECT `v1`.`name`, votes, points FROM( SELECT `name`, COUNT(`name`) AS votes, SUM(`vote`)/COUNT(`name`) AS points FROM (SELECT `name`, `vote` FROM `".$config["dbprefix"]."v_votes` WHERE `vote` != 0)x GROUP BY `name` ORDER BY `points` DESC )v1 RIGHT JOIN (SELECT `name` FROM  `".$config["dbprefix"]."fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0) )v2 ON `v1`.`name` LIKE `v2`.`name`  WHERE points >= ".$_GET["cut"];
	$res = $db->query($sql);
	$loop = $res->num_rows;
	if ($loop != 0)
	{
		while($row = $res->fetch_array(MYSQLI_ASSOC))
		{
			$name = $row['name'];
			$sql = "UPDATE `".$config["dbprefix"]."fotos` SET `jury` = 1 WHERE `name` = '$name'";
			$db->query($sql);
		}
	}
	$uploader = "<p>".$text["created_jury"]."</p>";
	
	return $uploader;
}

function calculate_position($db)
{
	global $config;
	global $text;
	
	$sql = "SELECT `v1`.`name`, votes, points FROM( SELECT `name`, COUNT(`name`) AS votes, SUM(`vote`)/COUNT(`name`) AS points FROM (SELECT `name`, `vote` FROM `".$config["dbprefix"]."v_votes` WHERE `vote` != 0)x GROUP BY `name` ORDER BY `points` DESC )v1 RIGHT JOIN (SELECT `name` FROM  `".$config["dbprefix"]."fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0) )v2 ON `v1`.`name` LIKE `v2`.`name` ORDER BY points DESC";
	$res = $db->query($sql);

	$uploader = "<table>";
	$uploader .= "
		<tr>
			<td>place</td>
			<td>points</td>
			<td>votes</td>
			<td>name</td>
		</tr>";
	$i = 1;
	while($row = $res->fetch_array(MYSQLI_ASSOC)) // loop images
	{
		$uploader .= "
			<tr>
				<td>".$i."</td>
				<td><a href=\"index.php?p2j=m&cut=" . $row['points'] . "\">" . $row['points'] . "</a></td>
				<td>" . $row['votes'] . "</td>
				<td>" . $row['name'] . "</td>
			</tr>";
		$i++;
	}
	$uploader .= "</table>";
	
	return $uploader;
}

?>