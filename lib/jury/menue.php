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
	
	$menue = '<p style="text-align: left; padding-left: 40px;">
		<img src="'.$config["icon"].'" width=14> '.$text["unseen"].' 
			<a href="./index.php" class="st">'.$text["single"].'</a> / 
			<a href="./index.php?p=ungelesen" class="st">'.$text["list"].'</a> 
			<a href="./index.php?p=ungelesen&b=1" class="st">'.$text["big_pictures"].'</a><br>
			
		<img src="../theme/img/cc.png" width=14> 
			<a href="./index.php?p=gemerkt" class="st"> '.$text["list"].' '.$text["bookmarked"].'</a> 
			<a href="./index.php?p=gemerkt&b=1" class="st">'.$text["big_pictures"].'</a><br>
			
		<img src="../theme/img/sc.png" width=14> 
			<a href="./index.php?p=selected" class="st"> '.$text["list"].' '.$text["selected"].'</a> 
			<a href="./index.php?p=selected&b=1" class="st">'.$text["big_pictures"].'</a><br>
			
		<img src="../theme/img/dc.png" width=14> 
			<a href="./index.php?p=notselected" class="st"> '.$text["list"].' '.$text["not_selected"].'</a> 
			<a href="./index.php?p=notselected&b=1" class="st">'.$text["big_pictures"].'</a>
	</p>';
	
	return $menue;
}

?>