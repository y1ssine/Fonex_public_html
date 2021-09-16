		<h1><?php echo $header ?></h1>
		<div id="c2c-div-logo-<?php echo $prefix;?>">
			<img src="<?php echo $logo?>" alt="" id="c2c-logo-<?php echo $prefix;?>">
		</div>
		<div id="c2c-content-<?php echo $prefix;?>">
			<form action="" method="post" class="hform" id="c2c-form-<?php echo $prefix;?>">
				<div align="left" style="width: 370px;">
					<div id="c2c-note-<?php echo $prefix;?>">
						<div class="notification_error"> <?php echo $popuptext;?><br />
						</div>
					</div>
					<div id="c2c-fields-<?php echo $prefix;?>">
						<select name="destination" id="c2c-destination-<?php echo $prefix;?>">
						<?php
							$r = explode(",", $destinations);
							sort($r);
							foreach($r as $value) {
								print "<option value=\"$value\">+$value</option>";
							}
						?>
						</select>
						<input class="textbox" type="text" name="phonenumber" id="c2c-phonenumber-<?php echo $prefix;?>" value=""><br />
						<?php
							if ($delays != '') {
								print '<label>When to call</label><br />';
								print '<select name="delay">';
								print "<option value=\"0\">Now</option>";
								$r = explode(",", $delays);
								sort($r);
								foreach($r as $value) {
									if ($value == "1") {
										$seconds = $value * 60;
										print "<option value=\"$seconds\">In a minute</option>";
									} elseif ($value != "0") {
										$seconds = $value * 60;
										print "<option value=\"$seconds\">In $value minutes</option>";
									}
								}
							}
						?>
						</select>
						<br />
						<input type="hidden" name="action" id="c2c-action-<?php echo $prefix;?>" value="check" />
						<input type="hidden" name="prefix" id="c2c-prefix-<?php echo $prefix;?>" value="<?php echo $prefix;?>" />
						<input type="submit" name="submit" id="c2c-submit-<?php echo $prefix;?>" value="Call Us"/>
					</div>
				</div>
			</form>
		</div>