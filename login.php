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
	
	$showform = 1;
	
	if(!empty($_POST))
	{
		if(empty($_POST['user_id']) || empty($_POST['user_password']))
		{
			$output .= '<div class="panel black-alpha">';
			$output .= '<p>Geben Sie ihre Zugangsdaten ein.</p>';
			$output .= '</div>';
		}
		else
		{
			if(strlen($_POST['user_id']) == 8 && preg_match('/[^0-9]/',$_POST['user_id']) == 0)
			{
				$user_id = $_POST['user_id'];
				
				if(strlen($_POST['user_password']) >= 10)
				{
					if(preg_match('/[^a-zA-ZöäüÖÄÜß0-9\-\.\:\?\!\&\/\(\)\#\+\,]/',$_POST['user_password']) == 0)
					{
						$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);
						
						if(!$sql)
						{
							$output .= '<div class="panel black-alpha">';
							$output .= '<p>Es konnte keine Datenbankverbindung hergestellt werden.</p>';
							$output .= '</div>';
						}
						else
						{
							$query = sprintf("
							SELECT user_vname,user_name,user_password,user_salt,user_failed_login,user_active
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
										
										if($row['user_failed_login'])
										{
											$query = sprintf("
											UPDATE user
											SET user_failed_login = '0'
											WHERE user_id = '%s';",
											$sql->real_escape_string($_POST['user_id']));
											
											$sql->query($query);	
										}
										
										header('location:http://'.$_SERVER['HTTP_HOST'].'/index.php');
										exit;
									}
									else
									{
										if($row['user_failed_login'] == 2)
										{
											$query = sprintf("
											UPDATE user
											SET user_active = '0'
											WHERE user_id = '%s';",
											$sql->reaL_escape_string($_POST['user_id']));
											
											$sql->query($query);

											if($sql->affected_rows == 1)
											{
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Login nicht erfolgreich. Ihr Account wurde deaktiviert.</p>';
												$output .= '</div>';
											}
										}
										else
										{
											$user_failed_login = $row['user_failed_login']+1;
											
											$query = sprintf("
											UPDATE user
											SET user_failed_login = '%s'
											WHERE user_id = '%s';",
											$sql->real_escape_string($user_failed_login),
											$sql->real_escape_string($_POST['user_id']));
											
											$sql->query($query);
											
											if($sql->affected_rows == 1)
											{
												$user_attempts = 3-$user_failed_login;
												
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Login nicht erfolgreich. Sie haben noch '.$user_attempts.' Versuch/e &uuml;brig.</p>';
												$output .= '</div>';
											}
										}
									}
								}
								else
								{
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Login nicht erfolgreich. Ihr Account ist deaktiviert.</p>';
									$output .= '</div>';
								}
							}
							else
							{
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Login nicht erfolgreich.</p>';
								$output .= '</div>';
							}
						}
					}
					else
					{	
						$output .= '<div class="panel black-alpha">';
						$output .= '<p>Verwenden Sie nur folgende Zeichen in ihrem Passwort: a-z, A-Z, öäüÖÄÜß, 0-9, -.:?!&/()#+,</p>';
						$output .= '</div>';
					}
				}
				else
				{
					$output .= '<div class="panel black-alpha">';
					$output .= '<p>Ihr Passwort ist mind. 10 Zeichen lang.</p>';
					$output .= '</div>';
				}
			}
			else
			{
				$output .= '<div class="panel black-alpha">';
				$output .= '<p>Die Personalnummer ist 8 Zeichen lang und besteht nur aus Zahlen.</p>';
				$output .= '</div>';
			}
		}
	}
	
	if($showform)
	{
		$output .= '<form action="login.php" method="post">';
		$output .= '<p><input class="input-default border border-grey" type="number" name="user_id" placeholder="Personalnummer"';
		
		if(!empty($user_id))
		{
			$output .= ' value="'.$user_id.'"';
		}
		
		$output .= '/></p>';
		$output .= '<p><input class="input-default border border-grey" type="password" name="user_password" value="" placeholder="Passwort"/></p>';
		$output .= '<p><button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">einloggen <i class="fas fa-arrow-right"></i></button></p>';
		$output .= '</form>';
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
				<div class="content-center container white-alpha">
					<p class="text-right">
						<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="register.php">
							<i class="fas fa-plus"></i>
						</a>
					</p>
					<div class="text-center">
						<p>
							<img src="/images/logo.svg" style="width:120px;"/>
						</p>
						<h1>Login</h1>
					</div>
					<?php
					if(!empty($output))
					{
						echo $output;
					}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
