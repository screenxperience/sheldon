<?php
function randomstr($length) {
	
	$str = '';
	
	$z = 'abcdefghijklmnopqrstuvwxyz!?%&$ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	
	for($i = 0; $i < $length; $i++)
	{
		$str .= substr($z,mt_rand(0,strlen($z)-1),1);
	}
	
	return $str;
}
?>