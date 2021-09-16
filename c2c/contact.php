<?php
require "config/config2call.php";
$post = (!empty($_POST)) ? true : false;
if($post) {
require "functions.php";
sleep(1);
$destination = stripslashes($_POST['destination']);
$phonenumber = stripslashes($_POST['phonenumber']);
$number = $destination . $phonenumber;
$cbdelay = @stripslashes($_POST['delay']);
$action = stripslashes($_POST['action']);
if(@isset($_POST['prefix'])) {$prefix=$_POST['prefix'];}
$error = '';
// Check the phone number
if(!$number) {$error = 'Please enter your phone number.<br />';}
if($number && !ValidateNumber($number)) {$error = 'Please enter a valid number.<br />';}
//definitions
		$file='calls.log';
		if (empty($att)) {$att=3;}
		if (empty($period)) {$period=24;}//hours
		if (empty($delay)) {$delay=0;}//seconds
		if (empty($logsize)) {$logsize=300;}//strings
		if (empty($logtime)) {$logtime=1;}//weeks
		$rows=array('time', 'ip', 'number', 'delay', 'err');
		$def=array('NULL', 'NULL', $number, $delay, 'NULL');
		for ($i = 0; $i < count($rows); $i++) {
			$val[$rows[$i]] = $def[$i];
		}
//ip
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$val['ip']=$_SERVER['HTTP_CLIENT_IP']; // share internet
		} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$val['ip']=$_SERVER['HTTP_X_FORWARDED_FOR']; // pass from proxy
		} else {
			$val['ip']=$_SERVER['REMOTE_ADDR'];
		}
//fix the time
		$val['time']=time();
//check in log
		if (file_exists($file) and $fh=fopen($file, 'r')) {
			$n=$numc=$ipc=0;$strings=array();
			while ($string=fgets($fh)) {
				$strings[$n]=$string;$n++;
				//$p=split(',',$string);
				$p=explode(',',$string);
				if ($p[0]>$val['time']-$period*3600) {
					if ($p[1]==$val['ip']) $ipc++;
					if ($p[2]==$val['number']) $numc++;
				} else {continue;}
			}
			fclose($fh);
//check logsize
			if ($n>=$logsize) {
				if (unlink($file)) {
					$fh=fopen($file, 'w');
					$i=1;
//check logtime and don't write to new log messages older than $logtime weeks
					while ($i<$logsize) {
						//$p=split(",",$strings[$n-$logsize+$i]);
						$p=explode(",",$strings[$n-$logsize+$i]);
						if ($p[0]>=$val['time']-$logtime*3600*24) {
							fwrite($fh,$strings[$n-$logsize+$i]);
						}
						$i++;
					}
					fclose($fh);
				}
			}
			unset($strings);
			if ($ipc>=$att) {$val['err']='IP is blocked'; $error="You are not allowed to make more than " . $att . " call attempts from the same IP address per " . $period . " hours";}
			elseif ($numc>=$att) {$val['err']='Number is blocked'; $error="You are not allowed to make more than " . $att . " call attempts to the same destination number per " . $period . " hours";}
		}
		switch ($action) {
			case 'send':
			set_time_limit($cbdelay+30);
			$data = array ('Account' => $cbaccount, 'Password' => $cbpassword);
			$order = (empty($number_order)) ? 'ivr' : $number_order;
			switch ($order) {
				case 'person':
					$data += array ('First_Phone_Number' => $phonenumber1, 'Second_Phone_Number' => $number);  //'i_env' => $i_env
				break;
				case 'ivr':
				default:
					$data += array ('First_Phone_Number' => $number, 'Second_Phone_Number' => $phonenumber1);  //'i_env' => $i_env
				break;
			}
			//$data = http_build_query($data);
			if ($app_type == 'um') {
				$web_ip = 'um.telinta.com';
				$domainname = 'https://' . $web_ip . ':8901';
				$url = $domainname.'/cgi/web/receive.pl';
			}
			if ($cbdelay > 0) {
				sleep($cbdelay);
			}
	//write to log
			if (!file_exists($file)) {$fh=fopen($file,"w");}else{$fh=fopen($file,"a");}
			$string=getlist($val);
			fwrite($fh,$string."\n");
			fclose($fh);
			if($app_type == 'um') {
				do_post_request($url, $data, $c2c_addr);
			}
			break;
			case 'check':
?>
function c2c() {
<?php
//check for errors
			if ($val['err'] == 'NULL') {
				if ($app_type=='um'){
					$action = 'send';
?>
$.post("<?php echo $path?>contact.php", { 'destination': "<?php echo $destination?>",'phonenumber': "<?php echo $phonenumber?>",'delay': "<?php echo $cbdelay?>", 'action': "<?php echo $action?>", 'prefix': "<?php echo $prefix?>" });
result = '<div class="notification_ok">Your request is accepted. <br /> Please wait for the call. <br /> <br /> <a id="c2c-try-<?php echo $prefix;?>" href=#>Try Again</a></font></div>';
<?php				}else{	?>
$.post("<?php echo $path?>contact.php", {'delay': "<?php echo $cbdelay?>", 'action': "<?php echo $action?>", 'prefix': "<?php echo $prefix?>" });
result = '<div class="notification_ok">Your request is accepted. <br /> The call is in action. <br /> <br /> <input type=button name="end_call" id="c2c-try-<?php echo $prefix;?>" value="Hang Up"/></font></div>';
<?php				}	?>
<?php			} else { ?>
result = '<?php echo $error;?>' + '<br /> <a id="c2c-try-<?php echo $prefix;?>" href=#>Try Again</a>';
<?php 				}
?>
$("#c2c-note-<?php echo $prefix;?>").html(result);
}
<?php			break;
		}
}
?>
