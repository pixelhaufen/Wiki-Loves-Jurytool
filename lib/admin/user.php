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

function create_user($db, $type)
{
	global $config;
	global $text;
	
	$sql = "INSERT INTO `".$config["dbprefix"].$type."jury` (`lname`, `pw`, `admin`) VALUES ('" . $_POST["user"] . "', '" . sha1($_POST["password"].$config["salt"]) . "', " . $_POST["admin"] . ")";
	$db->query($sql);
	
	$uploader = "<p>" . $text['user_created'].  ": ". $_POST["user"] . "</p>";
	
	return $uploader;
}

function create_user_form($db,$programm,$type)
{
	global $text;
	
	$uploader = '
		<form action="index.php?'.$programm.'" method="post">
		<h3>'.$text['new_user']." ".$type.'</h3> 

		<table border=0 cellpadding=0px width=300px style="text-align: left; table-layout:fixed;">
			<tr>
			<td>'.$text['user'].'</td>
			<td><input name="user" value="WMUsername"></td>
			</tr>

			<tr>
			<td>'.$text['password'].'</td>
			<td><input type="password" name="password" value=""></td>
			</tr>

			<tr>
			<td>'.$text['admin'].'</td>
			<td>
				<select name="admin">
					<option value="1">'.$text['Yes'].'</option>
					<option value="0" selected>'.$text['No'].'</option>
				</select>
			</td>
			</tr>

			<tr>
			<td></td>
			<td><br><input type="submit" value="'.$text['save'].'"><br> <br> </td>
			</tr>
		</table>
		</form>
	';
	
	return $uploader;
}

function user($user)
{
	if($user==1)
	{
		return "admin";
	}
	else
	{
		return "user";
	}
}

function info($info)
{
	if($info==1)
	{
		return "info";
	}
	else
	{
		return "silent";
	}
}

function user_list($db, $type)
{
	global $config;
	
	$userlist = '<table border=0 cellpadding=0px width=1000px style="text-align: left; ">';
	$sql = "SELECT * FROM `" . $config["dbprefix"] . $type . "jury`";
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC))
	{
		$userlist .= '<tr>
			<td>'.$row['lname'].'</td>
			<td>'.$row['pw'].'</td>
			<td>'.$row['width'].'</td>
			<td>'.info($row['info']).'</td>
			<td>'.user($row['admin']).'</td>
			<td>'.date('d.m.Y H:i:s',$row['time']).'</td>
			</tr>';
	}
	$userlist .= '</table>';
	
	return $userlist;
}

function prejury_user($db)
{
	global $text;
	
	$uploader = user_list($db, "v_");
	
	if(isset($_POST["user"]))
	{
		$uploader .= create_user($db, "v_");
	}
	else
	{
		$uploader .= create_user_form($db, "pj=s", $text['prejury']);
	}
	
	return $uploader;
}

function jury_user($db)
{
	global $text;
	
	$uploader = user_list($db, "");
	
	if(isset($_POST["user"]))
	{
		$uploader .= create_user($db, "");
	}
	else
	{
		$uploader .= create_user_form($db, "j=s", $text['prejury']);
	}
	
	return $uploader;
}


?>