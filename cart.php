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
				$allowed_aktions = array('add','del','view');
					
				if(in_array($_GET['aktion'],$allowed_aktions))
				{
					if($_GET['aktion'] == $allowed_aktions[0] || $_GET['aktion'] == $allowed_aktions[1])
					{
						if(empty($_GET['category']))
						{
							$output .= '<div class="container">';
							$output .= '<div class="content-center container white-alpha">';
							$output .= '<h1>Error</h1>';
							$output .= '<div class="panel black-alpha">';
							$output .= '<p>Es wurde keine Kategorie gesendet.</p>';
							$output .= '</div>'; 
							$output .= '</div>'; 
							$output .= '</div>';
						}
						else
						{
							if(preg_match('/[^a-z]/',$_GET['category']) == 0)
							{
								$allowed_category = array('asset','user');

								if(in_array($_GET['category'],$allowed_category))
								{
									if($_GET['aktion'] == $allowed_aktions[0])
									{
										if(empty($_GET['id']))
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white-alpha">';
											$output .= '<h1>Error</h1>';
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>Es wurde keine ID gesendet.</p>';
											$output .= '</div>'; 
											$output .= '</div>'; 
											$output .= '</div>';
										}
										else
										{
											if(preg_match('/[^0-9]/',$_GET['id']) == 0)
											{
												if($_GET['category'] == $allowed_category[0])
												{
													$cart = $_SESSION['cart']['assets'];
														
													$query = "
													SELECT lend_id,lend_assets
													FROM lend
													WHERE lend_archived = '0'";

													$result = $sql->query($query);

													$amount_gs = mysqli_num_rows($result);

													if($amount_gs > 0)
													{
														while($row = $result->fetch_array(MYSQLI_ASSOC))
														{
															$lend_assets = json_decode($row['lend_assets']);

															if(in_array($_GET['id'],$lend_assets))
															{
																$lend_id = $row['lend_id'];

																break;
															}
														}
													}
													
													if(!empty($lend_id))
													{
														$output .= '<div class="container">';
														$output .= '<div class="content-center container white-alpha">';
														$output .= '<h1>Info</h1>';
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Asset ist zurzeit verausgabt.</p>';
														$output .= '</div>';
														$output .= '<p><a class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category=lend&id='.$lend_id.'">Leihgabe anzeigen <i class="fas fa-arrow-right"></i></a></p>';
														$output .= '</div>';
														$output .= '</div>';
													}
													else
													{
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
																
															if(!$exit)
															{
																array_push($cart,$_GET['id']);		
																		
																$output .= '<div class="container">';
																$output .= '<div class="content-center container white-alpha">';
																$output .= '<div class="panel black-alpha">';
																$output .= '<p><strong>'.$row['asset_serial'].'</strong> wurde in ihren Warenkorb gelegt.</p>';
																$output .= '</div>'; 
																$output .= '</div>'; 
																$output .= '</div>';
															}
															else
															{
																$output .= '<div class="container">';
																$output .= '<div class="content-center container white-alpha">';
																$output .= '<h1>Info</h1>';
																$output .= '<div class="panel black-alpha">';
																$output .= '<p><strong>'.$row['asset_serial'].'</strong> befindet sich bereits in ihrem Warenkorb.</p>';
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
															
														$_SESSION['cart']['assets'] = $cart;
													}
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
														$output .= '<div class="content-center container white-alpha">';
														$output .= '<div class="panel black-alpha">';
														$output .= '<p><strong>'.$row['user_name'].', '.$row['user_vname'].'</strong> wurde in ihren Warenkorb gelegt.</p>';
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
														$output .= '<p>Es wurde kein User gefunden.</p>';
														$output .= '</div>'; 
														$output .= '</div>'; 
														$output .= '</div>';
													}
														
													$_SESSION['cart']['user'] = $cart;
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
											
										$cart_count = count($_SESSION['cart']['assets']);
									}
									else if($_GET['aktion'] == $allowed_aktions[1])
									{
										if($_GET['category'] == $allowed_category[0])
										{
											if($_GET['id'] == '')
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<h1>Error</h1>';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Es wurde keine ID gesendet.</p>';
												$output .= '</div>'; 
												$output .= '</div>'; 
												$output .= '</div>';
											}
											else
											{
												if(preg_match('/[^0-9]/',$_GET['id']) == 0)
												{
													$assets = $_SESSION['cart']['assets'];
														
													$key = $_GET['id'];
														
													if(!empty($assets[$key]))
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
															
															$output .= '<div class="container">';
															$output .= '<div class="content-center container white">';
															$output .= '<div class="panel dark">';
															$output .= '<p>Asset wurde aus ihrem Warenkorb entfernt.</p>';
															$output .= '</div>'; 
															$output .= '</div>'; 
															$output .= '</div>';
															
															$_SESSION['cart']['assets'] = $assets;
														}	
													}
													else
													{
														$output .= '<div class="container">';
														$output .= '<div class="content-center container white-alpha">';
														$output .= '<h1>Error</h1>';
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Die gesendete ID ist nicht vorhanden.</p>';
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
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<h1>Error</h1>';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Sie haben noch keinen User gew&auml;hlt.</p>';
												$output .= '</div>'; 
												$output .= '</div>'; 
												$output .= '</div>';
											}
											else
											{
												$_SESSION['cart']['user'] = '';
														
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Der User wurde aus Ihrem Warenkorb entfernt.</p>';
												$output .= '</div>'; 
												$output .= '</div>'; 
												$output .= '</div>';
											}
										}
											
										$cart_count = count($_SESSION['cart']['assets']);
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
					else if($_GET['aktion'] == $allowed_aktions[2])
					{
						$user_id = $_SESSION['cart']['user'];
										
						$assets = $_SESSION['cart']['assets'];
							
						if(empty($user_id) || empty($assets))
						{
							$output .= '<div class="container">';
							$output .= '<div class="content-center container white-alpha">';
							$output .= '<h1>Info</h1>';
							$output .= '<div class="panel black-alpha">';
								
							if(empty($user_id) && empty($assets))
							{
								$output .= '<p>Ihr Warenkorb ist leer :(</p>';
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
								$output .= '<div class="panel white-alpha">';
								$output .= '<h1>Ihr Warenkorb</h1>';
								$output .= '<h2>User</h2>';
								$output .= '<div class="panel black-alpha display-container">';
								$output .= '<p>'.$user_id.'</p>';
								$output .= '<p>'.$row['user_name'].', '.$row['user_vname'].'</p>';
								$output .= '<div class="container display-middle-right">';
								$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="cart.php?aktion=del&category=user"><i class="fas fa-times"></i></a>';
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
										$output .= '<div class="panel black-alpha display-container">';
										$output .= '<p>'.$row['type_name'].' / '.$row['vendor_name'].' / '.$row['model_name'].'</p>';
										$output .= '<p>'.$row['asset_serial'].'</p>';
										$output .= '<div class="container display-middle-right">';
										$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="cart.php?aktion=del&category=asset&id='.$i.'"><i class="fas fa-times"></i></a>';
										$output .= '</div>';
										$output .= '</div>';
									}
								}
									
								$date_min = date('Y-m-d',strtotime('now')+60*60*24);
									
								$date_max = date('Y-m-d',strtotime('now')+60*60*24*365);
									
								$output .= '<form action="add.php" method="get">';
								$output .= '<input type="hidden" name="category" value="lend"/>';
								$output .= '<h2>Bemerkung</h2>';
								$output .= '<p><textarea id="lenddescription" onkeyup="'."chk_inputlength('lenddescription',200)".';" class="input-default border border-grey focus-border-light-blue" name="lend_description" placeholder="Bemerkung eingeben (200 Zeichen)"></textarea></p>';
								$output .= '<h2>Leihgabe bis</h2>';
								$output .= '<ul class="flex section">';
								$output .= '<li class="col-s10 col-m10 col-l10">';
								$output .= '<input class="input-default border border-grey border-tbl focus-border-light-blue" style="height:53px;" name="lend_end" type="date" value="'.$date_min.'" min="'.$date_min.'" max="'.$date_max.'"/></p>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-arrow-right"></i></button>';
								$output .= '</li>';
								$output .= '</ul>';
								$output .= '</form>';
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
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
					$output .= '<div class="content-center container white-alpha">';
					$output .= '<h1>Error</h1>';
					$output .= '<div class="panel black-alpha">';
					$output .= '<p>Die gesendete Aktion kann nicht bearbeitet werden.</p>';
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
		$output .= '<div class="content-center container white-alpha">';
		$output .= '<h1>Error</h1>';
		$output .= '<div class="panel black-alpha">';
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