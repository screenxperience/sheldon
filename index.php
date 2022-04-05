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
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Home</title>
		<?php
		require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
		?>
	</head>
	<body>
	<?php
	require($_SERVER['DOCUMENT_ROOT'].'/include/body.inc.php');
	?>
	</body>
</html>
