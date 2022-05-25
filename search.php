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
	$sql->query('SET NAMES UTF8');

	if(!empty($_GET))
	{
		$filter = $_GET['filter'];

		$filter_str = implode("",$filter);

		if(empty($_GET['category']) || empty($filter_str))
		{
			$output .= '<div class="container">';
			$output .= '<div class="content-center container white-alpha">';
			$output .= '<h1>Error</h1>';
			$output .= '<div class="panel black-alpha">';
			$output .= '<p>Es konnte keine Suche durchgeführt werden.</p>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		}
		else
		{
			if(preg_match('/[^a-z]/',$_GET['category']) == 0)
			{
				$allowed_category = array('lend','asset','user');
				
				if(in_array($_GET['category'],$allowed_category))
				{
					$exit = 0;

					for($i = 0; $i < count($filter); $i++)
					{
						if(preg_match('/[^a-zA-Z0-9öäüÖÄÜß\s\-\.]/',$filter[$i]) != 0)
						{
							$exit = 1;

							break;
						}
					}
						
					if(!$exit)
					{
						if($_GET['category'] == $allowed_category[0])
						{
							$documentnr = $filter[0]; 
								
							$personalnr = $filter[1]; 
								
							$vname = $filter[2]; 
								
							$name = $filter[3]; 
								
							$serialnr = $filter[4];

							$i = 0;

							$query = " 
							SELECT lend_id,user_name,user_vname,lend_user_id,lend_start
							FROM lend
							INNER JOIN user ON user_id = lend_user_id
							WHERE lend_id = '".$documentnr."'
							OR lend_user_id = '".$personalnr."'";
							
							if(!empty($vname))
							{
								$subquery = sprintf("
								SELECT user_id
								FROM user
								WHERE user_vname LIKE '%s';",
								$sql->real_escape_string('%'.$vname.'%'));

								$result = $sql->query($subquery);

								while($row = $result->fetch_array(MYSQLI_ASSOC))
								{
									$query .= " OR lend_user_id = '".$row['user_id']."'";
								}
							}
							
							if(!empty($name))
							{
								$subquery = sprintf("
								SELECT user_id
								FROM user
								WHERE user_name LIKE '%s';",
								$sql->real_escape_string('%'.$name.'%'));

								$result = $sql->query($subquery);

								while($row = $result->fetch_array(MYSQLI_ASSOC))
								{
									$query .= " OR lend_user_id = '".$row['user_id']."'";
								}
							}
							
							if(!empty($serialnr))
							{
								$subquery = sprintf("
								SELECT asset_id
								FROM asset
								WHERE asset_serial LIKE '%s';",
								$sql->real_escape_string('%'.$serialnr.'%'));
									
								$result = $sql->query($subquery);
									
								while($row = $result->fetch_array(MYSQLI_ASSOC))
								{
									$query .= " OR lend_assets LIKE '".'%"'.$row['asset_id'].'"%'."' OR lend_archived_assets LIKE '".'%"'.$row['asset_id'].'"%'."'";
								}
							}

							$result = $sql->query($query);

							$amount_gs = mysqli_num_rows($result);

							while($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								if($i == 0)
								{
									$output .= '<div class="container">';
									$output .= '<h1>Leihgaben ( '.$amount_gs.' )</h1>';
									$output .= '</div>';
									$output .= '<ul class="flex block">';
								}

								if($i == 2)
								{
									$output .= '</ul>';
									$output .= '<ul class="flex block">';

									$i = 0;
								}

								$lend_start = date('d.m.Y',strtotime($row['lend_start']));

								$output .= '<li class="col-s12 col-m6 col-l6">';
								$output .= '<div class="text-center-medium text-center-small margin container display-container black-alpha">';
								$output .= '<p>'.$row['lend_user_id'].'</p>';
								$output .= '<p>'.$row['user_name'].', '.$row['user_vname'].'</p>';
								$output .= '<p>verliehen am '.$lend_start.'</p>';
								$output .= '<div class="container-large display-middle-right-large section-medium section-small">';
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$row['lend_id'].'"><i class="fas fa-eye"></i></a>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</li>';

								$i++;
							}

							$output .= '</ul>';
						}
						else if($_GET['category'] == $allowed_category[1])
						{
							$type_id = $filter[0]; 
								
							$vendor_id = $filter[1]; 
								
							$model_id = $filter[2];
							
							$building_id = $filter[3];

							$floor_id = $filter[4];

							$room_id = $filter[5];
								
							$serialnr = $filter[6];

							$i = 0;
							
							$query = "
							SELECT asset_id,type_name,vendor_name,model_name,asset_serial,asset_locked
							FROM asset
							INNER JOIN type ON type_id = asset_type_id
							INNER JOIN vendor ON vendor_id = asset_vendor_id
							INNER JOIN model ON model_id = asset_model_id
							WHERE asset_type_id = '".$type_id."'
							OR asset_vendor_id = '".$vendor_id."'
							OR asset_model_id = '".$model_id."'
							OR asset_building_id = '".$building_id."'
							OR asset_floor_id = '".$floor_id."'
							OR asset_room_id = '".$room_id."'";

							if(!empty($serialnr))
							{
								$query .= " OR asset_serial LIKE '%".$serialnr."%'";
							}

							$result = $sql->query($query);

							$amount_gs = mysqli_num_rows($result);

							while($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								if($i == 0)
								{
									$output .= '<div class="container">';
									$output .= '<h1>Assets ( '.$amount_gs.' )</h1>';
									$output .= '</div>';
									$output .= '<ul class="flex block">';
								}

								if($i == 2)
								{
									$output .= '</ul>';
									$output .= '<ul class="flex block">';

									$i = 0;
								}

								$output .= '<li class="col-s12 col-m6 col-l6">';
								$output .= '<div class="text-center-medium text-center-small margin container display-container black-alpha">';
								$output .= '<p>'.$row['type_name'].' / '.$row['vendor_name'].' / '.$row['model_name'].'</p>';
								$output .= '<p>'.$row['asset_serial'].'</p>';
								$output .= '<div class="container-large display-middle-right-large section-medium section-small">';
								$output .= '<a class="btn-default border border-red red hover-white hover-text-red" href="del.php?category='.$_GET['category'].'&id='.$row['asset_id'].'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/search.php?category='.$_GET['category'].'&filter[]='.$filter[0].'&filter[]='.$filter[1].'&filter[]='.$filter[2].'&filter[]='.$filter[3].'&filter[]='.$filter[4].'&filter[]='.$filter[5].'&filter[]='.$filter[6]).'"><i class="fas fa-trash"></i></a> ';
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$row['asset_id'].'&tab=general"><i class="fas fa-eye"></i></a> ';

								if(!$row['asset_locked'])
								{
									$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="cart.php?aktion=add&category='.$_GET['category'].'&id='.$row['asset_id'].'"><i class="fas fa-shopping-cart"></i> <i class="fas fa-plus"></i></a>';
								}
								
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</li>';

								$i++;
							}

							$output .= '</ul>';
						}
						else if($_GET['category'] == $allowed_category[2])
						{
							$personalnr = $filter[0];

							$rank_id = $filter[1];

							$vname = $filter[2];

							$name = $filter[3];

							$email = $filter[4];

							$building_id = $filter[5];

							$floor_id = $filter[6];

							$room_id = $filter[7];

							$i = 0;
							
							$query = "
							SELECT user_id,user_email
							FROM user
							WHERE user_id = '".$personalnr."'
							OR user_rank_id = '".$rank_id."'";

							if(!empty($vname))
							{
								$query .= " OR user_vname LIKE '%".$vname."%'";
							}

							if(!empty($name))
							{
								$query .= " OR user_name LIKE '%".$name."%'";
							}

							if(!empty($email))
							{
								$query .= " OR user_email LIKE '%".$email."%'";
							}

							$query .= " OR user_building_id = '".$building_id."'
							OR user_floor_id = '".$floor_id."'
							OR user_room_id = '".$room_id."'";

							$result = $sql->query($query);

							$amount_gs = mysqli_num_rows($result);

							while($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								if($i == 0)
								{
									$output .= '<div class="container">';
									$output .= '<h1>User ( '.$amount_gs.' )</h1>';
									$output .= '</div>';
									$output .= '<ul class="flex block">';
								}

								if($i == 2)
								{
									$output .= '</ul>';
									$output .= '<ul class="flex block">';
									
									$i = 0;
								}

								$output .= '<li class="col-s12 col-m6 col-l6">';
								$output .= '<div class="text-center-medium text-center-small margin container display-container black-alpha">';
								$output .= '<p>'.$row['user_id'].'</p>';
								$output .= '<p>'.$row['user_email'].'</p>';
								$output .= '<div class="container-large display-middle-right-large section-medium section-small">';
								$output .= '<a class="btn-default border border-red red hover-white hover-text-red" href="del.php?category='.$_GET['category'].'&id='.$row['user_id'].'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/search.php?category='.$_GET['category'].'&filter[]='.$filter[0].'&filter[]='.$filter[1].'&filter[]='.$filter[2].'&filter[]='.$filter[3].'&filter[]='.$filter[4].'&filter[]='.$filter[5].'&filter[]='.$filter[6].'&filter[]='.$filter[7]).'"><i class="fas fa-trash"></i></a> ';
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$row['user_id'].'&tab=general"><i class="fas fa-eye"></i></a> ';
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="cart.php?aktion=add&category='.$_GET['category'].'&id='.$row['user_id'].'"><i class="fas fa-shopping-cart"></i> <i class="fas fa-plus"></i></a>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</li>';

								$i++;
							}

							$output .= '</ul>';
						}
					}
					else
					{
						$output .= '<div class="container">';
						$output .= '<div class="content-center container white-alpha">';
						$output .= '<h1>Error</h1>';
						$output .= '<div class="panel black-alpha">';
						$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r ihre Suche: a-zA-Z0-9öäüÖÄÜß-.</p>';
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
}
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Search</title>
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
