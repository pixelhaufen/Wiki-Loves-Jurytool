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
	$uploader = "";
	
	// way to goal
	if($programm == "selected")
	{
		$uploader .= "<h1>" . $images . " " . $text['photos'] . "</h1>";
		if($images < $config['goal'])
		{
			$uploader .= "<p>" . $text['missing'] . " " . ($config['goal'] - $images) . " " . $text['photos'] . "<br> </p>";
		}
		else if($images > $config['goal'])
		{
			$uploader .= "<p>" . $text['remove'] . " " . ($images - $config['goal']) . " " . $text['photos'] . "<br> </p>";
		}
		else
		{
			$uploader .= "<p>" . $text['perfekt'] . "<br> </p>";
		}
	}

	$i = $ii = 0;
	$uploader .= "<table border=0 cellpadding=0px width=1000px style=\"text-align: left;border-spacing: 2px 15px;\">";
	while($row = $res->fetch_array(MYSQLI_ASSOC))
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
			$uploader .= "<td width=100px style=\"text-align: center; background-image: url(../theme/img/cws.gif); background-repeat: no-repeat; background-position: center; \">";
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
		$uploader1 =  "&nbsp;".$row['user'] . " <br>&nbsp;".$row['date']." ".$row['time']."<br>&nbsp;".round($row['size']/1024/1024,2)." MB, ".$row['width']."x".$row['height']." px<br>&nbsp;".$row['license']."<br>&nbsp;<a href=\"".$row['descriptionurl']."\" target=\"_blank\" class=\"co\">commons</a></td>";
		
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
		
		// both
		if($programm != "selected")
		{
			$uploader2 = "&nbsp;<a href=\"".$action."2".$action2."\" class=\"sta\">".$text['select']."</a><br><br>";
		}
		else
		{
			$uploader2 = "&nbsp;<img src=\"../theme/img/sc.png\" width=14><br><br>";
		}
				
		if($programm != "gemerkt")
		{
			$uploader2 .= "&nbsp;<a href=\"".$action."1".$action2."\" class=\"stm\">".$text['bookmark']."</a><br><br>";
		}
		else
		{
			$uploader2 .= "&nbsp;<img src=\"../theme/img/cc.png\" width=14><br><br>";
		}
				
		if($programm != "notselected")
		{
			$uploader2 .= "&nbsp;<a href=\"".$action."10".$action2."\" class=\"ste\">".$text['exclude']."</a>";
		}
		else
		{
			$uploader2 .= "&nbsp;<img src=\"../theme/img/dc.png\" width=14>";
		}
				
		if (($images != 1) && (!isset($_GET["b"])))
		{
			$uploader .= $uploader1 . "<td>". $uploader2 . "</td>";
		}
		else
		{
			$uploader .= $uploader2 . "<br> <br> ";
			if($programm == "")
			{
				$uploader .= "&nbsp;<a href=\"index.php?n=". ($starter-1) . "\"><img src=\"../theme/img/le.png\" width=14></a>  ".$text['browse']."  <a href=\"index.php?n=". ($starter+1). "\"><img src=\"../theme/img/ri.png\" width=14></a>";
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
		$uploader = "<h1>".$text['empty']."</h1>";
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
		case "gemerkt":
			$sql = "SELECT * FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "votes` WHERE `user` LIKE '$user' AND `vote` = 1) votefiles LEFT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` LIKE `votefotos`.`name`";
		break;
		case "selected":
			$sql = "SELECT * FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "votes` WHERE `user` LIKE '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` LIKE `votefotos`.`name` WHERE `votefiles`.`vote` = 2";
		break;
		case "notselected":
			$sql = "SELECT * FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "votes` WHERE `user` LIKE '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` LIKE `votefotos`.`name` WHERE `votefiles`.`vote` = 10";
		break;
		case "ungelesen": 
			$sql = "SELECT * FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "votes` WHERE `user` LIKE '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` LIKE `votefotos`.`name` WHERE `votefiles`.`vote` IS NULL";
		break;
		default:
			if(isset($_GET["n"]))
			{
				$starter = $_GET["n"];
				$sql = "SELECT count(*) AS max FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "votes` WHERE `user` LIKE '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` LIKE `votefotos`.`name` WHERE `votefiles`.`vote` IS NULL";
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
			$sql = "SELECT * FROM (SELECT `name`, `vote`  FROM `" . $config['dbprefix'] . "votes` WHERE `user` LIKE '$user') votefiles RIGHT JOIN (SELECT * FROM  `" . $config['dbprefix'] . "fotos` WHERE (`jury` = 1)) votefotos ON `votefiles`.`name` LIKE `votefotos`.`name` WHERE `votefiles`.`vote` IS NULL LIMIT $starter, 1";
			$programm = "";
		break;
	}
	
	return display_foto($db, $sql, $programm, $starter);
}

?>