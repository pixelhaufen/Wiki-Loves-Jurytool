<?php session_start(); 
/**
 * Wiki Loves Jurytool prejury
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

if (!file_exists("config/config.php")) // goto install.php
{
	$forward =  '<meta http-equiv="refresh" content="1; url=install.php" />';
}
else
{
	require_once "config/config.php";
	require_once "l10n/".$config["language"].".php";
	require_once "lib/prejury/deadline.php";
	if (deadline()!= "0") // goto prejury
	{
		$forward = '<meta http-equiv="refresh" content="1; url=prejury/index.php" />';
	}
	else // goto jury
	{
		$forward = '<meta http-equiv="refresh" content="1; url=jury/index.php" />';
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Install Wiki Loves Jurytool</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php echo $forward; ?>
	<style type="text/css">
	td
	{
		text-align:left;
		padding-left: 25px;
	}
	</style>
</head>

<body style="background-color: white; text-align: center;">

	<center>
		<img src="theme/img/WLX.svg"/>
	</center>

	<p>Tool: 2011 - 2015 <a href="http://pixelhaufen.at">Ruben Demus</a> on behalf of <a href="https://wikimedia.at">Wikimedia Austria - WMAT</a>, <a href="https://www.gnu.org/licenses/agpl-3.0.en.html">GNU AGPL v3</a> </p>
</body>
