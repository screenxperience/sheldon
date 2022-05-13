<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');

$app_dst = 'Einsfltl1';
$app_plz = '24106';
$app_location = 'Kiel';

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
	$sql->query('SET NAMES UTF8');

	if(!empty($_GET))
	{
		if(empty($_GET['category']) || empty($_GET['id']))
		{
			$output .= '<div class="container">';
			$output .= '<div class="content-center container white">';
			$output .= '<h1>Error</h1>';
			$output .= '<div class="panel dark">';
			$output .= '<p>Es wurden nicht alle Daten gesendet.</p>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		}
		else
		{
			if(preg_match('/[^a-z]/',$_GET['category']) == 0)
			{
				$allowed_category = array('lend');

				if(in_array($_GET['category'],$allowed_category))
				{
					if(preg_match('/[^0-9]/',$_GET['id']) == 0)
					{
						if($_GET['category'] == $allowed_category[0])
						{
							$query = sprintf("
							SELECT rank_name_short,user_name
							FROM user
							INNER JOIN rank ON rank_id = user_rank_id
							WHERE user_id = '%s';",
							$sql->real_escape_string($_SESSION['user']['id']));
							
							$result = $sql->query($query);
							
							if($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								$date = date('d.m.Y',strtotime('now'));
								
								$output .= '<div class="pdf-a4">';
								$output .= '<table>';
								$output .= '<tr>';
								$output .= '<td class="border border-bl">';
								$output .= '&nbsp;Dienststelle<br>&nbsp;'.$app_dst;
								$output .= '</td>';
								$output .= '<td class="border border-bl">';
								$output .= '&nbsp;Ort, Datum<br>&nbsp;'.$app_plz.' '.$app_location.', '.$date;
								$output .= '</td>';
								$output .= '</tr>';
								$output .= '</table>';
								$output .= '<table>';
								$output .= '<tr>';
								$output .= '<td class="border border-bl">';
								$output .= '&nbsp;Bearbeiter<br>&nbsp;'.$row['rank_name_short'].' '.$row['user_name'];
								$output .= '</td>';
								$output .= '</tr>';
								$output .= '</table>';
								$output .= '<div class="text-center">';
								$output .= '<h2><u>&Uuml;bergabe von IT-Material</u></h2>';
								$output .= '</div>';
								
								$query = sprintf("
								SELECT rank_name_short,user_name,building_name,floor_name,room_name
								FROM user
								INNER JOIN rank ON rank_id = user_rank_id
								INNER JOIN building ON building_id = user_building_id
								INNER JOIN floor ON floor_id = user_floor_id
								INNER JOIN room ON room_id = user_room_id
								WHERE user_id = (
								SELECT lend_user_id
								FROM lend
								WHERE lend_id = '%s');",
								$sql->real_escape_string($_GET['id']));
								
								$result = $sql->query($query);
								
								if($row = $result->fetch_array(MYSQLI_ASSOC))
								{
									$output .= '<table>';
									$output .= '<tr>';
									$output .= '<td class="border border-bl">';
									$output .= '&nbsp;&Uuml;bernehmender<br>&nbsp;'.$row['rank_name_short'].' '.$row['user_name'];
									$output .= '</td>';
									$output .= '<td class="border border-bl">';
									$output .= '&nbsp;Lokation<br>&nbsp;'.$row['building_name'].' / '.$row['floor_name'].' / '.$row['room_name'];
									$output .= '</td>';
									$output .= '</tr>';
									$output .= '</table>';
									
									$query = sprintf("
									SELECT lend_assets,lend_archived_assets
									FROM lend
									WHERE lend_id = '%s';",
									$sql->real_escape_string($_GET['id']));
									
									$result = $sql->query($query);
									
									if($row = $result->fetch_array(MYSQLI_ASSOC))
									{
										$output .= '<table>';
										$output .= '<tr>';
										$output .= '<td class="border border-l">';
										$output .= '&nbsp;Beschreibung<br/><br/>';
										$output .= '</td>';
										$output .= '<td class="border border-l">';
										$output .= '&nbsp;Seriennummer<br/><br/>';
										$output .= '</td>';
										$output .= '</tr>';
										$output .= '</table>';
										
										$lend_assets = json_decode($row['lend_assets']);
										
										$lend_archived_assets = json_decode($row['lend_archived_assets']);
										
										for($i = 0; $i < count($lend_assets); $i++)
										{
											$lend_asset_id = $lend_assets[$i];
											
											$query = sprintf("
											SELECT type_name,vendor_name,model_name,asset_serial
											FROM asset
											INNER JOIN type ON type_id = asset_type_id
											INNER JOIN vendor ON vendor_id = asset_vendor_id
											INNER JOIN model ON model_id = asset_model_id
											WHERE asset_id = '%s';",
											$sql->real_escape_string($lend_asset_id));
											
											$result = $sql->query($query);
											
											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$output .= '<table>';
												$output .= '<tr>';
												$output .= '<td class="border border-l">';
												$output .= '&nbsp;'.$row['type_name'].' '.$row['vendor_name'].' '.$row['model_name'];
												$output .= '</td>';
												$output .= '<td class="border border-l">';
												$output .= '&nbsp;'.$row['asset_serial'];
												$output .= '</td>';
												$output .= '</tr>';
												$output .= '</table>';
											}
										}
										
										for($i = 0; $i < count($lend_archived_assets); $i++)
										{
											$lend_archived_asset = $lend_archived_assets[$i];
											
											$lend_archived_asset_id = $lend_archived_asset[0];
											
											$query = sprintf("
											SELECT type_name,vendor_name,model_name,asset_serial
											FROM asset
											INNER JOIN type ON type_id = asset_type_id
											INNER JOIN vendor ON vendor_id = asset_vendor_id
											INNER JOIN model ON model_id = asset_model_id
											WHERE asset_id = '%s';",
											$sql->real_escape_string($lend_archived_asset_id));
											
											$result = $sql->query($query);
											
											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$output .= '<tabl>';
												$output .= '<tr>';
												$output .= '<td class="border border-l">';
												$output .= '&nbsp;'.$row['type_name'].' '.$row['vendor_name'].' '.$row['model_name'];
												$output .= '</td>';
												$output .= '<td class="border border-l">';
												$output .= '&nbsp;'.$row['asset_serial'];
												$output .= '</td>';
												$output .= '</tr>';
												$output .= '</table>';
											}
										}		
									}
								}
								
								$output .= '</div>';
							}
						}
					}
				}
			}
		}
	}
}
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Print</title>
		<?php
		require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
		?>
	</head>
	<body class="dark-grey">
	<?php
	if(!empty($output))
	{
		echo $output;
	}
	?>
	</body>
</html>

								
								
								
								
								
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							