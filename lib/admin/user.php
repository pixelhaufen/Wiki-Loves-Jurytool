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
	
	$user = $db->real_escape_string($_POST["user"]);
	$pass = $db->real_escape_string(sha1($_POST["password"].$config["salt"]));
	
	$sql = "INSERT INTO `".$config["dbprefix"].$type."jury` (`lname`, `pw`, `userlevel`) VALUES ('" . $user . "', '" . $pass . "', " . $_POST["userlevel"] . ")";
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
			<td>'.$text['userlevel'].'</td>
			<td>
				<select name="userlevel">
					<option value="1">'.$text['admin'].'</option>
					<option value="2">'.$text['manager'].'</option>
					<option value="0" selected>'.$text['user'].'</option>
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
	global $text;
	
	if($user==1)
	{
		return $text['admin'];
	}
	else if ($user==2)
	{
		return $text['manager'];
	}
	else
	{
		return $text['user'];
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
	global $text;
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
			<td>'.user($row['userlevel']).'</td>
			<td>'.date('d.m.Y H:i:s',$row['time']).'</td>
			</tr>';
	}
	$userlist .= '</table>';
	
	return $userlist;
}

function prejury_user($db)
{
	global $text;
	
	$uploader = "";
	
	if(isset($_POST["user"]))
	{
		$uploader .= create_user($db, "v_");
	}
	else
	{
		$uploader .= create_user_form($db, "pj=s", $text['prejury']);
	}
	
	$uploader .= user_list($db, "v_");
	
	return $uploader;
}

function jury_user($db)
{
	global $text;
	
	$uploader = "";
	
	if(isset($_POST["user"]))
	{
		$uploader .= create_user($db, "");
	}
	else
	{
		$uploader .= create_user_form($db, "j=s", $text['jury']);
	}
	
	$uploader .= user_list($db, "");
	
	return $uploader;
}


?>