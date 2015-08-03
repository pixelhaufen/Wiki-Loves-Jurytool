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
	
function deadline()
{
	global $config;
	global $text;
	
	$end = mktime(
		$config["dlhour"],
		$config["dlminute"],
		$config["dlsecond"],
		$config["dlmonth"],
		$config["dlday"],
		$config["dlyear"]
	);

	$now = microtime(true); 

	$diff = $end - $now; 
	if($diff > 0)
	{
		$milli = explode(".", round($diff, 2)); 
		$millisec = round($milli[1]);

		$day = floor($diff / (24*3600)); 
		$diff = $diff % (24*3600); 
		$houre = floor($diff / (60*60)); 
		$diff = $diff % (60*60); 
		$min = floor($diff / 60); 
		$sec = $diff % 60; 

		$stop = $text["countdown"].": ";
		$stop .= $day." ".$text["days"]." "; 
		$stop .=  $houre." ".$text["hours"]." "; 
		$stop .=  $min." ".$text["minutes"]." "; 
		$stop .=  $sec." ".$text["sec"]." "; 
		$stop .=  $millisec." ".$text["msec"];
	}
	else
	{
		$stop = "0";
	}
	return $stop;
}

?>