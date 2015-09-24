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

function save_file($file,$data)
{
	$fp = fopen($file, "w");
	fputs ($fp, $data);
	fclose ($fp);
}

function append_file($file,$data)
{
	$fp = fopen($file, "a");
	fputs ($fp, $data);
	fclose ($fp);
}

function get_file($file)
{
	$ausgabe = "";
	if (file_exists($file))
	{
		$fp = @fopen($file, "r");
		while (!feof($fp))
		{
			$ausgabe = $ausgabe.fgets($fp, 200);
		}
		fclose ($fp);
	}
	return $ausgabe;
}

function html_table($data,$append="")
{
	$data = '<table width = "1000px" style="text-align: left; table-layout:fixed;"><tr> <td width="200px">'.$append.$data;
	$data = str_replace("\t", $append.'</td><td>'.$append, $data);
	$data = str_replace("\r\n", $append.'</td></tr><tr><td>'.$append, $data);
	$data = str_replace("\r", $append."</td></tr><tr><td>".$append, $data);
	$data = str_replace("\n", $append."</td></tr><tr><td>".$append, $data);
	$data .= $append."</td></tr></table>";
	return $data;
}

function html_nl($data)
{
	$data = str_replace("\r\n", "<br>", $data);
	$data = str_replace("\r", "<br>", $data);
	return $data;
}

function html_replace($data)
{
	$data = str_replace("<", "&lt", $data);
	$data = str_replace(">", "&gt", $data);
	return html_nl($data);
}

function unix_nl($data)
{
	$data = str_replace("\r\n", "\n", $data);
	$data = str_replace("\r", "\n", $data);
	return $data;
}

function stringEndsWith($str, $test)
{
	return substr_compare($str, $test, -strlen($test), strlen($test)) === 0;
}

?>
