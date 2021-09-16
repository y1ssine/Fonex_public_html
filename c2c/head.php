<?php
if(@isset($prefix)) {$tmp=$prefix;} else {$tmp=false;}
require_once $loc."config/config2call.php";
if($tmp) {$prefix=$tmp;}

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $header ?></title>
	<link href="<?php echo $path; ?>general.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo $path; ?>scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>scripts/jquery-ui-slider.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>scripts/jquery.slideLock.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>scripts/jquery.delegate.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>scripts/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>scripts/popup.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>scripts/jquery-resize.js"></script>
	<script type="text/javascript">
	function dial(){
		console.log("calling");
		setDst("<?php echo $num_to_dial;?>");
		sipAction('call');
	}
	function hang_up(){
		console.log("hang up");
		sipAction('hangup');
	}
	function eventHandler(e){
		<?php if($enable_buttons=='enabled_buttons') { ?>
			switch(e.detail.type){
				case 'connected': $('#c2c-note-form').hide(); $('#webrtc_embed').show(); break;
				case 'disconnected': $('#webrtc_embed').hide(); $("#c2c-try-<?php echo $prefix;?>").click(); break;
			}
		<?php }?>
		if(e.detail.type == 'disconnected') $("#c2c-try-<?php echo $prefix;?>").click();
	}
	
	$(document).ready(function(){
		<?php if($app_type == 'web_rtc') { ?>
			document.addEventListener('ls_webrtc', eventHandler);
			$("#c2c-phonenumber-<?php echo $prefix;?>").hide();
			$("#c2c-destination-<?php echo $prefix;?>").hide();
			$('#container').removeClass('default').addClass('dialpad');
			$('#dialer').css('display','inline');
			$('#container').css('display','initial');
		<?php } ?>

		$("#c2c-form-<?php echo $prefix;?>").submit(function(){
			var err = '';
			if (($("#c2c-phonenumber-<?php echo $prefix;?>").val().length + $("#c2c-destination-<?php echo $prefix;?>").val().length) > <?php if (empty($maxlen)) {$maxlen="14"; echo "$maxlen";} else echo "$maxlen";?>) {err = 'Entered number is too long';}
			if (err == '') {
				var str = $(this).serialize();
				$.ajax({
					type: "POST",
					url: "<?php echo $path; ?>contact.php",
					data: str,
					dataType: 'script',
					beforeSubmit: $("#c2c-note-<?php echo $prefix;?>").html('<p>Please wait. We are processing your request... </p> <div id="processing"><img src="<?php echo $path; ?>chrome/processing.gif" /></div> '),
					success: function(){
						c2c();
						<?php if($app_type == 'web_rtc') { ?>
							document.getElementById("c2c-try-<?php echo $prefix;?>").setAttribute("onclick", "$('#c2c-note-<?php echo $prefix;?>').html(''); $('#c2c-fields-<?php echo $prefix;?>').show(); hang_up();");
							dial();
						<?php }else{ ?>
							document.getElementById("c2c-try-<?php echo $prefix;?>").setAttribute("onclick", "$('#c2c-note-<?php echo $prefix;?>').html(''); $('#c2c-fields-<?php echo $prefix;?>').show();");
						<?php } ?>
						$("#c2c-fields-<?php echo $prefix;?>").hide();
					}
				});
			} else {$("#c2c-note-<?php echo $prefix;?>").html('<p>' + err + '</p>')};
			return false;
		});
		
		<?php
		if ($captcha == '0'){
		}
		else { print'
		$("#c2c-form-'.$prefix.'").slideLock({
		labelText: "Slide to Unlock:",
		noteText: "",
		lockText: "Locked",
		unlockText: "Unlocked",
		iconURL: "'.$path.'chrome/arrow_right.png",
		inputID: "sliderInput",
		onCSS: "#333",
		offCSS: "#aaa",
		inputValue: 1,
		saltValue: 9,
		checkValue: 10,
		submitID: "#c2c-submit-'.$prefix.'"
		});';
		}
		?>
		$("#c2c-logo-<?php echo $prefix;?>").resize({maxWidth: 370});
	});
	</script>  
</head>
<body>

<?php if($app_type == 'web_rtc') { ?>
	<div id='webrtc_embed' style="display: none;">
		<?php 
			include findpath(__FILE__)."index.html";
		?>
	</div>
<?php } ?>

<div class="c2c">