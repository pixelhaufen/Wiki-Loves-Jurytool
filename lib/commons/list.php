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

function fotos_commons($db,$value,$category)
{
	global $config;
	
	$sql = "SELECT `name` FROM `" . $config['dbprefix'] . "fotos_commons` WHERE `name` = '$value' AND `commons` = '$category'";
	$res = $db->query($sql);
	if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
	{
		append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tfotos_commons()");
	}
	
	$num_commons = $res->num_rows;
	$res->close();
	if ($num_commons < 1)
	{
		$sql = "INSERT INTO " . $config['dbprefix'] . "fotos_commons(name, commons, online) VALUES ('$value','$category',2)";
		$db->query($sql);
		if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
		{
			append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tfotos_commons()");
		}
	}
	else if($num_commons == 1)
	{
		$sql = "UPDATE `" . $config['dbprefix'] . "fotos_commons` SET online='2' WHERE `name` = '$value' AND `commons` = '$category'";
		$db->query($sql);
		if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
		{
			append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tfotos_commons()");
		}
	}
	else
	{
		$sql = "DELETE FROM `" . $config['dbprefix'] . "fotos_commons` WHERE `name` = '$value' AND `commons` = '$category'";
		$db->query($sql);
		if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
		{
			append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tfotos_commons()");
		}
		
		$sql = "INSERT INTO " . $config['dbprefix'] . "fotos_commons(online, name, commons) VALUES ('2','$value','$category')"; 
		$db->query($sql);
		if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
		{
			append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tfotos_commons()");
		}
	}
}


// get data from commons and save in db
function getdata($url, $db, $category) 
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
					$sql = "SELECT `name` FROM `" . $config['dbprefix'] . "fotos` WHERE `name` = '$value'";
					$res = $db->query($sql);
					if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
					{
						append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tgetdata()");
					}
					
					$num_names = $res->num_rows;
					$res->close();
					if ($num_names < 1)
					{
						// e.g. a Category is not a foto... save what we know
						$isfile = strpos($value, 'File:');
						if(($isfile !== false)&&($isfile==0))
						{
							// note that online is still 0 - we are not ready yet 
							$sql = "INSERT INTO " . $config['dbprefix'] . "fotos(name, user, date, time, url, descriptionurl) VALUES ('$value','-','-','-','-','-')"; 
							$db->query($sql);
							if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
							{
								append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tgetdata()");
							}
							
							fotos_commons($db,$value,$category);
						}
					}
					else if($num_names == 1)
					{
						// e.g. a Category is not a foto...
						$isfile = strpos($value, 'File:');
						if(($isfile !== false)&&($isfile==0))
						{
							// mark as still here
							$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET online='2' WHERE `name` = '$value'";
							$db->query($sql);
							if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
							{
								append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tgetdata()");
							}

							fotos_commons($db,$value,$category);
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
						if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
						{
							append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tgetdata()");
						}
						
						// note that online is still 0 - we are not ready yet 
						$sql = "INSERT INTO " . $config['dbprefix'] . "fotos(name, user, date, time, size, width, height, pixel, url, descriptionurl, online) VALUES ('$value','-','-','-',0,0,0,0,'-','-',0)";
						$db->query($sql);
						if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
						{
							append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tgetdata()");
						}
						
						fotos_commons($db,$value,$category);
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
	
	// set online to 2 in fotos so we know later which images are no longer in this category on commons
	$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET `online`='1' WHERE `online`='2'";
	$db->query($sql);
	if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
	{
		append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tcommons_get_list()");
	}
	
	foreach($config['catadd'] as $element => $category)
	{
		$sql = "UPDATE `" . $config['dbprefix'] . "fotos_commons` SET online='1' WHERE `commons` = '$category' AND `online`='2'";
		$db->query($sql);
		if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
		{
			append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tcommons_get_list()");
		}
	
		// commons api query
		$url='http://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&format=xml&cmtitle=Category:' . $category . '&cmprop=title&continue';
		// read data and save to db
		$continue = getdata($url, $db, $category);
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
			$continue = getdata($url."=-||&cmcontinue=".$continue, $db, $category);
		} // end api loop

		$sql = "UPDATE `" . $config['dbprefix'] . "fotos_commons` SET online='0' WHERE `commons` = '$category' AND `online`='1'";
		$db->query($sql);
		if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
		{
			append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tcommons_get_list()");
		}
	}
	
	// set online to 0 in fotos for images that are no longer in this category on commons
	$sql = "UPDATE `" . $config['dbprefix'] . "fotos` SET `online`='0' WHERE `online`='1'";
	$db->query($sql);
	if(($config['log']=="PARANOID") || ($config['log']=="DEBUG"))
	{
		append_file("log/cron.txt","\n" . date(DATE_RFC822) . "\t" . $sql . "\tcommons_get_list()");
	}

}

?>