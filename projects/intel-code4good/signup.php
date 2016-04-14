 <?php
 require_once("includes/init.php");
 
//handle login 
if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['password_confirm'];
	$email = $_POST['email'];
	
	if($password == $passwordConfirm)
	{
		$query = "INSERT INTO users(username, password, email) VALUES ('".$username."', '".encrypt_password($password)."', '".$email."')";
		echo $query;
		$db->query($query);
		redirect_to(SITE);
	}
	
	$session->login($username,$password);	
}

//redirect if logged in to main page
if($session->is_logged_in()) { 
	redirect_to(SITE); 
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<form name="login" method="post" action="<?php echo 'signup.php'; ?>">
		<td>
			<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
				<tr>
					<td colspan="3"><strong>Logins</strong></td>
				</tr>
				<tr>
					<td width="78">Username</td>
					<td width="6">:</td>
					<td width="294"><input name="username" type="text"></td>
				</tr>
				<tr>
                    <td>Password</td>
                    <td>:</td>
                    <td><input name="password" type="password"></td>
                </tr>
				<tr>
					<td width="78">Password Confirm</td>
					<td width="6">:</td>
					<td width="294"><input name="password_confirm" type="text"></td>
				</tr>
				<tr>
					<td width="78">email</td>
					<td width="6">:</td>
					<td width="294"><input name="email" type="text"></td>
				</tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="Login"></td>
				</tr>
			</table>
		</td>
		</form>
	</tr>
</table>

<body>
</body>
</html>