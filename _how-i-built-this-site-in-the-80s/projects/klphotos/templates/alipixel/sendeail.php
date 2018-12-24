
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="10; URL=/index.php" />
<title>Thanks for You</title>
<link href="./css/alipixel.css" rel="stylesheet" type="text/css" />
</head>
<body>
		
<?php

$ip = $_POST['ip'];
$httpref = $_POST['httpref'];
$httpagent = $_POST['httpagent'];
$visitor = $_POST['visitor'];
$visitormail = $_POST['visitormail'];
$notes = $_POST['notes'];
$attn = $_POST['attn'];


if (eregi('http:', $notes)) {
die ("Do NOT try that! ! ");
}
if(!$visitormail == "" && (!strstr($visitormail,"@") || !strstr($visitormail,".")))
{
echo "<h2>Use Back - Enter valid e-mail</h2>\n";
$badinput = "<h2>Feedback was NOT submitted</h2>\n";
echo $badinput;
die ("Go back! ! ");
}

if(empty($visitor) || empty($visitormail) || empty($notes )) {
echo "<h2>Use Back - fill in all fields</h2>\n";
die ("Use back! ! ");
}

$todayis = date("l, F j, Y, g:i a") ;

$attn = $attn ;
$subject = $attn;

$notes = stripcslashes($notes);

$message = "$todayis \n
Message: $notes \n
From: $visitor ($visitormail)\n
";

$from = "From: $visitormail\r\n";


mail("you@yourdomain.com", "Contact Form", $message, $from);

?>

<p  align="center"><br /><br />
<h1><u>Date</u></h1><br /><?php echo $todayis ?>
<br /><br />
<h1><u>Your Name</u></h1><br /><?php echo $visitor ?>
<br /><br />
<h1><u>Your Email</u></h1><br /><?php echo $visitormail ?>
<br /><br />

<h1><u>Your Message</u></h1><br /><br />
<?php $notesout = str_replace("\r", "<br/>", $notes);
echo $notesout; ?>
<br />
<h1><?php echo $ip ?></h1>
<br /><br />
<a href="/index.php"> Go Back </a>

<br /><br /><img src="./images/bar_down.png" width="160" height="29" border="0"/></p><br /><br />
</body>
</html>