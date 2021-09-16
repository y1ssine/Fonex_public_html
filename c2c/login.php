<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login area</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
  <div id="container">
  <?php
    if (!ini_get('allow_url_fopen')) {
      echo 'Enable allow_url_fopen in PHP.ini';
    } elseif (!extension_loaded('curl')) {
      echo 'Enable the curl extension';
    } elseif (!is_writable(__DIR__.'/config/')) {
      echo 'The /config/ folder should be writable';
    } elseif (!is_writable(__DIR__.'/logos/')) {
      echo 'The /logos/ folder should be writable';
    } elseif (!is_writable(__DIR__.'/config/country_codes.php')) {
      echo 'The /config/country_codes.php file should be writable';
    } elseif(is_dir(__DIR__.'/webrtc_embed/') and !is_writable(__DIR__.'/webrtc_embed/')){
      echo 'The /webrtc_embed/ folder should be writable';
    } elseif(is_dir(__DIR__.'/webrtc_embed/') and !is_writable(__DIR__.'/webrtc_embed/config.js')){
      echo 'The /webrtc_embed/config.js file should be writable';
    } else {
      $res = do_post_request('https://208.89.104.15:8444/', array(), '');
      if ($res === FALSE) {
        echo 'Reseller access is not available, you should allow outgoing requests via HTTPS to port 8444, contact your system administrator regarding this matter<br/>';
      }
      $res = do_post_request('https://208.89.104.14:8901/', array(), '');
      if ($res === FALSE) {
        echo 'You should allow outgoing requests via HTTPS to port 8901 to use the UM callback type, contact your system administrator regarding this matter<br/>';
      }
  ?>
	<h2>Please enter your admin/reseller login and password</h2>
	<div class="login-form">
	  <form id="login_form" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	    <label for="login">Login: </label> <br />
	    <input type="text" id="login" name="login" /><br />
	    <label for="password">Password: </label> <br />
	    <input type="password" id="password" name="password" /><br />
	    <input type="submit" id="submit" value="Login" class="submit" />
	  </form>
	</div>
	<?php } ?>
  </div>
</body>
</html>
