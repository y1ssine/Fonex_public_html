<?php
require_once "functions.php";
require_once "soapauth.php";
require "config/config2call.php";
	$loc = findpath(__FILE__);
	$path = findpath($_SERVER['PHP_SELF']);
?>
<html>
<head>
	<title>Sample integration page</title>
</head>
<body>
	<center>
	<br><br>
	<h1>Sample integration page</h1><br>
	Once you verify that your test works properly, you can place the following PHP code into your site.
	<br><b>&lt;?php include "<?php echo $loc;?>c2c.php";?&gt;</b><br><br>
	<?php 
	if($prefix=='Button'){
	?>
	<b>TeliClick button</b><br>
	To test functionality of the TeliClick button please click the "Click To Call" button.<br>
	<br>
	<?php
	$prefix='button'; include "c2c.php";
	?>
	<br>
	<?php
	}
	if($prefix=='Form'){
	?>
	<b>TeliClick form</b><br>
	To test functionality of the TeliClick form please fill in fields of the form and click the 'Call Us' button.<br>
	<br>
	<div style="border:1px solid #999999;width: 370px;">
	<?php
	$prefix='form'; include "c2c.php";
	?>
	</div>
	<?php
	}
	?>
	<br>To make changes to the configuration settings, <a href="click2callwiz.php">return to the wizard</a>.<br>
	</center>
</body>
</html>