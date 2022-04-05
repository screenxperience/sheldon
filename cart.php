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
	$sql->query('SET NAMES UTF8');
		
	if(!empty($_GET))
	{
		if(empty($_GET['aktion']))
		{
			$output .= '<div class="container">';
			$output .= '<div class="content-center container white">';
			$output .= '<h1>Error</h1>';
			$output .= '<div class="panel dark">';
			$output .= '<p>Es konnte keine Aktion ausgef&uuml;hrt werden.</p>';
			$output .= '</div>'; 
			$output .= '</div>'; 
			$output .= '</div>'; 
		}
		else
		{
			if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['aktion']) == 0)
			{
				$allowed_aktions = array('add','del','view');
					
				if(in_array($_GET['aktion'],$allowed_aktions))
				{
					if($_GET['aktion'] == $allowed_aktions[0] || $_GET['aktion'] == $allowed_aktions[1])
					{
						if(empty($_GET['category']))
						{
							$output .= '<div class="container">';
							$output .= '<div class="content-center container white">';
							$output .= '<h1>Error</h1>';
							$output .= '<div class="panel dark">';
							$output .= '<p>Es wurde keine Kategorie gesendet.</p>';
							$output .= '</div>'; 
							$output .= '</div>'; 
							$output .= '</div>';
						}
						else
						{
							if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['category']) == 0)
							{
								$allowed_category = array('asset','user');

								if(in_array($_GET['category'],$allowed_category))
								{
									if($_GET['aktion'] == $allowed_aktions[0])
									{
										if(empty($_GET['id']))
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white">';
											$output .= '<h1>Error</h1>';
											$output .= '<div class="panel dark">';
											$output .= '<p>Es wurde keine ID gesendet.</p>';
											$output .= '</div>'; 
											$output .= '</div>'; 
											$output .= '</div>';
										}
										else
										{
											if(preg_match('/[^'.$app_regex['number'].']/',$_GET['id']) == 0)
											{
												if($_GET['category'] == $allowed_category[0])
												{
													$cart = $_SESSION['cart']['assets'];
														
													$query = sprintf("
													SELECT asset_serial
													FROM asset
													WHERE asset_id = '%s';",
													$sql->real_escape_string($_GET['id']));
														
													$result = $sql->query($query);
														
													if($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$exit = 0;
															
														if(!empty($cart))
														{
															for($i = 0; $i < count($cart); $i++)
															{
																$asset_id = $cart[$i];
																	
																if($asset_id == $_GET['id'])
																{
																	$exit = 1;
																}
															}
														}
															
														if($exit == 0)
														{
															array_push($cart,$_GET['id']);		
																	
															$output .= '<div class="container">';
															$output .= '<div class="content-center container white">';
															$output .= '<div class="panel dark">';
															$output .= '<p><strong>'.$row['asset_serial'].'</strong> wurde in ihren Warenkorb gelegt.</p>';
															$output .= '</div>'; 
															$output .= '</div>'; 
															$output .= '</div>';
														}
														else if($exit == 1)
														{
															$output .= '<div class="container">';
															$output .= '<div class="content-center container white">';
															$output .= '<h1>Error</h1>';
															$output .= '<div class="panel dark">';
															$output .= '<p><strong>'.$row['asset_serial'].'</strong> befindet sich bereits in ihrem Warenkorb.</p>';
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
														$output .= '<p>Es wurde kein Asset gefunden.</p>';
														$output .= '</div>'; 
														$output .= '</div>'; 
														$output .= '</div>';
													}
														
													$_SESSION['cart']['assets'] = $cart;
														
													$returnto = 'http://'.$_SERVER['HTTP_HOST'].'/list.php?category=asset&site=0&site_amount=5';
												}
												else if($_GET['category'] == $allowed_category[1])
												{
													$cart = $_SESSION['cart']['user'];
														
													$query = sprintf("
													SELECT user_vname,user_name
													FROM user
													WHERE user_id = '%s'",
													$sql->real_escape_string($_GET['id']));
														
													$result = $sql->query($query);
														
													if($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$cart = $_GET['id'];
															
														$output .= '<div class="container">';
														$output .= '<div class="content-center container white">';
														$output .= '<div class="panel dark">';
														$output .= '<p><strong>'.$row['user_name'].', '.$row['user_vname'].'</strong> wurde in ihren Warenkorb gelegt.</p>';
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
														$output .= '<p>Es wurde kein User gefunden.</p>';
														$output .= '</div>'; 
														$output .= '</div>'; 
														$output .= '</div>';
													}
														
													$_SESSION['cart']['user'] = $cart;
														
													$returnto = 'http://'.$_SERVER['HTTP_HOST'].'/list.php?category=user&site=0&site_amount=5';
												}
											}
											else
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white">';
												$output .= '<h1>Error</h1>';
												$output .= '<div class="panel dark">';
												$output .= '<p>Die ID besteht nur aus Zahlen.</p>';
												$output .= '</div>'; 
												$output .= '</div>'; 
												$output .= '</div>';
											}
										}
											
										$cart_count = count($_SESSION['cart']['assets']);
									}
									else if($_GET['aktion'] == $allowed_aktions[1])
									{
										if($_GET['category'] == $allowed_category[0])
										{
											if($_GET['id'] == '')
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white">';
												$output .= '<h1>Error</h1>';
												$output .= '<div class="panel dark">';
												$output .= '<p>Es wurde keine ID gesendet.</p>';
												$output .= '</div>'; 
												$output .= '</div>'; 
												$output .= '</div>';
											}
											else
											{
												if(preg_match('/[^'.$app_regex['number'].']/',$_GET['id']) == 0)
												{
													$assets = $_SESSION['cart']['assets'];
														
													$key = $_GET['id'];
														
													if(!empty($assets[$key]))
													{
														$query = sprintf("
														SELECT asset_serial
														FROM asset
														WHERE asset_id = '%s';",
														$sql->real_escape_string($assets[$key]));
															
														$result = $sql->query($query);
															
														if($row = $result->fetch_array(MYSQLI_ASSOC))
														{
															unset($assets[$key]);
																
															if(!empty($assets))
															{
																$assets_new = array();
															
																foreach($assets as $asset)
																{
																	array_push($assets_new,$asset);
																}
																	
																$assets = $assets_new;
																	
																$returnto = 'http://'.$_SERVER['HTTP_HOST'].'/cart.php?aktion=view';
															}
															else
															{
																$returnto = 'http://'.$_SERVER['HTTP_HOST'].'/list.php?category=asset&site=0&site_amount=5';
															}
															
															$_SESSION['cart']['assets'] = $assets;
															
															$output .= '<div class="container">';
															$output .= '<div class="content-center container white">';
															$output .= '<div class="panel dark">';
															$output .= '<p><strong>'.$row['asset_serial'].'</strong> wurde aus ihrem Warenkorb entfernt.</p>';
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
														$output .= '<p>Die gesendete ID ist nicht vorhanden.</p>';
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
													$output .= '<p>Die ID besteht nur aus Zahlen.</p>';
													$output .= '</div>'; 
													$output .= '</div>'; 
													$output .= '</div>';
												}
											}
										}
										else if($_GET['category'] == $allowed_category[1])
										{
											$user_id = $_SESSION['cart']['user'];
												
											if(empty($user_id))
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white">';
												$output .= '<h1>Error</h1>';
												$output .= '<div class="panel dark">';
												$output .= '<p>Sie haben noch keinen User gew&auml;hlt.</p>';
												$output .= '</div>'; 
												$output .= '</div>'; 
												$output .= '</div>';
											}
											else
											{
												$query = sprintf("
												SELECT user_vname,user_name
												FROM user
												WHERE user_id = '%s';",
												$sql->real_escape_string($user_id));
													
												$result = $sql->query($query);
													
												if($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$_SESSION['cart']['user'] = '';
														
													$output .= '<div class="container">';
													$output .= '<div class="content-center container white">';
													$output .= '<div class="panel dark">';
													$output .= '<p><strong>'.$row['user_name'].', '.$row['user_vname'].'</strong> wurde aus dem Warenkorb entfernt.</p>';
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
													$output .= '<p>Es wurde kein User gefunden.</p>';
													$output .= '</div>'; 
													$output .= '</div>'; 
													$output .= '</div>';
												}
											}
												
											$returnto = 'http://'.$_SERVER['HTTP_HOST'].'/list.php?category=user&site=0&site_amount=5';
										}
											
										$cart_count = count($_SESSION['cart']['assets']);
									}
								}
								else
								{
									$output .= '<div class="container">';
									$output .= '<div class="content-center container white">';
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel dark">';
									$output .= '<p>Die gesendete Kategorie kann nicht bearbeitet werden.</p>';
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
								$output .= '<p>Die gesendete Kategorie kann nicht bearbeitet werden.</p>';
								$output .= '</div>'; 
								$output .= '</div>'; 
								$output .= '</div>';
							}
						}
					}
					else if($_GET['aktion'] == $allowed_aktions[2])
					{
						$user_id = $_SESSION['cart']['user'];
										
						$assets = $_SESSION['cart']['assets'];
							
						if(empty($user_id) || empty($assets))
						{
							$output .= '<div class="container">';
							$output .= '<div class="content-center container white">';
							$output .= '<h1>Error</h1>';
							$output .= '<div class="panel dark">';
								
							if(empty($user_id) && empty($assets))
							{
								$output .= '<p>Ihr Warenkorb ist leer.</p>';
							}
							else if(empty($user_id))
							{
								$output .= '<p>Sie haben noch keinen User gew&auml;hlt.</p>';
							}
							else if(empty($assets))
							{
								$output .= '<p>Sie haben noch keine Assets gew&auml;hlt.</p>';
							}
								
							$output .= '</div>';
							$output .= '</div>';
							$output .= '</div>';
						}
						else
						{
							$query = sprintf("
							SELECT user_name,user_vname
							FROM user
							WHERE user_id = '%s';",
							$sql->real_escape_string($user_id));
								
							$result = $sql->query($query);
								
							if($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								$output .= '<div class="container">';
								$output .= '<div class="panel white">';
								$output .= '<h1>Ihr Warenkorb</h1>';
								$output .= '<h2>User</h2>';
								$output .= '<div class="panel dark display">';
								$output .= '<p>'.$user_id.'</p>';
								$output .= '<p>'.$row['user_name'].', '.$row['user_vname'].'</p>';
								$output .= '<div class="container display-middle-right">';
								$output .= '<a class="btn-default light-blue" href="cart.php?aktion=del&category=user"><i class="fas fa-times"></i></a>';
								$output .= '</div>';
								$output .= '</div>';
									
								$output .= '<h2>Assets</h2>';
									
								for($i = 0; $i < count($assets); $i++)
								{
									$asset_id = $assets[$i];
											
									$query = sprintf("
									SELECT type_name,vendor_name,model_name,asset_serial
									FROM asset
									INNER JOIN type ON type_id = asset_type_id
									INNER JOIN vendor ON vendor_id = asset_vendor_id
									INNER JOIN model ON model_id = asset_model_id
									WHERE asset_id = '%s';",
									$sql->real_escape_string($asset_id));
											
									$result = $sql->query($query);
											
									if($row = $result->fetch_array(MYSQLI_ASSOC))
									{
										$output .= '<div class="panel dark display">';
										$output .= '<p>'.$row['type_name'].' / '.$row['vendor_name'].' / '.$row['model_name'].'</p>';
										$output .= '<p>'.$row['asset_serial'].'</p>';
										$output .= '<div class="container display-middle-right">';
										$output .= '<a class="btn-default light-blue" href="cart.php?aktion=del&category=asset&id='.$i.'"><i class="fas fa-times"></i></a>';
										$output .= '</div>';
										$output .= '</div>';
									}
								}
									
								$date_min = date('Y-m-d',strtotime('now')+60*60*24);
									
								$date_max = date('Y-m-d',strtotime('now')+60*60*24*365);
									
								$output .= '<form action="lend.php" method="get">';
								$output .= '<h2>Leihgabe bis</h2>';
								$output .= '<input type="hidden" name="aktion" value="add"/>';
								$output .= '<ul class="flex section">';
								$output .= '<li class="col-s10 col-m10 col-l10">';
								$output .= '<input class="ipt-default" name="lend_end" type="date" value="'.$date_min.'" min="'.$date_min.'" max="'.$date_max.'"/></p>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-arrow-right"></i></button>';
								$output .= '</li>';
								$output .= '</ul>';
								$output .= '</form>';
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel dark">';
								$output .= '<p>Ihr Warenkorb kann nicht angezeigt werden.</p>';
								$output .= '</div>'; 
								$output .= '</div>'; 
								$output .= '</div>';
							}
						}
					}
				}
				else
				{
					$output .= '<div class="container">';
					$output .= '<div class="content-center container white">';
					$output .= '<h1>Error</h1>';
					$output .= '<div class="panel dark">';
					$output .= '<p>Die gesendete Aktion kann nicht bearbeitet werden.</p>';
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
				$output .= '<p>Die gesendete Aktion kann nicht bearbeitet werden.</p>';
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
	
if(!empty($returnto))
{
	$output .= '<script>'."ch_location('".$returnto."'".',2);</script>';
}
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Warenkorb</title>
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