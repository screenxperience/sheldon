<?php
session_start();

session_regenerate_id();

if(empty($_SESSION['user']['online']))
{
	header('location:http://'.$_SERVER['HTTP_HOST'].'/login.php');
	exit;
}
else
{
	$initials = $_SESSION['user']['initials'];
	
	$cart_count = count($_SESSION['cart']['assets']);
}
?>