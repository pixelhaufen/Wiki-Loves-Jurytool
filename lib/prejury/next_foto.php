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

function display_foto($db, $sql, $programm, $starter)
{
	global $config;
	global $text;
	
	$bg[0] = "style=\"background-color:#FAFAFA;\"";
	$bg[1] = "style=\"background-color:#EEE;\"";
	$bg[2] = "style=\"background-color:#DDD;\"";
	
	$res = $db->query($sql);

	$images = $res->num_rows;
	$i = $ii = 0;
	$uploader = "<table border=0 cellpadding=0px width=1000px style=\"text-align: left;border-spacing: 2px 15px;\">";
	while($row = $res->fetch_array(MYSQLI_ASSOC)) // loop images
	{
		$i++;

		// background color
		if(($i%2 == 1)&&($images != 1))
		{
			$ii++;
			$uploader .= "<tr ".$bg[($ii)%2].">";
		}
		else if (isset($_GET["b"]))
		{
			$ii++;
			$uploader .= "<tr ".$bg[($ii)%2].">";
		}
		else
		{
			$uploader .= "<td width=10px style=\"background-color:#fff;\"> </td>";
		}

		// small images list
		if (($images != 1) && (!isset($_GET["b"])))
		{
			$uploader .= "<td style=\"text-align: center; background-image: url(../theme/img/cws.gif); background-repeat: no-repeat; background-position: center; height: 100px; width:100px;\">";
			$uploader .= "<a href=\"".$row['url']."\" target=\"_blank\"><img src=\"".str_replace("/commons/","/commons/thumb/",$row['url'])."/";
			if(stringEndsWith($row['name'],".tif") || stringEndsWith($row['name'],".tiff")) // ends with .tif or tiff
			{
				$uploader .= "lossy-page1-100px-".urlencode(str_replace("File:","",str_replace(' ', '_', $row['name']))) .".jpg\"";
			}
			else
			{
				$uploader .= "100px-".urlencode(str_replace("File:","",str_replace(' ', '_', $row['name']))) ."\"";
			}
			if($row['width'] > $row['height'])
			{
				$uploader .= "width=\"100\"";
			}
			else
			{
				$uploader .= "height=\"100\"";
			}
			$uploader .= "></a></td><td>";
		}
		else // big images
		{
			$uploader .= "<td width=".$_SESSION['width']."px height=".$_SESSION['width']."px style=\"text-align: center; background-image: url(../theme/img/cw.gif); background-repeat: no-repeat; background-position: center; \">";
			$uploader .= "<a href=\"".$row['url']."\" target=\"_blank\"><img src=\"";
			if($row['width'] <= $_SESSION['width'])
			{
				$uploader .= $row['url'];
			}
			else
			{
				$uploader .= str_replace("/commons/","/commons/thumb/",$row['url'])."/";
				if(stringEndsWith($row['name'],".tif") || stringEndsWith($row['name'],".tiff")) // ends with .tif or tiff
				{
					$uploader .= "lossy-page1-".$_SESSION['width']."px-".urlencode(str_replace("File:","",str_replace(' ', '_', $row['name']))) .".jpg";
				}
				else
				{
					$uploader .= $_SESSION['width']."px-".urlencode(str_replace("File:","",str_replace(' ', '_', $row['name'])));
				}
				if($row['width'] > $row['height'])
				{
					$uploader .= "\" width=\"".$_SESSION['width'];
				}
				else
				{
					$uploader .= "\" height=\"".$_SESSION['width'];
				}
			}
			$uploader .= "\"></a></td>";
			
			if (!isset($_GET["b"]))
			{
				$uploader .= "<td width=30px style=\"background-color:#fff;\"> </td>";
			}
			
			$uploader .= "<td style=\"vertical-align: top;\">";
		}
		
		// info about image
		$uploader1 =  "&nbsp;". sha1($row['user']) . " <br>&nbsp;".$row['date']." ".$row['time']."<br>&nbsp;".round($row['size']/1024/1024,2)." MB, ".$row['width']."x".$row['height']." px<br>&nbsp;".$row['license']."<br>&nbsp;<a href=\"".$row['descriptionurl']."\" target=\"_blank\" class=\"co\">commons</a></td>";
		
		// voting url
		if($programm != "")
		{
			$action = "./index.php?p=".$programm."&f=".urlencode($row['name'])."&a=";
		}
		else
		{
			$action = "./index.php?f=".urlencode($row['name'])."&a=";
		}
		
		// voting url big images
		if (isset($_GET["b"]))
		{
			$action2 = "&b=1";
		}
		else
		{
			$action2 = "";
		}

		$uploader2 = "&nbsp;".$text["vote"]."<br><br>&nbsp;";
		
		if($programm == "")
		{
			$rank_art = 1;
		}
		else
		{
			$rank_art = $programm;
		}
		$nr_anz_star = 1;
		
		if((isset($starter))&&($starter != ""))
		{
			$addstarter = "&n=".$starter;
		}
		else
		{
			$addstarter = "";
		}
			
		for($i2=0; $i2<$rank_art; $i2++) // make stars link yellow
        {
			if (($images != 1) && (!isset($_GET["b"]))) // small stars
			{
				$uploader2 .= "<a href='".$action.$nr_anz_star.$addstarter.$action2."'><span class='star' id='".$i.$nr_anz_star."' onmouseover=\"change_star('".$nr_anz_star."', '".$i."');\" onmouseout=\"change_star('".$rank_art."', '".$i."');\"><img src='../theme/img/sc.png' style='vertical-align: middle; width:14px;'></span></a>";
			}
			else // big stars
			{
				$uploader2 .= "<a href='".$action.$nr_anz_star.$addstarter.$action2."'><span class='star' id='".$i.$nr_anz_star."' onmouseover=\"change_star('".$nr_anz_star."', '".$i."');\" onmouseout=\"change_star('".$rank_art."', '".$i."');\"><img src='../theme/img/sc.png' style='vertical-align: middle; width:32px;'></span></a>";
			}
            $nr_anz_star++;
        }
		
        for($i3=0; $i3<(5-$rank_art); $i3++) // make stars link gray
        {
			if (($images != 1) && (!isset($_GET["b"])))
			{
				$uploader2 .= "<a href='".$action.$nr_anz_star.$addstarter.$action2."'><span class='star_gray' id='".$i.$nr_anz_star."' onmouseover=\"change_star('".$nr_anz_star."', '".$i."');\" onmouseout=\"change_star('".$rank_art."', '".$i."');\"><img src='../theme/img/sc.png' style='vertical-align: middle; width:14px;'></span></a>";
			}
			else
			{
				$uploader2 .= "<a href='".$action.$nr_anz_star.$addstarter.$action2."'><span class='star_gray' id='".$i.$nr_anz_star."' onmouseover=\"change_star('".$nr_anz_star."', '".$i."');\" onmouseout=\"change_star('".$rank_art."', '".$i."');\"><img src='../theme/img/sc.png' style='vertical-align: middle; width:32px;'></span></a>";
			}
            $nr_anz_star++;
        }
		
		$uploader2 .= "<br>";

		// ignore link
		$uploader3 = "<br>&nbsp;<a href='".$action."0".$addstarter.$action2."'>".$text["ignore"]."</a>";

		// build html
		if (($images != 1) && (!isset($_GET["b"])))
		{
			$uploader .= $uploader1 . "<td>". $uploader2 . $uploader3 . "</td>";
		}
		else
		{
			if($programm == "") // add browse link
			{
				$uploader .= "<br> <br> <br> <br> <br> <br> " . $uploader2 . "<br>" . $uploader3 . "<br> <br> ".
					"&nbsp;<a href=\"index.php?n=". ($starter-1) . "\"><img src=\"../theme/img/le.png\" width=14></a>  ".$text["browse"]."  <a href=\"index.php?n=". ($starter+1). "\"><img src=\"../theme/img/ri.png\" width=14></a>";
			}
			else
			{
				$uploader .= $uploader2 . "<br>" . $uploader3 . " <br> ";
			}
			$uploader .= " <br> <br> <br>" . $uploader1;
		}

		if(($i%2 != 1) || (isset($_GET["b"])))
		{
			$uploader .= "</tr>";
		}
	} // end loop images
	$res->close();
	
	$uploader .= "</table>";
	
	if($i == 0)
	{
		if($programm == "")
		{
			$uploader = "<h1>".$text["THX_later"]."</h1>";
		}
		else
		{
			$uploader = "<h1>".$text["empty"]."</h1>";
		}
	}
		
	return $uploader;
}

