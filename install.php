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

require_once "lib/lib.php"; // file functions
require_once "l10n/en.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Install Wiki Loves Jurytool</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css">
	td
	{
		text-align:left;
		padding-left: 25px;
	}
	pre
	{
		text-align: left;
		background-color: lavender;
	}
	</style>
</head>

<body style="background-color: white; text-align: center;">

	<center>
	<form action="install.php" method="post">

	<h2><img src="theme/img/WLX.svg" width="30px"> <?php echo $text['install']; ?></h2>

<?php

if (file_exists("config/config.php"))
{
	echo "<h3>".$text["clean_up"]."</h3>";
}
else if(isset($_POST["dbhost"]))
{

	$db = new mysqli($_POST["dbhost"], $_POST["dbuser"], $_POST["dbpassword"], $_POST["dbname"]);

	$sql1 = "CREATE TABLE IF NOT EXISTS `".$_POST["dbprefix"]."fotos` (
      `name` text COLLATE utf8_bin NOT NULL,
      `user` text COLLATE utf8_bin NOT NULL,
      `date` text COLLATE utf8_bin NOT NULL,
      `time` text COLLATE utf8_bin NOT NULL,
      `size` int(11) NOT NULL DEFAULT '0',
      `width` int(11) NOT NULL DEFAULT '0',
      `height` int(11) NOT NULL DEFAULT '0',
      `pixel` int(11) NOT NULL DEFAULT '0',
      `license` text COLLATE utf8_bin NOT NULL,
      `url` text COLLATE utf8_bin NOT NULL,
      `descriptionurl` text COLLATE utf8_bin NOT NULL,
      `exclude` int(11) NOT NULL DEFAULT '0',
      `vote` int(11) NOT NULL DEFAULT '0',
      `jury` int(11) NOT NULL DEFAULT '0',
      `online` int(11) NOT NULL DEFAULT '0'
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $sql2 = "CREATE TABLE IF NOT EXISTS `".$_POST["dbprefix"]."v_jury` (
      `lname` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `pw` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `width` int(11) NOT NULL DEFAULT '500',
      `info` int(11) NOT NULL DEFAULT '0',
      `admin` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $sql3 = "CREATE TABLE IF NOT EXISTS `".$_POST["dbprefix"]."v_votes` (
      `name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `user` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `vote` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL,
      `online` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $sql4 = "CREATE TABLE IF NOT EXISTS `".$_POST["dbprefix"]."jury` (
      `lname` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `pw` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `width` int(11) NOT NULL DEFAULT '500',
      `info` int(11) NOT NULL DEFAULT '0',
      `admin` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $sql5 = "CREATE TABLE IF NOT EXISTS `".$_POST["dbprefix"]."votes` (
      `name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `user` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `vote` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL,
      `online` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

	$sql6 = "CREATE TABLE `".$_POST["dbprefix"]."points` (
      `name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	  `round` int(11) NOT NULL,
	  `sum` int(11) NOT NULL DEFAULT '0'
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $sql7 = "INSERT INTO `" . $_POST["dbprefix"] . "jury`(`lname`,`pw`,`admin`) VALUES ('" . $_POST["admin"] . "','" . sha1($_POST["adminpassword"].$_POST["salt"]) . "','1');";

    $sql8 = "INSERT INTO `" . $_POST["dbprefix"] . "v_jury`(`lname`,`pw`,`admin`) VALUES ('" . $_POST["admin"] . "','" . sha1($_POST["adminpassword"].$_POST["salt"]) . "','1');";

	if ($db->connect_error)
	{
		echo "<h3>1. ".$text["no_db"]."</h3>";
		echo "<pre>".html_nl($sql1)."</pre>";
		echo "<pre>".html_nl($sql2)."</pre>";
		echo "<pre>".html_nl($sql3)."</pre>";
		echo "<pre>".html_nl($sql4)."</pre>";
		echo "<pre>".html_nl($sql5)."</pre>";
		echo "<pre>".html_nl($sql6)."</pre>";
		echo "<pre>".html_nl($sql7)."</pre>";
		echo "<pre>".html_nl($sql8)."</pre>";
	}
	else
	{
		if ($db->query($sql1) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql1)."</pre>";
		}
		if ($db->query($sql2) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql2)."</pre>";
		}
		if ($db->query($sql3) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql3)."</pre>";
		}
		if ($db->query($sql4) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql4)."</pre>";
		}
		if ($db->query($sql5) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql5)."</pre>";
		}
		if ($db->query($sql6) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql6)."</pre>";
		}
		if ($db->query($sql7) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql7)."</pre>";
		}
		if ($db->query($sql8) !== TRUE) {
			echo "<h3>1. ".$text["no_db"]."</h3>";
			echo "<pre>".html_nl($sql8)."</pre>";
		}
		$db->close();
		echo "<h3>1. ".$text["db_created"]."</h3>";
	}
	
	$config_file = "<?php
\$config = array(
	// Database
	'dbhost' => '".$_POST["dbhost"]."',
	'dbname' => '".$_POST["dbname"]."',
	'dbuser' => '".$_POST["dbuser"]."',
	'dbpassword' => '".$_POST["dbpassword"]."',
	'dbprefix' => '".$_POST["dbprefix"]."',

	// User
	'salt' => '".$_POST["salt"]."',

	// Logfiles
	'log' => '".$_POST["logfiles"]."', // NO, NORMAL, PARANOID, DEBUG

	// deadline Server time
	'dlyear' => ".$_POST["dlyear"].",
	'dlday' => ".$_POST["dlday"].",
	'dlmonth' => ".$_POST["dlmonth"].",
	'dlhour' => ".$_POST["dlhour"].",
	'dlminute' => ".$_POST["dlminute"].",
	'dlsecond' => ".$_POST["dlsecond"].",

	// jury goal
	'goal' => ".$_POST["goal"].",

	// language
	'language' => '".$_POST["language"]."', // de-at, de, en

	// category Commons
	'catadd' => array(
		".$_POST["catadd"]."
	),
	'catremove' => array(
		".$_POST["catremove"]."
	),

	'license' => array(
		".$_POST["license"]."
	),

	// general settings
	'title' => '".$_POST["title"]."',
	'name' => '".$_POST["name"]."',
	'version' => '".$_POST["version"]."',
	'url' => '".$_POST["url"]."',
	'mail' => '".$_POST["mail"]."',
	'logo' => '".$_POST["logo"]."',
	'icon' => '".$_POST["icon"]."',
	'time' => '".$_POST["time"]."', // commons loves UTC but MESZ is +2
);
?>
";
	save_file("config/config.php",unix_nl($config_file));
	if (file_exists("config/config.php"))
	{
		echo "<h3>2. ".$text["config_created"]."</h3>";
	}
	else
	{
		echo "<h3>2. ".$text["no_config"]."</h3>";
		echo "<pre>".html_replace($config_file)."</pre>";
	}
	echo "<h3>3. ".$text["clean_up"]."</h3>";
	
}
else
{
?>
		
	<table border=0 cellpadding=0px width=1000px style="text-align: left; table-layout:fixed;">
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td style="width: 160px;">
					<h3><?php echo $text['title']; ?></h3>
				</td>
				<td style="width: 300px">
					<br><input name="title" value="Wiki Loves Jurytool">
				</td>
				<td style="width: 540px">
					<br>Wiki Loves Jurytool
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['short name']; ?></h3>
				</td>
				<td>
					<br><input name="name">
				</td>
				<td>
					<br>WLX_ISO
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['version']; ?></h3> 
				</td>
				<td>
					<br><input name="version" value="0.1.0">
				</td>
				<td>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['url']; ?></h3> 
				</td>
				<td>
					<br><input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].str_replace("/install.php","",$_SERVER['PHP_SELF']); ?>">
				</td>
				<td>
					<br> <?php echo $text['used_in_user_agent']; ?><br>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['mail']; ?></h3> 
				</td>
				<td>
					<br><input name="mail" value="<?php echo 'tooladmin@'.$_SERVER['HTTP_HOST']; ?>">
				</td>
				<td>
					<br>mail@example.com - <?php echo $text['used_in_user_agent']; ?> <br>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['logo']; ?></h3> 
				</td>
				<td>
					<br><input name="logo" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].str_replace("install.php","",$_SERVER['PHP_SELF']).'theme/img/WLX.svg'; ?>">
				</td>
				<td>
					<br>200px, png/jpg/svg
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['icon']; ?></h3> 
				</td>
				<td>
					<br><input name="icon" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].str_replace("install.php","",$_SERVER['PHP_SELF']).'theme/img/WLX.svg'; ?>">
				</td>
				<td>
					<br>28px, png/jpg/svg
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['time']; ?></h3> 
				</td>
				<td>
					<br><input name="time" value="+2 hour">
				</td>
				<td>
					<br>+2 hour<br>
					<?php echo $text['UTC_MESZ']; ?>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['language']; ?></h3> 
				</td>
				<td>
					<br><input name="language" value="en">
				</td>
				<td>
					<br>de-at, de, en<br>
					<?php echo $text['language_info']; ?>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['logfiles']; ?></h3> 
				</td>
				<td>
					<br>
					<select name="logfiles">
					<option value="NO">NO</option>
					<option value="NORMAL" selected>NORMAL</option>
					<option value="PARANOID">PARANOID</option>
					<option value="DEBUG">DEBUG</option>
					</select>
				</td>
				<td>
					<br>
					NO, NORMAL, PARANOID, DEBUG<br>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['deadline']; ?></h3> 
				</td>
				<td style="padding-left: 0px;">
					<br>
					<table>
						<tr>
							<td>YYYY</td>
							<td><input name="dlyear" value="2038"></td>
						</tr>
						<tr>
							<td>DD</td>
							<td><input name="dlday" value="19"></td>
						</tr>
						<tr>
							<td>MM</td>
							<td><input name="dlmonth" value="1"></td>
						</tr>
						<tr>
							<td>HH</td>
							<td><input name="dlhour" value="3"></td>
						</tr>
						<tr>
							<td>mm</td>
							<td><input name="dlminute" value="14"></td>
						</tr>
						<tr>
							<td>ss</td>
							<td><input name="dlsecond" value="8"></td>
						</tr>
					</table>
					<br>
				</td>
				<td>
					<br><?php echo $text['countdown']; ?>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['goal']; ?></h3> 
				</td>
				<td>
					<br><input name="goal" value="10">
				</td>
				<td>
					<?php echo $text['jury_goal']; ?>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['catadd']; ?></h3> 
				</td>
				<td>
					<br>
					<textarea name="catadd" rows="4" cols="40">'Images_from_Wiki_Loves_XXX_20XX_in_XXX',</textarea>
					<br>
					<br>
				</td>
				<td>
					<br><?php echo $text['catinfo']; ?>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['catremove']; ?></h3> 
				</td>
				<td>
					<br>
					<textarea name="catremove" rows="4" cols="40">'Images_from_Wiki_Loves_XXX_20XX_in_XXX_not_for_prejury',</textarea><br>
					<br>
				</td>
				<td>
					<br><?php echo $text['catinfo']; ?>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['license']; ?></h3> 
				</td>
				<td>
					<br>
					<textarea name="license" rows="4" cols="40">'cc-by-sa-3',
'cc-by-sa-4',
'pd-self',
'cc-zero',</textarea><br>
					<br>
				</td>
				<td>
					<br>
					<?php echo $text['licenseinfo']; ?><br>&nbsp;<br>&nbsp;
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['salt']; ?></h3> 
				</td>
				<td>
					<br>
					<input name="salt" size="40" value="<?php echo sha1(time()."57696B69204C6F766573204A757279746F6F6C".time());?>"><br> 
				</td>
				<td>
					<br>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<h3><?php echo $text['database']; ?></h3> 
				</td>
				<td style="padding-left: 0px;">
					<br>
					<table>
						<tr>
							<td>host</td>
							<td><input name="dbhost" value="localhost"></td>
						</tr>
						<tr>
							<td>name</td>
							<td><input name="dbname" value="WLJ"></td>
						</tr>
						<tr>
							<td>user</td>
							<td><input name="dbuser" value="WLJdbUser"></td>
						</tr>
						<tr>
							<td>password</td>
							<td><input type="password" name="dbpassword" value=""></td>
						</tr>
						<tr>
							<td>prefix</td>
							<td><input name="dbprefix" value="wlx_<?php echo date("Y"); ?>_"></td>
						</tr>
					</table>
					<br>
				</td>
				<td>
					<br>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #EAEAEA;">
				<td>
					<h3><?php echo $text['admin']; ?></h3> 
				</td>
				<td style="padding-left: 0px;">
					<br>
					<table>
						<tr>
							<td>admin</td>
							<td><input name="admin" value="WMUsername"></td>
						</tr>
						<tr>
							<td>password</td>
							<td><input type="password" name="adminpassword" value=""></td>
						</tr>
					</table>
					<br>
				</td>
				<td>
					<br>
				</td>
			</tr>
			
			<tr valign="top" style="background-color: #F9F9F9;">
				<td>
					<br> 
				</td>
				
				<td style="text-align:right;">
					<br>
					<input type="submit" value="<?php echo $text['install'];?>">
					<br>
					<br>
				</td>
				
				<td>
					<br> 
				</td>
			</tr>
	</table>
	</form>
<?php	
}
?>
</html>
