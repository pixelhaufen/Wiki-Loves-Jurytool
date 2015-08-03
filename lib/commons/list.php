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

// get data from commons and save in db
function getdata($url, $db) 
{
	global $config;
	
	// read data
	$str = @file_get_contents($url);

	if($str === FALSE)
	{
		return "connection error";
	}
	else
	{
		$str = str_replace("query-continue","continue",$str);
		$xml = new SimpleXMLElement($str);

		// get continue value
		$continue = "";
		if(isset($xml->continue))
		{
			foreach($xml->continue->attributes() as $element => $value)
			{
				if($element == "cmcontinue")
				{
	    			$continue = $value;
				}
			}
		} // end continue value

		// loop to get fotos
		foreach($xml->query->categorymembers->cm as $fotos)
		{
			foreach($fotos->attributes() as $element => $value)
			{
				if($element == "title")
				{
					// is it a new file?
					$value = $db->real_escape_string($value);
					$sql = "SELECT `name` FROM `" . $config['dbprefix'] . "fotos` WHERE `name` LIKE '$value'";
					$res = $db->query($sql);
					$num_names = $res->num_rows;
					$res->close();
					if ($num_names < 1)
					{
						$cattest = strpos($value, 'Category'); // a Category is not a foto...
						if($cattest === false) // save what we know
						{
							// note that online is still 0 - we are not ready yet 
							$sql = "INSERT INTO " . $config['dbprefix'] . "fotos(name, user, date, time, url, descriptionurl) VALUES ('$value','-','-','-','-','-')"; 
							$db->query($sql);
						}
					}
					else if($num_names == 1)
					{
						$cattest = strpos($value, 'Category'); // a Category is not a foto...
						if($cattest === false)
						{
							// mark as still here
							$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET online='2' WHERE `name` = '$value'";
							$db->query($sql);
						}
					}
					else // an object can't be here twice...
					{
						// log error
						if($config['log']!="NO")
						{
							append_file("log/cron.txt","\n".date(DATE_RFC822)."\tduplicate: $value\tcommons_get_list() -> getdata()");
						}
						
						// try to fix that:
						$sql = "DELETE FROM `" . $config['dbprefix'] . "fotos` WHERE `name` = '$value'";
						$db->query($sql);
						// note that online is still 0 - we are not ready yet 
						$sql = "INSERT INTO " . $config['dbprefix'] . "fotos(name, user, date, time, size, width, height, pixel, url, descriptionurl, online) VALUES ('$value','-','-','-',0,0,0,0,'-','-',0)";
						$db->query($sql);
						
					}
				} // end if title
			} // end attributes loop
		} // end fotos
	} // connection ok
	
	return $continue;
}

// get list of images from commons
function commons_get_list($db)
{
	global $config;
	
	foreach($config['catadd'] as $element => $category)
	{
		// set online to 2 in fotos so we know later which images are no longer in this category on commons
		$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET `online`='1' WHERE `online`='2'";
		$db->query($sql);
	
		// commons api query
		$url='http://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&format=xml&cmtitle=Category:' . $category . '&cmprop=title&continue';
		// read data and save to db
		$continue = getdata($url, $db);
		while($continue != "") // loop while api gives data
		{
			if($continue == "connection error")
			{
				// log error
				if($config['log']!="NO")
				{
					append_file("log/cron.txt","\n".date(DATE_RFC822)."\tconnection error\tcommons_get_list()");
				}
				return "STOP";
			}
			// read data and save to db
			$continue = getdata($url."=-||&cmcontinue=".$continue, $db);
		} // end api loop

		// set online to 0 in fotos for images that are no longer in this category on commons
		$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET `online`='0' WHERE `online`='1'";
		$db->query($sql);
	}
}

?>