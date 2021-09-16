<?php
$data=array('number'=>'1315');
$data['number'] = preg_replace("/[^a-zA-Z0-9]/", "", $data['number']) ? preg_replace("/[^a-zA-Z0-9]/", "", $data['number']) : $data['number'];
$data['number'] = substr($data['number'], 0, strpos($data['number'], "xxx")) ? substr($data['number'], 0, strpos($data['number'], "xxx"))."X".time() : $data['number'];

echo $data['number'];
die();
$data['number'] = ($numb = explode("-",$data['number']) && isset($numb)) ? $numb[0]."-".time() : $data['number'];
var_dump( $data['number']);
?>

