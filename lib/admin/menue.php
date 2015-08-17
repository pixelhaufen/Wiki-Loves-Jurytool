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

function menue2()
{
	global $text;
	
	$menue = '
<p class="right">
	<b>'.$text["hi"].' '.$_SESSION['us'].'!</b><br>
	<br>
	<a href="./help.php" class="st"> '.$text["help"].'</a><br>
	<br>
	<a href="./index.php?p=logout" class="st"> '.$text["logout"].'</a><br>
</p>
</td>';
	
	return $menue;
}

function menue()
{
	global $text;
	
	$menue = '<p class="menue">';
	$menue .= '<a href="index.php?pj=s" class="st">'.$text["prejury"].' '.$text["user_created"].'</a><br>';
	$menue .= '<a href="index.php?j=s" class="st">'.$text["jury"].' '.$text["user_created"].'</a><br>';
	$menue .= '<a href="index.php?p2j=s" class="st">'.$text["created_jury"].'</a><br>';
	$menue .= '<a href="index.php?res=s" class="st">'.$text["jury_result"].'</a><br>';
	$menue .= 'logfile: <a href="index.php?m=l&l=cron" class="st">cron</a> <a href="index.php?m=l&l=prejury" class="st">prejury</a> <a href="index.php?m=l&l=jury" class="st">jury</a> <a href="index.php?m=l&l=admin" class="st">admin</a> <a href="index.php?m=l&l=login" class="st">login</a><br>';
	$menue .= '<a href="meeting.php" class="st">meeting</a><br>';
	$menue .= '</p>';
	
	return $menue;
}

?>