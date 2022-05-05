<?php
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
	$sql->query('SET NAMES UTF8');

	if(!empty($_GET['returnto']))
	{
		if(preg_match('/[^a-zA-Z0-9\?\&\=\.\:\/\_]/',$_GET['returnto']) == 0)
		{
			$host = parse_url($_GET['returnto'],PHP_URL_HOST);

			if($host == $_SERVER['HTTP_HOST'])
			{
				$returnto = $_GET['returnto'];
			}
			else
			{
				$returnto = 'index.php';
			}
		}
		else
		{
			$returnto = 'index.php';
		}
	}
	else
	{
		$returnto = 'index.php';
	}

	if(!empty($_GET))
	{
		if(empty($_GET['category']) || empty($_GET['id']))
		{
			$output .= '<div class="container">';
			$output .= '<div class="content-center container white-alpha">';
			$output .= '<h1>Error</h1>';
			$output .= '<div class="panel black-alpha">';
			$output .= '<p>Es wurden nicht alle Daten gesendet.</p>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		}
		else
		{
			if(preg_match('/[^a-z]/',$_GET['category']) == 0)
			{
				$allowed_category = array('asset','user','ci','vendor','model','type','building','floor','room','cis');

				if(in_array($_GET['category'],$allowed_category))
				{
					if(preg_match('/[^0-9]/',$_GET['id']) == 0)
					{
						if($_GET['category'] == $allowed_category[0])
						{
							$exit = 0;

							$query = "
							SELECT lend_assets
							FROM lend";

							$result = $sql->query($query);

							while($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								$lend_assets = json_decode($row['lend_assets']);

								if(in_array($_GET['id'],$lend_assets))
								{
									$exit = 1;

									break;
								}
							}

							if(!empty($exit))
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Info</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Datensatz konnte nicht gel&ouml;scht werden.</p>';
								$output .= '<p>Asset wurde bereits ausgeliehen.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
							else
							{
								$query = sprintf("
								DELETE FROM asset
								WHERE asset_id = '%s';",
								$sql->real_escape_string($_GET['id']));

								$sql->query($query);

								if($sql->affected_rows == 1)
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Datensatz wurde gel&ouml;scht.</p>';
									$output .= '</div>';
									$output .= '</div>';
									$output .= '</div>';
								}
								else
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Datensatz konnte nicht gel&ouml;scht werden.</p>';
									$output .= '<p>Kein Asset mit der gesendeten ID vorhanden.</p>';
									$output .= '</div>';
									$output .= '</div>';
									$output .= '</div>';
								}
							}
						}
						else if($_GET['category'] == $allowed_category[1])
						{
							$query = sprintf("
							DELETE FROM user
							WHERE user_id = '%s';",
							$sql->real_escape_string($_GET['id']));

							$sql->query($query);

							if($sql->affected_rows == 1)
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Datensatz wurde gel&ouml;scht.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Datensatz konnte nicht gel&ouml;scht werden.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
						}
						else if($_GET['category'] == $allowed_category[2])
						{
							$exit = 0;

							$query = "
							SELECT asset_cis
							FROM asset";

							$result = $sql->query($query);

							while($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								$asset_cis = json_decode($row['asset_cis']);

								for($i = 0; $i < count($asset_cis); $i++)
								{
									$asset_ci = $asset_cis[$i];

									$ci_id = $asset_ci[0];

									if($ci_id == $_GET['id'])
									{
										$exit = 1;

										break 2;
									}
								}
							}

							if(!empty($exit))
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Datensatz konnte nicht gel&ouml;scht werden.</p>';
								$output .= '<p>CI ist noch mit Assets verkn&uuml;pft.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
							else
							{
								$query = sprintf("
								DELETE FROM ci
								WHERE ci_id = %s;",
								$sql->real_escape_string($_GET['id']));

								$sql->query($query);

								if($sql->affected_rows == 1)
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Datensatz wurde gel&ouml;scht.</p>';
									$output .= '</div>';
									$output .= '</div>';
									$output .= '</div>';
								}
								else
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Datensatz konnte nicht gel&ouml;scht werden.</p>';
									$output .= '<p>Kein CI mit der gesendeten ID vorhanden.</p>';
									$output .= '</div>';
									$output .= '</div>';
									$output .= '</div>';
								}
							}
						}
						else if($_GET['category'] == $allowed_category[3] || $_GET['category'] == $allowed_category[4] || $_GET['category'] == $allowed_category[5])
						{
							$query = sprintf("
							DELETE FROM %s
							WHERE %s_id = %s;",
							$sql->real_escape_string($_GET['category']),
							$sql->real_escape_string($_GET['category']),
							$sql->real_escape_string($_GET['id']));

							$sql->query($query);

							if($sql->affected_rows == 1)
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Datensatz wurde gel&ouml;scht.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Datensatz konnte nicht gel&ouml;scht werden.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
						}
						else if($_GET['category'] == $allowed_category[6] || $_GET['category'] == $allowed_category[7] || $_GET['category'] == $allowed_category[8])
						{
							if($_GET['id'] != 1)
							{
								$query = sprintf("
								DELETE FROM %s
								WHERE %s_id = %s;",
								$sql->real_escape_string($_GET['category']),
								$sql->real_escape_string($_GET['category']),
								$sql->real_escape_string($_GET['id']));

								$sql->query($query);

								if($sql->affected_rows == 1)
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Datensatz wurde gel&ouml;scht.</p>';
									$output .= '</div>';
									$output .= '</div>';
									$output .= '</div>';
								}
								else
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Datensatz konnte nicht gel&ouml;scht werden.</p>';
									$output .= '<p>Kein Datensatz mit der gesendeten ID vorhanden.</p>';
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
								$output .= '<div class="panel red">';
								$output .= '<p>Standartwert darf nicht entfernt werden.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
						}
						else if($_GET['category'] == $allowed_category[9])
						{
							if(preg_match('/[^0-9]/',$_GET['ci_id']) == 0)
							{
								$query = sprintf("
								SELECT asset_cis
								FROM asset
								WHERE asset_id = '%s';",
								$sql->real_escape_string($_GET['id']));

								$result = $sql->query($query);

								if($row = $result->fetch_array(MYSQLI_ASSOC))
								{
									$asset_cis = json_decode($row['asset_cis']);

									if(array_key_exists($_GET['ci_id'],$asset_cis))
									{
										$asset_cis_new = array();

										unset($asset_cis[$_GET['ci_id']]);

										foreach($asset_cis as $asset_ci)
										{
											array_push($asset_cis_new,$asset_ci);
										}

										$asset_cis = $asset_cis_new;

										$query = sprintf("
										UPDATE asset
										SET asset_cis = '%s'
										WHERE asset_id = '%s';",
										$sql->real_escape_string(json_encode($asset_cis)),
										$sql->real_escape_string($_GET['id']));

										$sql->query($query);

										if($sql->affected_rows == 1)
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white-alpha">';
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>CI wurde entfernt.</p>';
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
										$output .= '<p>CI-Index ist nicht vorhanden.</p>';
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
									$output .= '<p>Es wurde kein Asset gefunden.</p>';
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
								$output .= '<p>Die CI-ID besteht nur aus Zahlen.</p>';
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
						$output .= '<p>Die ID besteht nur aus Zahlen.</p>';
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
					$output .= '<p>Die gesendete Kategorie kann nicht bearbeitet werden.</p>';
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
				$output .= '<p>Die gesendete Kategorie kann nicht bearbeitet werden.</p>';
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

	if(!empty($returnto))
	{
		$output .= '<script>'."ch_location('".$returnto."'".',2);</script>';
	}
}
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Del</title>
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
