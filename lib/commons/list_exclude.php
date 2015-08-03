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
function getdata_exclude($url, $db) // get list of images from commons 
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
		}

		// loop to get fotos
		foreach($xml->query->categorymembers->cm as $fotos)
		{
			foreach($fotos->attributes() as $element => $value)
			{
				if($element == "title")
				{
					$value = $db->real_escape_string($value);
					$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET exclude='1' WHERE `name` = '$value'";
					$db->query($sql);
				} // end if title
			} // end attributes loop
		} // end fotos
	} // connection ok
	return $continue;
}

// get list of images from commons
function commons_get_list_exclude($db)
{
	global $config;
	foreach($config['catremove'] as $element => $category)
	{
		// set exclude to 2 in fotos so we know later which images are no longer in this category on commons
		$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET `exclude`='1' WHERE `exclude`='2'";
		$db->query($sql);
	
		// get list of images from commons 
		$url='http://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&format=xml&cmtitle=Category:' . $category . '&cmprop=title&continue';
		// read data and save to db
		$continue = getdata_exclude($url, $db);
		while($continue != "")
		{
			if($continue == "connection error")
			{
				// log error
				if($config['log']!="NO")
				{
					append_file("log/cron.txt","\n\t".date(DATE_RFC822)."connection error\tcommons_get_list_exclude()");
				}
				return "STOP";
			}
			// read data and save to db
			$continue = getdata_exclude($url."=-||&cmcontinue=".$continue, $db);
		} // end api loop
		
		// set exclude to 0 in fotos for images that are no longer in this category on commons
		$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET `exclude`='0' WHERE `exclude`='2'";
		$db->query($sql);
	}
}

?>