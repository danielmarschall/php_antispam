<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<title>ViaThinkSoft AntiSpam Test</title>
</head>

<body>

<?php

echo '<form action="'.$PHP_SELF.'">
<input name="email" value="'.$_GET['email'].'">
<input name="linktext" value="'.$_GET['linktext'].'">
<input type="checkbox" name="crypt_linktext" checked>
<input type="submit">
</form>';

include 'antispam.inc.php';

$x = secure_email($_GET['email'], $_GET['linktext'], isset($_GET['crypt_linktext']));
echo '<textarea cols="120" rows="20">'.htmlentities($x).'</textarea>';
echo '<hr>';
echo $x;

?>

</body>

</html>
