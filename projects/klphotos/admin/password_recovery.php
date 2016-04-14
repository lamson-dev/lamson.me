<?php
/*

Password recovery for Pixelpost
Pixelpost www: http://www.pixelpost.org/


Version 1.0:
Development Team:
Eon (eonlepapillon@gmail.com

License: http://www.gnu.org/copyleft/gpl.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

*/

$update=$_POST['update'];
if($update=="1"){
	$pixelpost_db_host=$_POST['pixelpost_db_host'];
	$pixelpost_db_user=$_POST['pixelpost_db_user'];
	$pixelpost_db_pass=$_POST['pixelpost_db_pass'];
	$pixelpost_db_pixelpost=$_POST['pixelpost_db_pixelpost'];
	$pixelpost_db_prefix=$_POST['pixelpost_db_prefix'];
	$admin=$_POST['admin'];
	$new_pass1=$_POST['new_pass1'];
	$new_pass2=$_POST['new_pass2'];
	$warning="";

	if($new_pass1==$new_pass2){
		if(!mysql_connect($pixelpost_db_host, $pixelpost_db_user, $pixelpost_db_pass) ){
			$warning.="Connect DB Error: ". mysql_error();
		}
		
		if(!mysql_select_db($pixelpost_db_pixelpost) ){
			$warning.="Select DB Error: ". mysql_error();
		}
		
		$query = "update ".$pixelpost_db_prefix."config set password=MD5('".$new_pass1."') where admin='".$admin."'";
		if(mysql_query($query)){
			$message="
				Password changed :D<br />
				<b>NOTE:</b> After testing your password don't forget to delete this file!";
		}else{
			$dberror=mysql_error();
			$warning.="Database error: " .$dberror ."<br />Updating the new password failed. " ; 
		}
	}else{
		$warning.="Passwords are different!" ; 
	}
	echo $warning;
	echo $message;
}else{
	$message="
		You can find the database information in pixelpost.php.<br />
		<table border=\"0\">
		<form method=\"post\" action=\"password_recovery.php\" accept-charset=\"UTF-8\">
			<tr>
				<td>Host:</td>
				<td><input type=\"text\" name=\"pixelpost_db_host\" value=\"localhost\" /></td>
			<tr>
			<tr>
				<td>Database-user:</td>
				<td><input type=\"text\" name=\"pixelpost_db_user\" /></td>
			<tr>
			<tr>
				<td>Database-password:</td>
				<td><input type=\"password\" name=\"pixelpost_db_pass\" /></td>
			<tr>
			<tr>
				<td>Database-name:</td>
				<td><input type=\"text\" name=\"pixelpost_db_pixelpost\" /></td>
			<tr>
			<tr>
				<td>Database-prefix:</td>
				<td><input type=\"text\" name=\"pixelpost_db_prefix\" /></td>
			<tr>
			<tr>
				<td colspan=\"2\"><hr /></td>
			<tr>
			<tr>
				<td>Name of admin:</td>
				<td><input type=\"text\" name=\"admin\" /></td>
			<tr>
			<tr>
				<td>New password of admin:</td>
				<td><input type=\"password\" name=\"new_pass1\" /></td>
			<tr>
			<tr>
				<td>Confirm new password:</td>
				<td><input type=\"password\" name=\"new_pass2\" /></td>
			<tr>
			<tr>
				<td>&nbsp; <input type=\"hidden\" name=\"update\" value=\"1\" /></td>
				<td><input type=\"submit\" value=\"Recover\" /></td>
			</tr>
		</form>
		</table>
	";
	echo $message;
}

?>