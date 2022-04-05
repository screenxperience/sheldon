<?php
function strhash($str)
{
	$hash = hash('sha256',$str);
	
	return $hash;
}
	