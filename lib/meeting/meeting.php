<?php
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

function add2sum($n_arr)
{
	$numbers = explode("+",$n_arr);
	$loop = count($numbers);
	$i = 0;
	$sum = 0;
	while($i < $loop)
	{
		$sum += $numbers[$i];
		$i++;
	}
	return $sum;
}

function get_meeting($db)
{
	global $config;
	global $text;
	
	$bg[0] = "style=\"background-color:#FAFAFA;\"";
	$bg[1] = "style=\"background-color:#EEE;\"";
	$bg[2] = "style=\"background-color:#DDD;\"";
	
	$uploader = "";
	
	if(isset($_GET["r"])) // round 2
	{
		// save
		$round = $_GET["r"];
		$i = 0;
		while($i < $_POST["files"])
		{
			$i++;
			$file = $db->real_escape_string(urldecode($_POST["name$i"]));
			$sum = add2sum($_POST["value$i"]);
			$sql = "INSERT INTO `".$config['dbprefix']."points`(`name`, `round`, `sum`) VALUES ('$file','$round','$sum')";
			$db->query($sql);
		}
		// get selected
		$sql = "SELECT `votefiles`.`name`, `votefiles`.`sum` AS points, `votefotos`.`user`, `votefotos`.`date`, `votefotos`.`time`, `votefotos`.`size`, `votefotos`.`width`, `votefotos`.`height`, `votefotos`.`url`, `votefotos`.`thumburl`, `votefotos`.`descriptionurl`, `votefotos`.`license` FROM (SELECT name, sum FROM `".$config['dbprefix']."points` WHERE  `round` = $round) votefiles LEFT JOIN (SELECT * FROM `".$config['dbprefix']."fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` = `votefotos`.`name` ORDER BY `points` DESC";
	}
	else // init
	{
		$round = 0;
		$sql = "SELECT `votefiles`.`name`, `votefiles`.`jury`, `votefotos`.`user`, `votefotos`.`date`, `votefotos`.`time`, `votefotos`.`size`, `votefotos`.`width`, `votefotos`.`height`, `votefotos`.`url`, `votefotos`.`thumburl`, `votefotos`.`descriptionurl`, `votefotos`.`license` FROM (SELECT name, user AS jury FROM `".$config['dbprefix']."votes` WHERE `vote` =2) votefiles LEFT JOIN (SELECT * FROM  `".$config['dbprefix']."fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` = `votefotos`.`name` ORDER BY `votefiles`.`name` ASC";
	}
	
	// loop photos
	$res = $db->query($sql);
	$numberoffiles = $res->num_rows;

	$round++;
	$ii = 0;
	$uploader .= "<form action=\"meeting.php?r=$round\" method=\"post\">";
	$uploader .= "<table border=0 cellpadding=0px width=1030px style=\"text-align: left;border-spacing: 2px 15px;\">";
	$last = "";
	while($row = $res->fetch_array(MYSQLI_ASSOC))
	{
		// if more members of jury have nominated same picture
		if(($round == 1)&&($last == $row['name']))
		{
			$uploader .= "; " . $row['jury'];
		}
		else
		{
			if($last != "")
			{
				$uploader .= "</td></tr>";
			}
			$ii++;
			$uploader .= "<tr ".$bg[($ii)%2].">";
		
			$uploader .= "<td height=600px style=\"text-align: center; background-image: url(cw.gif); background-repeat: no-repeat; background-position: center; \">";
			$uploader .= "<a href=\"".$row['url']."\" target=\"_blank\"><img src=\"";

			if($row['width'] <= 600)
			{
				$uploader .= $row['url'];
			}
			else
			{
				$uploader .= str_replace("/100px-","/".$_SESSION['width']."px-",$row['thumburl']);
			}
			if($row['width'] < $row['height'])
			{
				$uploader .= "\" height=600px";
			}
			else
			{
				$uploader .= "\" width=600px";
			}
		
			$uploader .= "></a></td>";

			$uploader .= "<td style=\"vertical-align: top;\" width=400px>";

			$uploader1 =  "&nbsp;".$row['user'] . " <br>&nbsp;".$row['date']." ".$row['time']."<br>&nbsp;".round($row['size']/1024/1024,2)." MB, ".$row['width']."x".$row['height']." px<br>&nbsp;".$row['license']."<br>&nbsp;<a href=\"".$row['descriptionurl']."\" target=\"_blank\" class=\"co\">commons</a>";
		
			$uploader2 = $text['points'].": ";
			$uploader2 .= "<input name=\"value$ii\">";
			$uploader2 .= "<input name=\"name$ii\" type=\"hidden\" value=\"".urlencode($row['name'])."\">";

			$uploader .= $uploader2 . "<br> <br> <br>" . $uploader1 . "<br> <br>";
			
			if($round == 1)
			{
				$uploader .= " <br>" . $row['jury'];
			}
			else
			{
				$uploader .= $row['points'];
			}
		
			$last = $row['name'];
		}
	}
	$uploader .= "</td></tr>";
	$uploader .= "</table>";
	$uploader .= "<input name=\"files\" type=\"hidden\" value=\"$ii\">";
	$uploader .= "<input type=\"submit\" value=\"".$text['compute']."\"></form>";
	
	return $uploader;
}

?>
