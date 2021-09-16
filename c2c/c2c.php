<?php
require_once "functions.php";
$loc = findpath(__FILE__);
if (!file_exists($loc."config/config2call.php")) {
	?><script>document.location.href="<?php echo $path; ?>click2callwiz.php"</script><?php
	exit;
}
require $loc."head.php";
if ($prefix == 'Button') {
	if (!$buttontext) {$buttontext = 'TeliClick';}
?>
	<div id="button"><input type="button" value="<?php echo $buttontext; ?>" /></div>
	<div id="popupContact">
		<a id="popupContactClose">x</a>
		<?php require $loc."form.php"; ?>
	</div>
	<div id="backgroundPopup"></div>
<?php
} else {require $loc."form.php";}
require "foot.php";
?>