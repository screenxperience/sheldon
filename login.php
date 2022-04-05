<?php
session_start();

session_regenerate_id();

if(!empty($_SESSION['user']['online']))
{
	header('location:http://'.$_SERVER['HTTP_HOST'].'/index.php');
	exit;
}
else
{
	require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');
	
	require($_SERVER['DOCUMENT_ROOT'].'/include/randomstr.inc.php');
	
	require($_SERVER['DOCUMENT_ROOT'].'/include/strhash.inc.php');
	
	$output = '';
	
	if(!empty($_POST))
	{
		if(empty($_POST['user_id']) || empty($_POST['user_password']))
		{
			$output .= '<div class="panel dark">';
			$output .= '<p>Geben Sie ihre Zugangsdaten ein.</p>';
			$output .= '</div>';
		}
		else
		{
			if(strlen($_POST['user_id']) == 8 && preg_match('/[^'.$app_regex['number'].']/',$_POST['user_id']) == 0)
			{
				if(strlen($_POST['user_password']) >= 10)
				{
					if(preg_match('/[^'.$app_regex['lowerupperumlnumbersz'].']/',$_POST['user_password']) == 0)
					{
						$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);
						
						if(!$sql)
						{
							$output .= '<h1>Error</h1>';
							$output .= '<div class="panel dark">';
							$output .= '<p>Es konnte keine Datenbankverbindung hergestellt werden.</p>';
							$output .= '</div>';
						}
						else
						{
							$query = sprintf("
							SELECT user_vname,user_name,user_password,user_salt,user_active
							FROM user
							WHERE user_id = '%s';",
							$sql->real_escape_string($_POST['user_id']));
							
							$result = $sql->query($query);
							
							if($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								if($row['user_active'])
								{
									if($row['user_password'] == strhash($row['user_salt'].$_POST['user_password']))
									{
										$initial_vname = substr($row['user_vname'],0,1);
										
										$initial_name = substr($row['user_name'],0,1);
										
										$csrf_token = randomstr(10);
										
										session_start();
										
										$_SESSION = array(
										'user' => array('online' => true,'id' => $_POST['user_id'],'initials' => $initial_vname.$initial_name,'token' => $csrf_token),
										'cart' => array('user' => '','assets' => array()));
										
										header('location:http://'.$_SERVER['HTTP_HOST'].'/index.php');
										exit;
									}
									else
									{
										$output .= '<div class="panel dark">';
										$output .= '<p>Login nicht erfolgreich.</p>';
										$output .= '</div>';
									}
								}
								else
								{
									$output .= '<div class="panel dark">';
									$output .= '<p>Login nicht erfolgreich.</p>';
									$output .= '</div>';
								}
							}
							else
							{
								$output .= '<div class="panel dark">';
								$output .= '<p>Login nicht erfolgreich.</p>';
								$output .= '</div>';
							}
						}
					}
					else
					{
						$regex = str_replace('\s',' Leerzeichen ',$app_regex['lowerupperumlnumbersz']);
						
						$regex = str_replace('\\','',$regex);
						
						$output .= '<div class="panel dark">';
						$output .= '<p>Verwenden Sie nur folgende Zeichen in ihrem Passwort: '.$regex.'</p>';
						$output .= '</div>';
					}
				}
				else
				{
					$output .= '<div class="panel dark">';
					$output .= '<p>Ihr Passwort ist mind. 10 Zeichen lang.</p>';
					$output .= '</div>';
				}
			}
			else
			{
				$output .= '<div class="panel dark">';
				$output .= '<p>Die Personalnummer ist 8 Zeichen lang und besteht nur aus Zahlen.</p>';
				$output .= '</div>';
			}
		}
	}
}					
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Login</title>
		<?php
		require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
		?>
	</head>
	<body>
		<div class="bg-air">
			<div class="container">
				<div class="content-center">
					<div class="container white">
						<p class="text-right"><a class="btn-default light-blue" href="register.php"><i class="fas fa-plus"></i></a></p>
						<div class="text-center">
							<p><img src="/images/logo.svg" style="width:120px;"/></p>
							<h1>Login</h1>
						</div>
						<form action="login.php" method="post">
							<?php
							if(!empty($output))
							{
								echo $output;
							}
							?>
							<p><input class="ipt-default border-grey" type="number" name="user_id" value="" placeholder="Personalnummer"/></p>
							<p><a href="forgotpasswd.php">Passwort vergessen ?</a><input class="ipt-default border-grey" type="password" name="user_password" value="" placeholder="Passwort"/></p>
							<p><button class="block btn-default light-blue" type="submit">einloggen <i class="fas fa-arrow-right"></i></button></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
