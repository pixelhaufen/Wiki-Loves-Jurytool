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

// get info for new files
function commons_get_new_info($db)
{
	global $config;
	
	// get information about files from commons
	$sql = "SELECT `name` FROM `" . $config['dbprefix'] . "fotos` WHERE `user` = '-'";
	$res = $db->query($sql);

	if ($res)
	{
		// loop files
		while($row = $res->fetch_array(MYSQLI_ASSOC))
		{
			// commons api query
			$url='http://commons.wikimedia.org/w/api.php?action=query&titles='.urlencode($row['name']).'&prop=imageinfo&iiprop=timestamp|user|url|size&format=xml&iilimit=10';
			$file = $db->real_escape_string($row['name']);
			// read data
			$str = @file_get_contents($url);
	
			if($str === FALSE)
			{
				if($config['log']!="NO")
				{
					append_file("log/cron.txt","\n".date(DATE_RFC822)."\tfile:".$url."\tcommons_get_new_info()");
				}
			}
			else
			{
				$xml = new SimpleXMLElement($str);

				$user = $url = $descriptionurl = "-";
				$size = $width = $height = $date = 0;
				foreach($xml->query->pages->page->imageinfo->ii as $ii)
				{
					foreach($ii->attributes() as $element => $value)
					{
						switch($element)
						{
							case "timestamp":
								if($date == 0)
								{
									$t = explode("T", $value);
									$date = $t[0];
									$time = str_replace("Z", "", $t[1]);
								}
								break;
							case "user":
								{
									$user = $db->real_escape_string($value);
								}
								break;
							case "size":
								{
									$size = $value;
								}
								break;
							case "width":
								{
									$width = $value;
								}
								break;
							case "height":
								{
									$height = $value;
								}
								break;
							case "url":
								if($url == "-")
								{
									$url = $db->real_escape_string($value);
								}
								break;
							case "descriptionurl":
								if($descriptionurl == "-")
								{
									$descriptionurl = $db->real_escape_string($value);
								}
								$descriptionurl = $db->real_escape_string($value);
								break;
						} // switch
					} // foreach attribute
				} // foreach ii
				$pixel = $width * $height;

				if($user != "-")
				{
					$old_date = explode("-",$date);
					$old_time = explode(":",$time);
					$new_time = strtotime($config['time'],
						mktime($old_time[0],$old_time[1],$old_time[2],$old_date[1],$old_date[2],$old_date[0])
					);

					$date = date("Y-m-d", $new_time);
					$time = date("H:i:s", $new_time);
					
					$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET user='$user' , date='$date' , time='$time' , size='$size' , width='$width' , height='$height' , pixel='$pixel' , url='$url' , descriptionurl='$descriptionurl', online='2' WHERE `name` = '$file'";
					$db->query($sql);
				}
			} // ($str !== FALSE)
		} // while($row = mysql_fetch_array($res))
	} // ($loop != 0)
	$res->close();
}

function commons_get_licence($db)
{
	global $config;
	
	// get information about files from commons
	$sql = "SELECT `name` FROM `" . $config['dbprefix'] . "fotos` WHERE  `license` LIKE  '' AND (`online`=1 OR `online`=2)";
	$res = $db->query($sql);

	if ($res)
	{
		while($row = $res->fetch_array(MYSQLI_ASSOC))
		{
			// commons api query
			$url='http://commons.wikimedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=xml&titles='.urlencode($row['name']);
			$file = $db->real_escape_string($row['name']);
			// read data
			$str = @file_get_contents($url);
			
			if($str === FALSE)
			{
				if($config['log']!="NO")
				{
					append_file("log/cron.txt","\n".date(DATE_RFC822)."\tfile:".$url."\tcommons_get_licence()");
				}
			}
			else
			{
				$xml = new SimpleXMLElement($str);
		
				if(isset($xml->query->pages->page->revisions->rev))
				{
					foreach($config['license'] as $license)
					{
						if (stripos($xml->query->pages->page->revisions->rev[0], $license) !== false)
						{
							$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET license='" . $license . "' WHERE `name` = '" . $file . "'";
							$db->query($sql);
						}
					} // foreach
				} // if(!isset($xml->query->pages->page->revisions->rev))
			} // ($str !== FALSE)
		} // row
	}
}

?>