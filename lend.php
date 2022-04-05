<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');
	
$output = '';

$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);

if(!$sql)
{
	$output .= '<div class="container">';
	$output .= '<div class="content-center container white">';
	$output .= '<h1>Error</h1>';
	$output .= '<div class="panel dark">';
	$output .= '<p>Es konnte keine Datenbankverbindung hergestellt werden.</p>';
	$output .= '</div>'; 
	$output .= '</div>'; 
	$output .= '</div>'; 
}
else
{
	if(!empty($_GET))
	{
		if(empty($_GET['aktion']))
		{
			$output .= '<div class="container">';
			$output .= '<div class="content-center container white">';
			$output .= '<h1>Error</h1>';
			$output .= '<div class="panel dark">';
			$output .= '<p>Es konnte keine Aktion durchgef&uuml;hrt werden.</p>';
			$output .= '</div>'; 
			$output .= '</div>'; 
			$output .= '</div>'; 
		}
		else
		{
			if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['aktion']) == 0)
			{
				$allowed_aktions = array('add','view');
				
				if(in_array($_GET['aktion'],$allowed_aktions))
				{
					if($_GET['aktion'] == $allowed_aktions[0])
					{
						$user_id = $_SESSION['cart']['user'];
						
						$assets = $_SESSION['cart']['assets'];
						
						if(empty($user_id) || empty($assets))
						{
							header('location:http://'.$_SERVER['HTTP_HOST'].'/');
							exit;
						}
						else
						{
							if(empty($_GET['lend_end']))
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel dark">';
								$output .= '<p>Es wurde kein Ausleihzeitraum gew&auml;hlt.</p>';
								$output .= '</div>'; 
								$output .= '</div>'; 
								$output .= '</div>'; 
							}
							else
							{
								preg_match('/'.$app_regex['date'].'/',$_GET['lend_end'],$date_matches);
								
								if(!empty($date_matches))
								{
									$date_parts = explode('-',$_GET['lend_end']);
									
									if(checkdate($date_parts[1],$date_parts[2],$date_parts[0]))
									{	
										$document_nr = strtotime('now');
										
										$query = sprintf("
										INSERT INTO
										lend
										(lend_document_nr,lend_creator_id,lend_user_id,lend_assets,lend_start,lend_end)
										VALUES
										('%s','%s','%s','%s','%s','%s');",
										$sql->real_escape_string($document_nr),
										$sql->real_escape_string($_SESSION['user']['id']),
										$sql->real_escape_string($user_id),
										$sql->real_escape_string(json_encode($assets)),
										$sql->real_escape_string(date('Y-m-d',$document_nr)),
										$sql->real_escape_string($_GET['lend_end']));
										
										$sql->query($query);
										
										if($sql->affected_rows == 1)
										{
											$_SESSION['cart']['user'] = '';
											
											$_SESSION['cart']['assets'] = array();
											
											$cart_count = 0;
											
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white">';
											$output .= '<div class="panel dark">';
											$output .= '<p>Eintrag wurde mit der Nummer <strong>'.$document_nr.'</strong> im System erfasst.</p>';
											$output .= '</div>'; 
											$output .= '</div>'; 
											$output .= '</div>'; 
										}
										else
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white">';
											$output .= '<h1>Error</h1>';
											$output .= '<div class="panel dark">';
											$output .= '<p>Es konnte kein Eintrag erzeugt werden.</p>';
											$output .= '</div>'; 
											$output .= '</div>'; 
											$output .= '</div>'; 
										}
									}
									else
									{
										$output .= '<div class="container">';
										$output .= '<div class="content-center container white">';
										$output .= '<h1>Error</h1>';
										$output .= '<div class="panel dark">';
										$output .= '<p>Das eingegebene Datum existiert nicht.</p>';
										$output .= '</div>'; 
										$output .= '</div>'; 
										$output .= '</div>'; 
									}
								}
								else
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white">';
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel dark">';
									$output .= '<p>Geben Sie ein g&uml;tiges Datum mit der Form YYYY-MM-DD ein.</p>';
									$output .= '</div>'; 
									$output .= '</div>'; 
									$output .= '</div>';
								}
							}
						}
					}
					else if($_GET['aktion'] == $allowed_aktions[1])
					{
						
					}
				}
				else
				{
					$output .= '<div class="container">';
					$output .= '<div class="content-center container white">';
					$output .= '<h1>Error</h1>';
					$output .= '<div class="panel dark">';
					$output .= '<p>Es konnte keine Aktion durchgef&uuml;hrt werden.</p>';
					$output .= '</div>'; 
					$output .= '</div>'; 
					$output .= '</div>';
				}
			}
			else
			{
				$output .= '<div class="container">';
				$output .= '<div class="content-center container white">';
				$output .= '<h1>Error</h1>';
				$output .= '<div class="panel dark">';
				$output .= '<p>Es konnte keine Aktion durchgef&uuml;hrt werden.</p>';
				$output .= '</div>'; 
				$output .= '</div>'; 
				$output .= '</div>';
			}
		}
	}
	else
	{
		$output .= '<div class="container">';
		$output .= '<div class="content-center container white">';
		$output .= '<h1>Error</h1>';
		$output .= '<div class="panel dark">';
		$output .= '<p>Es wurden keine Daten gesendet.</p>';
		$output .= '</div>'; 
		$output .= '</div>'; 
		$output .= '</div>';
	}
}

?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Ausleihen</title>
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

										
									
									
									
									
							
				
			