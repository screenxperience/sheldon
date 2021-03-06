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
		if(empty($_GET['category']) || empty($_GET['id']) || empty($_GET['attr']) || $_GET['attr_value'] == "")
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
				$allowed_category = array('asset','user','ci','lend','vendor','model','type','building','floor','room');

				if(in_array($_GET['category'],$allowed_category))
				{
					if(preg_match('/[^0-9]/',$_GET['id']) == 0)
					{
						if($_GET['category'] == $allowed_category[0])
						{
							if(preg_match('/[^a-z]/',$_GET['attr']) == 0)
							{
								$allowed_attr = array('serial','cis','description','locked','type','vendor','model','building','floor','room');

								if(in_array($_GET['attr'],$allowed_attr))
								{
									if($_GET['attr'] == $allowed_attr[0])
									{
										if(preg_match('/[^A-Z0-9\-\.]/',$_GET['attr_value']) == 0)
										{
											$query = sprintf("
											UPDATE asset
											SET asset_serial = '%s'
											WHERE asset_id = '%s';",
											$sql->real_escape_string($_GET['attr_value']),
											$sql->real_escape_string($_GET['id']));

											$sql->query($query);

											if($sql->affected_rows == 1)
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Der Datensatz wurde erfolgreich gespeichert.</p>';
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
												$output .= '<p>Der Datensatz konnte nicht gespeichert werden.</p>';
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
											$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r die Seriennummer: A-Z, 0-9, -.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
									}
									else if($_GET['attr'] == $allowed_attr[1])
									{
										if(preg_match('/[^0-9]/',$_GET['ci_id']) == 0)
										{
											$ci_key = $_GET['ci_id'];

											$query = sprintf("
											SELECT asset_cis
											FROM asset
											WHERE asset_id = '%s';",
											$sql->real_escape_string($_GET['id']));

											$result = $sql->query($query);

											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$asset_cis = json_decode($row['asset_cis']);

												if(array_key_exists($ci_key,$asset_cis))
												{
													$asset_ci = $asset_cis[$ci_key];

													$query = sprintf("
													SELECT ci_type,ci_regex
													FROM ci
													WHERE ci_id = '%s';",
													$sql->real_escape_string($asset_ci[0]));

													$result = $sql->query($query);

													if($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														if($row['ci_type'] == 'string' || $row['ci_type'] == 'url')
														{
															if(preg_match('/[^'.$row['ci_regex'].']/',$_GET['attr_value']) == 0)
															{
																$asset_ci[1] = $_GET['attr_value'];

																$asset_cis[$ci_key] = $asset_ci;

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
																	$output .= '<p>Der Datensatz wurde erfolgreich gespeichert.</p>';
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
																	$output .= '<p>Der Datensatz konnte nicht gespeichert werden.</p>';
																	$output .= '</div>';
																	$output .= '</div>';
																	$output .= '</div>';
																}
															}
															else
															{
																$ci_regex = str_replace('\s',' Leerzeichen ',$row['ci_regex']);

																$ci_regex = str_replace('\\','',$ci_regex);

																$output .= '<div class="container">';
																$output .= '<div class="content-center container white-alpha">';
																$output .= '<h1>Error</h1>';
																$output .= '<div class="panel black-alpha">';
																$output .= '<p>Es sind nur folgende Zeichen erlaubt: '.$ci_regex.'</p>';
																$output .= '</div>';
																$output .= '</div>';
																$output .= '</div>';
															}
														}
														else if($row['ci_type'] == 'select')
														{
															if(preg_match('/[^0-9]/',$_GET['attr_value']) == 0)
															{
																$ci_regex = json_decode($row['ci_regex']);

																if(array_key_exists($_GET['attr_value'],$ci_regex))
																{
																	$asset_ci[1] = $_GET['attr_value'];

																	$asset_cis[$ci_key] = $asset_ci;

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
																		$output .= '<p>Der Datensatz wurde erfolgreich gespeichert.</p>';
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
																		$output .= '<p>Der Datensatz konnte nicht gespeichert werden.</p>';
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
																	$output .= '<p>W&auml;hlen Sie eine Option aus der SelectBox aus.</p>';
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
																$output .= '<p>W&auml;hlen Sie eine Option aus der SelectBox aus.</p>';
																$output .= '</div>';
																$output .= '</div>';
																$output .= '</div>';
															}
														}
														else if($row['ci_type'] == 'list')
														{
															if(preg_match('/[^'.$row['ci_regex'].']/',$_GET['attr_value']) == 0)
															{
																$pos = strpos($_GET['attr_value'],',');

																if($pos)
																{
																	$ci_value = explode(',',$_GET['attr_value']);

																	$asset_ci[1] = $ci_value;

																	$asset_cis[$ci_key] = $asset_ci;

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
																		$output .= '<p>Der Datensatz wurde erfolgreich gespeichert.</p>';
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
																		$output .= '<p>Der Datensatz konnte nicht gespeichert werden.</p>';
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
																	$output .= '<p>Verwenden Sie ein Komma(,) um die Listeneintr&auml;ge zu trennen.</p>';
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
																$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r ein Liste: a-z, A-Z, 0-9, ??????????????-,.</p>';
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
														$output .= '<p>Es konnte kein CI gefunden werden.</p>';
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
									else if($_GET['attr'] == $allowed_attr[2])
									{
										if(preg_match('/[^a-zA-Z??????????????0-9\-\.\r\n]/',$_GET['attr_value']) == 0)
										{
											if(strlen($_GET['attr_value']) <= 200)
											{
												$query = sprintf("
												UPDATE asset
												SET asset_description = '%s'
												WHERE asset_id = '%s';",
												$sql->real_escape_string($_GET['attr_value']),
												$sql->real_escape_string($_GET['id']));

												$sql->query($query);

												if($sql->affected_rows == 1)
												{
													$output .= '<div class="container">';
													$output .= '<div class="content-center container white-alpha">';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Der Datensatz wurde erfolgreich gespeichert.</p>';
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
													$output .= '<p>Der Datensatz konnte nicht gespeichert.</p>';
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
												$output .= '<p>Die Bemerkung darf nur 200 Zeichen lang sein.</p>';
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
											$output .= '<p>Verwenden Sie nur folgende Zeichen: a-z, A-Z, ??????????????, 0-9, -.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
									}
									else if($_GET['attr'] == $allowed_attr[3])
									{
										if(preg_match('/[^0-9]/',$_GET['attr_value']) == 0)
										{
											$allowed_locked = array(0,1);

											if(in_array($_GET['attr_value'],$allowed_locked))
											{
												$query = sprintf("
												UPDATE asset
												SET asset_locked = '%s'
												WHERE asset_id = '%s';",
												$sql->real_escape_string($_GET['attr_value']),
												$sql->real_escape_string($_GET['id']));

												$sql->query($query);

												if($sql->affected_rows == 1)
												{
													if($_GET['attr_value'] == $allowed_locked[0])
													{
														$output .= '<div class="container">';
														$output .= '<div class="content-center container white-alpha">';
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Asset wurde enstperrt.</p>';
														$output .= '</div>';
														$output .= '</div>';
														$output .= '</div>';
													}
													else if($_GET['attr_value'] == $allowed_locked[1])
													{
														$output .= '<div class="container">';
														$output .= '<div class="content-center container white-alpha">';
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Asset wurde gesperrt.</p>';
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
													$output .= '<p>Datensatz konnte nicht ge&auml;ndert werden.</p>';
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
												$output .= '<p>Ein Asset kann nur gesperrt oder entsperrt werden.</p>';
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
											$output .= '<p>Ein Asset kann nur gesperrt oder entsperrt werden.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
									}
									else
									{
										if(preg_match('/[^0-9]/',$_GET['attr_value']) == 0)
										{
											$query = sprintf("
											UPDATE %s
											SET %s_%s_id = '%s'
											WHERE %s_id = '%s';",
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['attr']),
											$sql->real_escape_string($_GET['attr_value']),
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['id']));

											$sql->query($query);

											if($sql->affected_rows == 1)
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Der Datensatz wurde erfolgreich gespeichert.</p>';
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
												$output .= '<p>Es konnte kein Datensatz gespeichert werden.</p>';
												$output .= '</div>';
												$output .= '</div>';
												$output .= '</div>';
											}
										}
										else
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container whitea-alpha">';
											$output .= '<h1>Error</h1>';
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>Eine ID besteht nur aus Zahlen.</p>';
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
									$output .= '<p>Es k&ouml;nnen nur folgende Attribute ge&auml;ndert werden: Seriennummer, CIs, Bemerkung, Sperrung, Typ, Hersteller, Modell, Geb&auml;ude, Stockwerk und Raum.</p>';
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
								$output .= '<p>Es k&ouml;nnen nur folgende Attribute ge&auml;ndert werden: Seriennummer, CIs, Bemerkung, Sperrung, Typ, Hersteller, Modell, Geb&auml;ude, Stockwer und Raum.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
						}
						else if($_GET['category'] == $allowed_category[1])
						{
							if(preg_match('/[^a-z]/',$_GET['attr']) == 0)
							{
								$allowed_attr = array('vname','name','email','active','admin','rank','building','floor','room');

								if(in_array($_GET['attr'],$allowed_attr))
								{
									if($_GET['attr'] == $allowed_attr[0] || $_GET['attr'] == $allowed_attr[1])
									{
										if(preg_match('/[^a-zA-Z??????????????\-\s]/',$_GET['attr_value']) == 0)
										{
											$query = sprintf("
											UPDATE user
											SET user_%s = '%s'
											WHERE user_id = '%s';",
											$sql->real_escape_string($_GET['attr']),
											$sql->real_escape_string($_GET['attr_value']),
											$sql->real_escape_string($_GET['id']));

											$sql->query($query);

											if($sql->affected_rows == 1)
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
												$output .= '<p>Es konnte kein Datensatz gespeichert werden.</p>';
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
											$output .= '<p>Verwenden Sie nur folgende Zeichen: a-z, A-Z, ??????????????-</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
									}
									else if($_GET['attr'] == $allowed_attr[2])
									{
										preg_match('/^[a-zA-Z0-9]{1,}+\@{1}+[a-zA-Z]{1,}+\.{1}+[a-zA-Z]{1,}$/',$_GET['attr_value'],$matches);

										if(!empty($matches))
										{
											$pos = strpos($_GET['attr_value'],'@');

											if($pos)
											{
												$allowed_provider = array('bundeswehr.org');

												$provider = substr($_GET['attr_value'],$pos+1);

												if(in_array($provider,$allowed_provider))
												{
													$query = sprintf("
													UPDATE user
													SET user_email = '%s'
													WHERE user_id = '%s';",
													$sql->real_escape_string($_GET['attr_value']),
													$sql->real_escape_string($_GET['id']));

													$sql->query($query);

													if($sql->affected_rows == 1)
													{
														$output .= '<div class="container">';
														$output .= '<div class="content-center container white-alpha">';
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
														$output .= '<p>Es konnte kein Datensatz gespeichert werden.</p>';
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
													$output .= '<p>Verwenden Sie eine @bundeswehr.org E-Mail-Adresse.</p>';
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
												$output .= '<p>In der E-Mail-Adresse fehlt das @-Zeichen.</p>';
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
											$output .= '<p>Geben Sie eine valide E-Mail-Adresse ein.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
									}
									else if($_GET['attr'] == $allowed_attr[3] || $_GET['attr'] == $allowed_attr[4])
									{
										if(preg_match('/[^0-9]/',$_GET['attr_value']) == 0)
										{
											$allowed_status = array('0','1');

											if(in_array($_GET['attr_value'],$allowed_status))
											{
												$query = sprintf("
												UPDATE user
												SET user_%s = '%s'
												WHERE user_id = '%s';",
												$sql->real_escape_string($_GET['attr']),
												$sql->real_escape_string($_GET['attr_value']),
												$sql->real_escape_string($_GET['id']));

												$sql->query($query);

												if($sql->affected_rows == 1)
												{
													$output .= '<div class="container">';
													$output .= '<div class="content-center container white-alpha">';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Userstatus wurde erfolgreich ge&auml;ndert.</p>';
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
													$output .= '<p>Userstatus konnte nicht ge&auml;ndert werden.</p>';
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
												$output .= '<p>Die Statuswerte k&ouml;nnen nur 1 oder 0 sein.</p>';
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
											$output .= '<p>Die Statuswerte k&ouml;nnen nur 1 oder 0 sein.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
									}
									else if($_GET['attr'] == $allowed_attr[5] || $_GET['attr'] == $allowed_attr[6] || $_GET['attr'] == $allowed_attr[7] || $_GET['attr'] == $allowed_attr[8]) 
									{
										if(preg_match('/[^0-9]/',$_GET['attr_value']) == 0)
										{
											$query = sprintf("
											UPDATE user
											SET user_%s_id = '%s'
											WHERE user_id = '%s';",
											$sql->real_escape_string($_GET['attr']),
											$sql->real_escape_string($_GET['attr_value']),
											$sql->real_escape_string($_GET['id']));

											$sql->query($query);

											if($sql->affected_rows == 1)
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
												$output .= '<p>Es konnte kein Datensatz gespeichert werden.</p>';
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
											$output .= '<p>Eine ID besteht nur aus Zahlen.</p>';
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
									$output .= '<p>Es k&ouml;nnen nur folgende Attribute ge&auml;ndert werden: Vorname, Name, E-Mail-Adresse, Aktiv, Admin, Dienstgrad, Geb&auml;ude, Stockwerk und Raum.</p>';
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
								$output .= '<p>Es k&ouml;nnen nur folgende Attribute ge&auml;ndert werden: Vorname, Name, E-Mail-Adresse, Aktiv, Admin, Dienstgrad, Geb&auml;ude, Stockwerk und Raum.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
						}
						else if($_GET['category'] == $allowed_category[2])
						{
							if(preg_match('/[^a-z]/',$_GET['attr']) == 0)
							{
								$allowed_attr = array('name','type','regex');

								if(in_array($_GET['attr'],$allowed_attr))
								{
									if($_GET['attr'] == $allowed_attr[0])
									{
										if(preg_match('/[^a-zA-Z??????????????0-9\s\-\.]/',$_GET['attr_value']) == 0)
										{
											$query = sprintf("
											UPDATE ci
											SET ci_name = '%s'
											WHERE ci_id = '%s';",
											$sql->real_escape_string($_GET['attr_value']),
											$sql->real_escape_string($_GET['id']));

											$sql->query($query);

											if($sql->affected_rows == 1)
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
												$output .= '<p>Es konnte kein Datensatz gespeichert werden.</p>';
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
											$output .= '<p>Verwenden Sie nur folgende Zeichen: a-z, A-Z, 0-9, ??????????????-.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
									}
									else if($_GET['attr'] == $allowed_attr[1])
									{
										$query = sprintf("
										SELECT asset_id
										FROM asset
										WHERE asset_cis LIKE '%s'
										LIMIT 1;",
										$sql->real_escape_string('%["'.$_GET['id'].'",%'));

										$result = $sql->query($query);

										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white-alpha">';
											$output .= '<h1>Info</h1>';
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>CI-Typ kann aufgrund bestehender Verkn&uuml;pfungen nicht ge&auml;ndert werden.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
										else
										{
											if(preg_match('/[^a-z]/',$_GET['attr_value']) == 0)
											{
												$allowed_type = array('string','select','url','list');

												if(in_array($_GET['attr_value'],$allowed_type))
												{
													if($_GET['attr_value'] == $allowed_type[0])
													{
														$ci_regex = 'a-zA-Z';
													}
													else if($_GET['attr_value'] == $allowed_type[1])
													{
														$regex_arr = array('Option 1','Option 2');

														$ci_regex = json_encode($regex_arr);
													}
													else if($_GET['attr_value'] == $allowed_type[2])
													{
														$ci_regex = 'a-zA-Z0-9\?\&\=\.\:\/\_';
													}
													else if($_GET['attr_value'] == $allowed_type[3])
													{
														$regex_arr = array('Eintrag 1,Eintrag 2');

														$ci_regex = json_encode($regex_arr);
													}

													$query = sprintf("
													UPDATE ci
													SET ci_type = '%s',
													ci_regex = '%s'
													WHERE ci_id = '%s';",
													$sql->real_escape_string($_GET['attr_value']),
													$sql->real_escape_string($ci_regex),
													$sql->real_escape_string($_GET['id']));

													$sql->query($query);

													if($sql->affected_rows == 1)
													{
														$output .= '<div class="container">';
														$output .= '<div class="content-center container white-alpha">';
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
														$output .= '<p>Datensatz konnte nicht gespeichert werden.</p>';
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
													$output .= '<p>Es sind nur folgende Typen zul&auml;ssig: Zeichenkette, SelectBox, URL oder Liste.</p>';
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
												$output .= '<p>Es sind nur folgende Typen zul&auml;ssig: Zeichenkette, SelectBox, URL oder Liste.</p>';
												$output .= '</div>';
												$output .= '</div>';
												$output .= '</div>';
											}
										}
									}
									else if($_GET['attr'] == $allowed_attr[2])
									{
										$query = sprintf("
										SELECT asset_id
										FROM asset
										WHERE asset_cis LIKE '%s'
										LIMIT 1;",
										$sql->real_escape_string('%["'.$_GET['id'].'",%'));

										$result = $sql->query($query);

										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white-alpha">';
											$output .= '<h2>Info</h2>';
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>CI-Regex kann aufgrund bestehender Verkn&uuml;pfungen nicht ge&auml;ndert werden.</p>';
											$output .= '</div>';
											$output .= '</div>';
											$output .= '</div>';
										}
										else
										{
											$query = sprintf("
											SELECT ci_type
											FROM ci
											WHERE ci_id = '%s';",
											$sql->real_escape_string($_GET['id']));

											$result = $sql->query($query);

											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$allowed_type = array('string','select','url','list');

												if($row['ci_type'] == $allowed_type[0])
												{
													if(preg_match('/[^azAZ09s??????????????\\\_\-\:\.]/',$_GET['attr_value']) == 0)
													{
														$query = sprintf("
														UPDATE ci
														SET ci_regex = '%s'
														WHERE ci_id = '%s';",
														$sql->real_escape_string($_GET['attr_value']),
														$sql->real_escape_string($_GET['id']));
														$sql->query($query);

														if($sql->affected_rows == 1)
														{
															$output .= '<div class="container">';
															$output .= '<div class="content-center container white-alpha">';
															$output .= '<div class="panel black-alpha">';
															$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
															$output .= '<p>Datensatz konnte nicht gespeichert werden.</p>';
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
														$output .= '<p>Verwenden Sie nur folgende Zeichen um einen Regex zu bilden: azAZ09??????????????\-:._</p>';
														$output .= '</div>';
														$output .= '</div>';
														$output .= '</div>';
													}
												}
												else if($row['ci_type'] == $allowed_type[1])
												{
													if(preg_match('/[^a-zA-Z0-9??????????????\s\-\.\,]/',$_GET['attr_value']) == 0)
													{
														$pos = strpos($_GET['attr_value'],',');

														if($pos)
														{
															$regex_arr = explode(',',$_GET['attr_value']);

															$ci_regex = json_encode($regex_arr);

															$query = sprintf("
															UPDATE ci
															SET ci_regex = '%s'
															WHERE ci_id = '%s';",
															$sql->real_escape_string($ci_regex),
															$sql->real_escape_string($_GET['id']));

															$sql->query($query);

															if($sql->affected_rows == 1)
															{
																$output .= '<div class="container">';
																$output .= '<div class="content-center container white-alpha">';
																$output .= '<div class="panel black-alpha">';
																$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
																$output .= '<p>Datensatz konnte nicht gespeichert werden.</p>';
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
															$output .= '<p>Verwenden Sie ein Komma(,) um die Auswahlm&ouml;glichkeiten festzulegen.</p>';
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
														$output .= '<p>Verwenden Sie nur folgende Zeichen: a-z, A-Z, 0-9, ??????????????-,.</p>';
														$output .= '<p>Verwenden Sie ein Komma(,) um die Auswahlm&ouml;glichkeiten festzulegen.</p>';
														$output .= '</div>';
														$output .= '</div>';
														$output .= '</div>';
													}
												}
												else if($row['ci_type'] == $allowed_type[2])
												{
													$output .= '<div class="container">';
													$output .= '<div class="content-center container white-alpha">';
													$output .= '<h1>Error</h1>';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Die URL Regex kann nicht ge&auml;ndert werden.</p>';
													$output .= '</div>';
													$output .= '</div>';
													$output .= '</div>';
												}
												else if($row['ci_type'] == $allowed_type[3])
												{
													$output .= '<div class="container">';
													$output .= '<div class="content-center container white-alpha">';
													$output .= '<h1>Error</h1>';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Die List Regex kann nicht ge&auml;ndert werden.</p>';
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
												$output .= '<p>Es wurde kein CI gefunden.</p>';
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
									$output .= '<p>Es k&ouml;nnen nur folgende Attribute ge&auml;ndert werden: Name, Typ und Regex.</p>';
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
								$output .= '<p>Es k&ouml;nnen nur folgende Attribute ge&auml;ndert werden: Name, Typ und Regex.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
						}
						else if($_GET['category'] == $allowed_category[3])
						{
							$query = sprintf("
							SELECT lend_archived
							FROM lend
							WHERE lend_id = '%s';",
							$sql->real_escape_string($_GET['id']));
							
							$result = $sql->query($query);
							
							if($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								if(!$row['lend_archived'])
								{
									if(preg_match('/[^a-z]/',$_GET['attr']) == 0)
									{
										$allowed_attr = array('description','date');

										if(in_array($_GET['attr'],$allowed_attr))
										{
											if($_GET['attr'] == $allowed_attr[0])
											{
												if(preg_match('/[^a-zA-Z0-9??????????????\-\.\s\r\n]/',$_GET['attr_value']) == 0)
												{
													if(strlen($_GET['attr_value']) <= 200)
													{
														$query = sprintf("
														UPDATE lend
														SET lend_description = '%s',
														lend_creator_id = '%s'
														WHERE lend_id = '%s';",
														$sql->real_escape_string($_GET['attr_value']),
														$sql->real_escape_string($_SESSION['user']['id']),
														$sql->real_escape_string($_GET['id']));
														
														$sql->query($query);
														
														if($sql->affected_rows == 1)
														{
															$output .= '<div class="container">';
															$output .= '<div class="content-center container white-alpha">';
															$output .= '<div class="panel black-alpha">';
															$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
															$output .= '<p>Datensatz konnte nicht gespeichert werden.</p>';
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
														$output .= '<p>Verwenden Sie nur 200 Zeichen f??r die Beschreibung.</p>';
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
													$output .= '<p>Verwenden Sie nur folgenden Zeichen f&uuml;r ihre Bemerkung: a-z, A-Z, 0-9, ??????????????-.</p>';
													$output .= '</div>';
													$output .= '</div>';
													$output .= '</div>';
												}
											}
											else if($_GET['attr'] == $allowed_attr[1])
											{
												preg_match('/^[0-9]{4}+\-{1}+[0-9]{2}+\-{1}+[0-9]{2}$/',$_GET['attr_value'],$date_matches);
										
												if(!empty($date_matches))
												{
													$date_parts = explode('-',$_GET['attr_value']);
													
													if(checkdate($date_parts[1],$date_parts[2],$date_parts[0]))
													{
														$date = strtotime($_GET['attr_value']);
														
														$date_min = strtotime('now')+60*60*24;
													
														$date_max = strtotime('now')+60*60*24*365;
														
														if($date >= $date_min || $date <= $date_max)
														{
															$query = sprintf("
															UPDATE lend
															SET lend_end = '%s',
															lend_creator_id = '%s'
															WHERE lend_id = '%s';",
															$sql->real_escape_string($_GET['attr_value']),
															$sql->real_escape_string($_SESSION['user']['id']),
															$sql->real_escape_string($_GET['id']));
														
															$sql->query($query);
														
															if($sql->affected_rows == 1)
															{
																$output .= '<div class="container">';
																$output .= '<div class="content-center container white-alpha">';
																$output .= '<div class="panel black-alpha">';
																$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
																$output .= '<p>Datensatz konnte nicht gespeichert werden.</p>';
																$output .= '</div>';
																$output .= '</div>';
																$output .= '</div>';
															}
														}
														else
														{
															$output  = '<div class="container">';
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
														$output .= '<p>Datum existiert nicht.</p>';
														$output .= '</div>';
														$output .= '</div>';
														$output .= '</div>';
													}
												}
												else
												{
													$output  = '<div class="container">';
													$output .= '<div class="content-center container white-alpha">';
													$output .= '<h1>Error</h1>';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>W&auml;hlen Sie ein Datum aus dem Date-Picker.</p>';
													$output .= '</div>'; 
													$output .= '</div>'; 
													$output .= '</div>';
												}
											}
										}
										else
										{
											$output  = '<div class="container">';
											$output .= '<div class="content-center container white-alpha">';
											$output .= '<h1>Error</h1>';
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>Das gew&auml;hlte Attribut konnte nicht bearbeitet werden.</p>';
											$output .= '</div>'; 
											$output .= '</div>'; 
											$output .= '</div>';
										}
									}
									else
									{
										$output  = '<div class="container">';
										$output .= '<div class="content-center container white-alpha">';
										$output .= '<h1>Error</h1>';
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Das gew&auml;hlte Attribut konnte nicht bearbeitet werden.</p>';
										$output .= '</div>'; 
										$output .= '</div>'; 
										$output .= '</div>';
									}
								}
								else
								{
									$output  = '<div class="container">';
									$output .= '<div class="content-center container white-alpha">';
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Datensatz konnte nicht bearbeitet werden.</p>';
									$output .= '<p>Leihgabe wurde bereits archiviert.</p>';
									$output .= '</div>'; 
									$output .= '</div>'; 
									$output .= '</div>';
								}
							}
							else
							{
								$output  = '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Es wurde keine Leihgabe gefunden.</p>';
								$output .= '</div>'; 
								$output .= '</div>'; 
								$output .= '</div>';
							}
						}
						else
						{
							if(preg_match('/[^a-z]/',$_GET['attr']) == 0)
							{
								$allowed_attr = array('name');

								if(in_array($_GET['attr'],$allowed_attr))
								{
									if($_GET['attr'] == $allowed_attr[0])
									{
										if(preg_match('/[^a-zA-Z0-9??????????????\-\.\s]/',$_GET['attr_value']) == 0)
										{
											$query = sprintf("
											UPDATE %s
											SET %s_name = '%s'
											WHERE %s_id = '%s';",
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['attr_value']),
											$sql->real_escape_string($_GET['category']),
											$sql->real_escape_string($_GET['id']));

											$sql->query($query);

											if($sql->affected_rows == 1)
											{
												$output .= '<div class="container">';
												$output .= '<div class="content-center container white-alpha">';
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Datensatz wurde erfolgreich gespeichert.</p>';
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
												$output .= '<p>Datensatz konnte nicht gespeichert werden.</p>';
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
											$output .= '<p>Verwenden Sie nur folgende Zeichen: a-z, A-Z, 0-9, ??????????????, -.</p>';
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
									$output .= '<p>Es k&ouml;nnen nur folgende Atrribute bearbeitet werden: Name</p>';
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
								$output .= '<p>Es k&ouml;nnen nur folgende Atrribute bearbeitet werden: Name</p>';
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
		<title>Sheldon #Change</title>
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
