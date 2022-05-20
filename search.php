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
							OR lend_user_id = '".$personalnr."'
							OR lend_user_id = (
							SELECT user_id
							FROM user
							WHERE user_vname = '".$vname."')
							OR lend_user_id = (
							SELECT user_id
							FROM user
							WHERE user_name = '".$name."')";
							
							if(!empty($serialnr))
							{
								$subquery = sprintf("
								SELECT asset_id
								FROM asset
								WHERE asset_serial = '%s';",
								$sql->real_escape_string($serialnr));
									
								$result = $sql->query($subquery);
									
								if($row = $result->fetch_array(MYSQLI_ASSOC))
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
									$output .= '<h1>Leihgaben suchen ( '.$amount_gs.' )</h1>';
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
								
							$serialnr = $filter[4];

							$query = sprintf("
							SELECT asset_id,type_name,vendor_name,model_name,asset_serial
							FROM asset
							INNER JOIN type ON type_id = asset_type_id
							INNER JOIN vendor ON vendor_id = asset_vendor_id
							INNER JOIN model ON model_id = asset_model_id
							WHERE asset_type_id = '%s'
							OR asset_vendor_id = '%s'
							OR asset_model_id = '%s'
							OR asset_serial LIKE '%s';",
							$sql->real_escape_string($type_id),
							$sql->real_escape_string($vendor_id),
							$sql->real_escape_string($model_id),
							$sql->real_escape_string('%'.$serialnr.'%'));

							$result = $sql->query($query);

							while($row = $result->fetch_array(MYSQLI_ASSOC))
							{
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
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="del.php?category='.$_GET['category'].'&id='.$row['asset_id'].'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/list.php?category='.$_GET['category'].'&site='.$_GET['site'].'&amount='.$_GET['amount']).'"><i class="fas fa-trash"></i></a> ';
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$row['asset_id'].'&tab=general"><i class="fas fa-eye"></i></a> ';
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="cart.php?aktion=add&category='.$_GET['category'].'&id='.$row['asset_id'].'"><i class="fas fa-shopping-cart"></i> <i class="fas fa-plus"></i></a>';
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
