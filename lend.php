<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');
	
$output = '';

$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);

if(!$sql)
{
	$output .= '<div class="container">';
	$output .= '<div class="content-center container white-alpha">';
	$output .= '<h1>Error</h1>';
	$output .= '<div class="panel black-alpha">';
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
			$output .= '<div class="content-center container white-alpha">';
			$output .= '<h1>Error</h1>';
			$output .= '<div class="panel black-alpha">';
			$output .= '<p>Es konnte keine Aktion durchgef&uuml;hrt werden.</p>';
			$output .= '</div>'; 
			$output .= '</div>'; 
			$output .= '</div>'; 
		}
		else
		{
			if(preg_match('/[^a-z]/',$_GET['aktion']) == 0)
			{
				$allowed_aktions = array('add','print');
				
				if(in_array($_GET['aktion'],$allowed_aktions))
				{
					if($_GET['aktion'] == $allowed_aktions[0])
					{
						$lend_user_id = $_SESSION['cart']['user'];
						
						$lend_assets = $_SESSION['cart']['assets'];
						
						if(empty($lend_user_id) || empty($lend_assets) || empty($_GET['lend_end']))
						{
							$output .= '<div class="container">';
							$output .= '<div class="content-center container white-alpha">';
							$output .= '<h1>Error</h1>';
							$output .= '<div class="panel black-alpha">';
							$output .= '<p>Es konnte keine Leihgabe erzeugt werden.</p>';
							$output .= '</div>'; 
							$output .= '</div>'; 
							$output .= '</div>';
						}
						else
						{
							$exit = 0;
							
							if(!empty($_GET['lend_description']))
							{
								if(preg_match('/[^a-zA-Z0-9öäüÖÄÜß\s\-\.\r\n]/',$_GET['lend_description']) == 0)
								{
									$exit = 0;
									
									if(strlen($_GET['lend_description']) <= 200)
									{
										$exit = 0;
										
										$lend_description = $_GET['lend_description'];
									}
									else
									{
										$exit = 1;
									}
								}
								else
								{
									$exit = 1;
								}
							}
							else
							{
								$lend_description = '-';
							}
							
							if(!$exit)
							{				
								preg_match('/^[0-9]{4}+\-{1}+[0-9]{2}+\-{1}+[0-9]{2}$/',$_GET['lend_end'],$date_matches);
								
								if(!empty($date_matches))
								{
									$date_parts = explode('-',$_GET['lend_end']);
									
									if(checkdate($date_parts[1],$date_parts[2],$date_parts[0]))
									{
										$date = strtotime($_GET['lend_end']);
										
										$date_min = strtotime('now')+60*60*24;
									
										$date_max = strtotime('now')+60*60*24*365;
										
										if($date >= $date_min || $date <= $date_max)
										{
											$query = sprintf("
											INSERT INTO
											lend
											(lend_creator_id,lend_user_id,lend_assets,lend_archived_assets,lend_description,lend_end)
											VALUES
											('%s','%s','%s','%s','%s','%s');",
											$sql->real_escape_string($_SESSION['user']['id']),
											$sql->real_escape_string($lend_user_id),
											$sql->real_escape_string(json_encode($lend_assets)),
											$sql->real_escape_string(json_encode(array())),
											$sql->real_escape_string($lend_description),
											$sql->real_escape_string($_GET['lend_end']));
										
											$sql->query($query);
										
											if($sql->affected_rows == 1)
											{
												for($i = 0; $i < count($lend_assets); $i++)
												{
													$query = sprintf("
													UPDATE asset
													SET asset_building_id = (
													SELECT user_building_id FROM user
													WHERE user_id = '%s'),
													asset_floor_id = (
													SELECT user_floor_id FROM user
													WHERE user_id = '%s'),
													asset_room_id = (
													SELECT user_room_id FROM
													user WHERE user_id = '%s')
													WHERE asset_id = '%s';",
													$sql->real_escape_string($lend_user_id),
													$sql->real_escape_string($lend_user_id),
													$sql->real_escape_string($lend_user_id),
													$sql->real_escape_string($lend_assets[$i]));
												
													$sql->query($query);
												}
											
												$_SESSION['cart']['user'] = '';
											
												$_SESSION['cart']['assets'] = array();
											
												$cart_count = 0;
											
												$query = sprintf("
												SELECT lend_id
												FROM lend
												WHERE lend_creator_id = '%s'
												ORDER BY lend_id DESC
												LIMIT 1;",
												$sql->real_escape_string($_SESSION['user']['id']));
												
												$result = $sql->query($query);
												
												if($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$output .= '<div class="container">';
													$output .= '<div class="content-center container white-alpha" style="max-width:600px;">';
													$output .= '<table>';
													$output .= '<tr>';
													$output .= '<td><h2>Auftrag erfolgreich</h2></td>';
													$output .= '<td>';
													$output .= '&nbsp;&nbsp;&nbsp;<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="lend.php?aktion=print&id='.$row['lend_id'].'"><i class="fas fa-print"></i></a> ';
													$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category=lend&id='.$row['lend_id'].'"><i class="fas fa-eye"></i></a>';
													$output .= '</td>';
													$output .= '</tr>';
													$output .= '</table>';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Leihgabe wurde mit der Nummer '.$row['lend_id'].' im System erfasst.</p>';
													$output .= '</div>'; 
													$output .= '</div>'; 
													$output .= '</div>';
												}
											}
											else
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<h1>Error</h1>';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Es konnte kein Eintrag erzeugt werden.</p>';
												$output .= '</div>'; 
												$output .= '</div>'; 
												$output .= '</div>'; 
											}
										}
										else
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white-alpha">';
											$output .= '<h1>Error</h1>';
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>Das Datum liegt nicht in der vorgegebenen Zeitspanne.</p>';
											$output .= '</div>'; 
											$output .= '</div>'; 
											$output .= '</div>';
										}
									}
									else
									{
										$output .= '<div class="container">';
										$output .= '<div class="content-center container white-alpha">';
										$output .= '<h1>Error</h1>';
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Das eingegebene Datum existiert nicht.</p>';
										$output .= '</div>'; 
										$output .= '</div>'; 
										$output .= '</div>'; 
									}
								}
								else
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>W&auml;hlen Sie ein Datum aus dem Date-Picker.</p>';
									$output .= '</div>'; 
									$output .= '</div>'; 
									$output .= '</div>';
								}
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Die Beschreibung darf nur 200 Zeichen lang sein und nur folgende Zeichen enthalten: a-z, A-Z, 0-9, öäüÖÄÜß-.</p>';
								$output .= '</div>'; 
								$output .= '</div>'; 
								$output .= '</div>';
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
					$output .= '<div class="content-center container white-alpha">';
					$output .= '<h1>Error</h1>';
					$output .= '<div class="panel black-alpha">';
					$output .= '<p>Es konnte keine Aktion durchgef&uuml;hrt werden.</p>';
					$output .= '</div>'; 
					$output .= '</div>'; 
					$output .= '</div>';
				}
			}
			else
			{
				$output .= '<div class="container">';
				$output .= '<div class="content-center container white-alpha">';
				$output .= '<h1>Error</h1>';
				$output .= '<div class="panel black-alpha">';
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
		$output .= '<div class="content-center container white-alpha">';
		$output .= '<h1>Error</h1>';
		$output .= '<div class="panel black-alpha">';
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