function next_foto($db, $programm)
{
	global $config;
	global $text;
	$user = $db->real_escape_string($_SESSION['us']);
	$starter = 0;
	
	switch($programm)
	{
		// ignored
		case "0":
			$sql = "SELECT * FROM (SELECT `name`, `vote`, `time` AS `sorttime` FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0)) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` = 0 ORDER BY  `votefiles`.`sorttime` DESC";
		break;
		
		// 1 star
		case "1":
			$sql = "SELECT * FROM (SELECT `name`, `vote`, `time` AS `sorttime` FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0)) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` = 1 ORDER BY  `votefiles`.`sorttime` DESC";
		break;
		
		// 2 stars
		case "2":
			$sql = "SELECT * FROM (SELECT `name`, `vote`, `time` AS `sorttime` FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0)) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` = 2 ORDER BY  `votefiles`.`sorttime` DESC";
		break;
		
		// 3 stars
		case "3":
			$sql = "SELECT * FROM (SELECT `name`, `vote`, `time` AS `sorttime` FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0)) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` = 3 ORDER BY  `votefiles`.`sorttime` DESC";
		break;
		
		// 4 stars
		case "4":
			$sql = "SELECT * FROM (SELECT `name`, `vote`, `time` AS `sorttime` FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0)) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` = 4 ORDER BY  `votefiles`.`sorttime` DESC";
		break;
		
		// 5 stars
		case "5":
			$sql = "SELECT * FROM (SELECT `name`, `vote`, `time` AS `sorttime` FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0)) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` = 5 ORDER BY  `votefiles`.`sorttime` DESC";
		break;
		
		// unseen
		default:
			// offset in three-torus universe
			if(isset($_GET["n"]))
			{
				$starter = $_GET["n"];
				$sql = "SELECT count(*) AS max FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0) AND `user` != '$user' ORDER BY  `" . $config['dbprefix'] . "fotos`.`vote` ASC) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` IS NULL";
				$res = $db->query($sql);
				$row = $res->fetch_array(MYSQLI_ASSOC);

				if($starter >= $row['max']) 
				{
					$starter = 0;
				}
				if($starter < 0)
				{
					$starter = $row['max'] + $starter;
				}
			}
			else
			{
				$starter = 0;
			}
			// unseen
			
			// use this and delete the line after for random order (code from removed feature)
			// $sql = "SELECT * FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0) AND `user` != '$user' ORDER BY  `" . $config['dbprefix'] . "fotos`.`vote` ASC, `" . $config['dbprefix'] . "fotos`.`url` ASC) votefotos ON `votefiles`.`name` LIKE `votefotos`.`name` WHERE `votefiles`.`vote` IS NULL LIMIT $starter, 1";
			
			$sql = "SELECT * FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "v_votes` WHERE `user` = '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`online` = 1 OR `online` = 2) AND (`exclude` = 0) AND `user` != '$user' ORDER BY  `" . $config['dbprefix'] . "fotos`.`vote` ASC) votefotos ON `votefiles`.`name` = `votefotos`.`name` WHERE `votefiles`.`vote` IS NULL LIMIT $starter, 1";
			$programm = "";
		break;
	}
	
	return display_foto($db, $sql, $programm, $starter);
}

?>