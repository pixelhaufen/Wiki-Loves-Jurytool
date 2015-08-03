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

function menue3()
{
	global $config;
	global $text;
	
	$settings = '<p>'.$text["menu"].' ';
	if((!isset($_SESSION['h']))||($_SESSION['h']!=1))
	{
		$settings .= '<a href="index.php?h=1">'.$text["hide"].'</a>';
	}
	else
	{
		$settings .= '<a href="index.php?h=0">'.$text["show"].'</a>';
	}
	$settings .= ' - '.$text["max"].': <a href="index.php?width=400">400px</a>, <a href="index.php?width=500">500px</a>, <a href="index.php?width=600">600px</a>, <a href="index.php?width=700">700px</a>';
	$settings .= ' - '.$text["info"].' ';
	if($_SESSION['info']!=0)
	{
		$settings .= '<a href="index.php?info=0">'.$text["hide"].'</a>';
	}
	else
	{
		$settings .= '<a href="index.php?info=1">'.$text["show"].'</a>';
	}
	$settings .= '</p>';
	
	return $settings;
}

function menue2()
{
	global $config;
	global $text;
	
	$menue = '
<p class="right">
	<b>'.$text["hi"].' '.$_SESSION['us'].'!</b><br> <br>
	<a href="./help.php" class="st"> '.$text["help"].'</a><br>
	<br>
	<a href="./index.php?p=logout" class="st"> '.$text["logout"].'</a><br>
</p>
</td>';

	return $menue;
}

function menue()
{
	global $config;
	global $text;
	
	$menue = '
<p class="menue">
	<img src="'.$config["icon"].'" width=14> <a href="./index.php" class="st">'.$text["unseen"].'</a><br>
	&nbsp;<a href="./index.php?p=0" class="st">'.$text["ignored"].'</a> 
		&nbsp;<a href="./index.php?p=0&b=1" class="st">'.$text["big_pictures"].'</a><br>
	
	<a href="./index.php?p=1" class="st"> <img src="../theme/img/sc.png" width=14><img src="../theme/img/sb.png" width=14><img src="../theme/img/sb.png" width=14><img src="../theme/img/sb.png" width=14><img src="../theme/img/sb.png" width=14></a> 
		<a href="./index.php?p=1&b=1" class="st">'.$text["big_pictures"].'</a><br>
	
	<a href="./index.php?p=2" class="st"> <img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sb.png" width=14><img src="../theme/img/sb.png" width=14><img src="../theme/img/sb.png" width=14></a> 
		<a href="./index.php?p=2&b=1" class="st">'.$text["big_pictures"].'</a><br>
	
	<a href="./index.php?p=3" class="st"> <img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sb.png" width=14><img src="../theme/img/sb.png" width=14></a> 
		<a href="./index.php?p=3&b=1" class="st">'.$text["big_pictures"].'</a><br>
	
	<a href="./index.php?p=4" class="st"> <img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sb.png" width=14></a> 
		<a href="./index.php?p=4&b=1" class="st">'.$text["big_pictures"].'</a><br>
	
	<a href="./index.php?p=5" class="st"> <img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14><img src="../theme/img/sc.png" width=14></a> 
	<a href="./index.php?p=5&b=1" class="st">'.$text["big_pictures"].'</a><br>';
	
	return $menue;
}

?>