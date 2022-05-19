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
		if(empty($_GET['category']) || $_GET['site'] == "" || empty($_GET['amount']))
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
				$allowed_category = array('lend','asset','user','vendor','model','type','building','floor','room','ci');

				if(in_array($_GET['category'],$allowed_category))
				{
					if(preg_match('/[^0-9]/',$_GET['site']) == 0)
					{
						if(preg_match('/[^0-9]/',$_GET['amount']) == 0)
						{
							$allowed_amount = array(5,10,15);

							if(in_array($_GET['amount'],$allowed_amount))
							{
								$category_german = array('Leihgaben','Assets','User','Hersteller','Modelle','Typen','Geb&auml;ude','Stockwerke','R&auml;ume','CIs');

								$array_key = array_search($_GET['category'],$allowed_category);

								$archived_input = '';

								if($_GET['category'] == $allowed_category[0])
								{
									if($_GET['archived'] == "")
									{
										header('location:http://'.$_SERVER['HTTP_HOST'].'/list.php?category=lend&site=0&amount=5&archived=0');
										exit;
									}
									else
									{
										if(preg_match('/[^0-9]/',$_GET['archived']) == 0)
										{
											$allowed_archived = array(0,1);
											
											if(in_array($_GET['archived'],$allowed_archived))
											{
												$archived_input .= '<input type="hidden" name="archived" value="'.$_GET['archived'].'"/>';

												$query = sprintf("
												SELECT lend_id
												FROM lend
												WHERE lend_archived = '%s';",
												$sql->real_escape_string($_GET['archived']));

												$result = $sql->query($query);

												$amount_gs = mysqli_num_rows($result);
												
												if($amount_gs > 0)
												{
													if($_GET['site']*$_GET['amount'] >= $amount_gs)
													{
														$_GET['site'] = 0;
													}

													$i = 0;

													$output .= '<div class="container">';
													$output .= '<table class="block"><tr>';
													
													if($_GET['archived'] == $allowed_archived[0])
													{
														$output .= '<td class="col-l6"><h1>aktive '.$category_german[$array_key].' ( '.$amount_gs.' )</h1></td>';
														$output .= '<td class="col-l6 text-right"><a class="btn-default border border-black-alpha black-alpha hover-white-alpha hover-text-black-alpha" href="list.php?category='.$_GET['category'].'&site=0&amount=5&archived=1"><i class="fa-solid fa-file-zipper"></i></a></td>';
													}
													else if($_GET['archived'] == $allowed_archived[1])
													{
														$output .= '<td class="col-l6"><h1>archivierte '.$category_german[$array_key].' ( '.$amount_gs.' )</h1></td>';
														$output .= '<td class="col-l6 text-right"><a class="btn-default border border-black-alpha black-alpha hover-white-alpha hover-text-black-alpha" href="list.php?category='.$_GET['category'].'&site=0&amount=5&archived=0"><i class="fas fa-clock"></i></a></td>';
													}
													
													$output .= '</tr></table>';
													$output .= '</div>';
													$output .= '<ul class="flex block">';

													$query = sprintf("
													SELECT lend_id,user_name,user_vname,lend_user_id,lend_start
													FROM lend
													INNER JOIN user ON user_id = lend_user_id 
													WHERE lend_archived = '%s'
													LIMIT %s,%s;",
													$sql->real_escape_string($_GET['archived']),
													$sql->real_escape_string($_GET['site']*$_GET['amount']),
													$sql->real_escape_string($_GET['amount']));

													$result = $sql->query($query);

													while($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$lend_start = date('d.m.Y',strtotime($row['lend_start']));

														if($i == 2)
														{
															$output .= '</ul>';
															$output .= '<ul class="flex block">';

															$i = 0;
														}

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
												else
												{
													$output .= '<div class="container">';
													$output .= '<div class="content-center container white-alpha">';
													$output .= '<h1>Info</h1>';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Es wurden keine Eintr&auml;ge gefunden.</p>';
													$output .= '<p><a class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" href="list.php?category='.$_GET['category'].'&site=0&amount=5&archived=0">aktive Leihgaben</a></p>';
													$output .= '<p><a class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" href="list.php?category='.$_GET['category'].'&site=0&amount=5&archived=1">archivierte Leihgaben</a></p>';
													$output .= '</div>';
													$output .= '</div>';
													$output .= '</div>';
												}
											}
											else
											{
												header('location:http://'.$_SERVER['HTTP_HOST'].'/list.php?category=lend&site=0&amount=5&archived=0');
												exit;
											}
										}
										else
										{
											header('location:http://'.$_SERVER['HTTP_HOST'].'/list.php?category=lend&site=0&amount=5&archived=0');
											exit;
										}
									}
								}
								else
								{
									$query = sprintf("
									SELECT %s_id
									FROM %s;",
									$sql->real_escape_string($_GET['category']),
									$sql->real_escape_string($_GET['category']));

									$result = $sql->query($query);

									$amount_gs = mysqli_num_rows($result);

									if($amount_gs > 0)
									{
										if($_GET['site']*$_GET['amount'] >= $amount_gs)
										{
											$_GET['site'] = 0;
										}

										$i = 0;

										$output .= '<div class="container">';
										$output .= '<table class="block"><tr>';
										$output .= '<td class="col-l6"><h1>'.$category_german[$array_key].' ( '.$amount_gs.' )</h1></td>';
										$output .= '<td class="col-l6"><div class="text-right"><a class="btn-default border border-black-alpha black-alpha hover-white-alpha hover-text-black-alpha" href="add.php?category='.$_GET['category'].'"><i class="fas fa-plus"></i></a></div></td>';
										$output .= '</tr></table>';
										$output .= '</div>';
										$output .= '<ul class="flex block">';

										if($_GET['category'] == $allowed_category[1])
										{
											$query = sprintf("
											SELECT asset_id,type_name,vendor_name,model_name,asset_serial
											FROM asset
											INNER JOIN type ON type_id = asset_type_id
											INNER JOIN vendor ON vendor_id = asset_vendor_id
											INNER JOIN model ON model_id = asset_model_id
											LIMIT %s,%s;",
											$sql->real_escape_string($_GET['site']*$_GET['amount']),
											$sql->real_escape_string($_GET['amount']));

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
										}
										else if($_GET['category'] == $allowed_category[2])
										{	
											$query = sprintf("
											SELECT user_id,user_email
											FROM user
											LIMIT %s,%s;",
											$sql->real_escape_string($_GET['site']*$_GET['amount']),
											$sql->real_escape_string($_GET['amount']));
												
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
												$output .= '<p>'.$row['user_id'].'</p>';
												$output .= '<p>'.$row['user_email'].'</p>';
												$output .= '<div class="container-large display-middle-right-large section-medium section-small">';
												$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="del.php?category='.$_GET['category'].'&id='.$row['user_id'].'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/list.php?category='.$_GET['category'].'&site='.$_GET['site'].'&amount='.$_GET['amount']).'"><i class="fas fa-trash"></i></a> ';
												$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$row['user_id'].'&tab=general"><i class="fas fa-eye"></i></a> ';
												$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="cart.php?aktion=add&category='.$_GET['category'].'&id='.$row['user_id'].'"><i class="fas fa-shopping-cart"></i> <i class="fas fa-plus"></i></a>';
												$output .= '</div>';
												$output .= '</div>';
												$output .= '</li>';
													
												$i++;
											}
										}	
										else
										{		
											$query = sprintf("
											SELECT %s_id,%s_name
											FROM %s
											LIMIT %s,%s;",
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['site']*$_GET['amount']),
											$sql->real_escape_string($_GET['amount']));
												
											$result = $sql->query($query);
												
											while($row = $result->fetch_array(MYSQLI_NUM))
											{
												if($i == 2)
												{
													$output .= '</ul>';
													$output .= '<ul class="flex block">';
														
													$i = 0;
												}
													
												$output .= '<li class="col-s12 col-m6 col-l6">';
												$output .= '<div class="margin container black-alpha">';
												$output .= '<table class="block">';
												$output .= '<tr>';
												$output .= '<td>';
												$output .= '<p>'.$row[1].'</p>';
												$output .= '</td>';
												$output .= '<td>';
												$output .= '<p class="text-right">';
												$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="del.php?category='.$_GET['category'].'&id='.$row[0].'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/list.php?category='.$_GET['category'].'&site='.$_GET['site'].'&amount='.$_GET['amount']).'"><i class="fas fa-trash"></i></a> ';
												$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$row[0].'"><i class="fas fa-eye"></i></a>';
												$output .= '</p>';
												$output .= '</td>';
												$output .= '</tr>';
												$output .= '</table>';
												$output .= '</div>';
												$output .= '</li>';
													
												$i++;
											}
										}
									
										$output .= '</ul>';
									}
									else
									{
										$output .= '<div class="container">';
										$output .= '<div class="content-center container white-alpha">';
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Es wurden keine Eintr&auml;ge gefunden.</p>';
										$output .= '<p><a class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" href="add.php?category='.$_GET['category'].'">'.$category_german[$array_key].' anlegen <i class="fas fa-plus"></i></a></p>';
										$output .= '</div>';
										$output .= '</div>';
										$output .= '</div>';
									}
								}

								if($amount_gs > $_GET['amount'])
								{
									$sites = ceil($amount_gs/$_GET['amount']);
										
									$next_site = $_GET['site']+1;
										
									if($next_site >= $sites)
									{
										$next_site = 0;
									}
										
									$previous_site = $_GET['site']-1;
										
									if($previous_site < 0)
									{
										$previous_site = $sites-1;
									}
										
									$output .= '<ul class="flex-center">';
									$output .= '<li class="col-s3 col-m4 col-l2">';
									$output .= '<div class="margin">';
									$output .= '<form action="list.php" method="get">';
									$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
									$output .= '<input type="hidden" name="site" value="'.$previous_site.'"/>';
									$output .= '<input type="hidden" name="amount" value="'.$_GET['amount'].'"/>';
									$output .= $archived_input;
									$output .= '<button class="block btn-default border border-black-alpha black-alpha hover-white-alpha hover-text-black-alpha" type="submit"><i class="fas fa-arrow-left"></i></button>';
									$output .= '</form>';
									$output .= '</div>';
									$output .= '</li>';
									$output .= '<li class="col-s6 col-m4 col-l4">';
									$output .= '<div class="margin">';
									$output .= '<form action="list.php" method="get">';
									$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
									$output .= '<input type="hidden" name="site" value="0"/>';
									$output .= '<select onchange="document.forms[2].submit();" class="input-default border border-grey focus-border-black-alpha" name="amount">';
									$output .= '<option disabled selected value="">'.$_GET['amount'].' pro Seite</option>';
									$output .= '<option value="5">5 pro Seite</option>';
									$output .= '<option value="10">10 pro Seite</option>';
									$output .= '<option value="15">15 pro Seite</option>';
									$output .= '</select>';
									$output .= $archived_input;
									$output .= '</form>';
									$output .= '</div>';
									$output .= '</li>';
									$output .= '<li class="col-s3 col-m4 col-l2">';
									$output .= '<div class="margin">';
									$output .= '<form action="list.php" method="get">';
									$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
									$output .= '<input type="hidden" name="site" value="'.$next_site.'"/>';
									$output .= '<input type="hidden" name="amount" value="'.$_GET['amount'].'"/>';
									$output .= $archived_input;
									$output .= '<button class="block btn-default border border-black-alpha black-alpha hover-white-alpha hover-text-black-alpha" type="submit"><i class="fas fa-arrow-right"></i></button>';
									$output .= '</form>';
									$output .= '</div>';
									$output .= '</li>';
									$output .= '</ul>';
								}
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Es k&ouml;nnen nur 5, 10 oder 15 Elemente angezeigt werden.</p>';
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
							$output .= '<p>Es k&ouml;nnen nur 5, 10 oder 15 Elemente angezeigt werden.</p>';
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
						$output .= '<p>Die Seitenzahl besteht nur aus Zahlen.</p>';
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
		<title>Sheldon #List</title>
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