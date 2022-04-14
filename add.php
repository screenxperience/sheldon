<?php
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
		if(empty($_GET['category']))
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
			if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['category']) == 0)
			{
				$allowed_category = array('asset','user','ci','cis','vendor','model','type','building','floor','room');
				
				if(in_array($_GET['category'],$allowed_category))
				{	
					$category_german = array('Asset','User','CI','CIs','Hersteller','Modell','Typ','Geb&auml;ude','Stockwerk','Raum');
					
					$key = array_search($_GET['category'],$allowed_category);
					
					$output .= '<div class="container">';
					$output .= '<div class="content-center container white">';
					$output .= '<h1>'.$category_german[$key].' hinzuf&uuml;gen</h1>';
							
					if($_GET['category'] == $allowed_category[0])
					{
						if(!empty($_GET['send']))
						{
							if(empty($_GET['type_id']) || empty($_GET['vendor_id']) || empty($_GET['model_id']) || empty($_GET['asset_serial']))
							{
								$output .= '<div class="panel dark">';
								$output .= '<p>Es wurden nicht alle Daten gesendet.</p>';
								$output .= '</div>';
							}
							else
							{
								if(preg_match('/[^'.$app_regex['number'].']/',$_GET['type_id']) == 0)
								{
									$query = sprintf("
									SELECT type_name
									FROM type
									WHERE type_id = '%s';",
									$sql->real_escape_string($_GET['type_id']));
									
									$result = $sql->query($query);
									
									if($row = $result->fetch_array(MYSQLI_ASSOC))
									{
										$type_name = $row['type_name'];
										
										if(preg_match('/[^'.$app_regex['number'].']/',$_GET['vendor_id']) == 0)
										{
											$query = sprintf("
											SELECT vendor_name
											FROM vendor
											WHERE vendor_id = '%s';",
											$sql->real_escape_string($_GET['vendor_id']));
											
											$result = $sql->query($query);
											
											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$vendor_name = $row['vendor_name'];
												
												if(preg_match('/[^'.$app_regex['number'].']/',$_GET['model_id']) == 0)
												{
													$query = sprintf("
													SELECT model_name
													FROM model
													WHERE model_id = '%s';",
													$sql->real_escape_string($_GET['model_id']));
													
													$result = $sql->query($query);
													
													if($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$model_name = $row['model_name'];
														
														if(preg_match('/[^'.$app_regex['loweruppernumbersz'].']/',$_GET['asset_serial']) == 0)
														{	
															$asset_cis = array();
															
															$asset_keywords = $type_name.' '.$vendor_name.' '.$model_name.' '.$_GET['asset_serial'];
																
															$query = sprintf("
															INSERT INTO
															asset
															(asset_type_id,asset_vendor_id,asset_model_id,asset_serial,asset_cis,asset_keywords)
															VALUES
															('%s','%s','%s','%s','%s','%s');",
															$sql->real_escape_string($_GET['type_id']),
															$sql->real_escape_string($_GET['vendor_id']),
															$sql->real_escape_string($_GET['model_id']),
															$sql->real_escape_string($_GET['asset_serial']),
															$sql->real_escape_string(json_encode($asset_cis)),
															$sql->real_escape_string($asset_keywords));
															
															$sql->query($query);
												
															if($sql->affected_rows == 1)
															{
																$output .= '<div class="panel dark">';
																$output .= '<p><strong>'.$_GET['asset_serial'].'</strong> wurde hinzugef&uuml;gt.</p>';
																$output .= '</div>';
															}
															else
															{
																$output .= '<div class="panel dark">';
																$output .= '<p>Es konnte kein Asset hinzugef&uuml;gt werden.</p>';
																$output .= '</div>';
															}
														}
														else
														{
															$regex = str_replace('\s',' Leerzeichen ',$app_regex['loweruppernumbersz']);
														
															$regex = str_replace('\\','',$regex);
															
															$output .= '<div class="panel dark">';
															$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r die Seriennummer: '.$regex.'</p>';
															$output .= '</div>';
														}
													}
													else
													{
														$output .= '<div class="panel dark">';
														$output .= '<p>Es ist kein Modell mit der gesendeten ID vorhanden.</p>';
														$output .= '</div>';
													}
												}
												else
												{
													$output .= '<div class="panel dark">';
													$output .= '<p>Die ModellID besteht nur aus Zahlen.</p>';
													$output .= '</div>';
												}
											}
											else
											{
												$output .= '<div class="panel dark">';
												$output .= '<p>Es ist kein Hersteller mit der gesendeten ID vorhanden.</p>';
												$output .= '</div>';
											}
										}
										else
										{
											$output .= '<div class="panel dark">';
											$output .= '<p>Die HerstellerID besteht nur aus Zahlen.</p>';
											$output .= '</div>';
										}
									}
									else
									{
										$output .= '<div class="panel dark">';
										$output .= '<p>Es ist kein Typ mit der gesendeten ID vorhanden.</p>';
										$output .= '</div>';
									}
								}
								else
								{
									$output .= '<div class="panel dark">';
									$output .= '<p>Die TypID besteht nur aus Zahlen.</p>';
									$output .= '</div>';
								}
							}
						}
						
						$output .= '<form action="add.php" method="get">';
						$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
						$output .= '<p><select class="ipt-default" name="type_id">';
						$output .= '<option value="">Typ w&auml;hlen</option>';
								
						$query = "
						SELECT type_id,type_name
						FROM type";
								
						$result = $sql->query($query);
								
						while($row = $result->fetch_array(MYSQLI_ASSOC))
						{
							$output .= '<option value="'.$row['type_id'].'">'.$row['type_name'].'</option>';
						}
								
						$output .= '</select></p>';
						$output .= '<p><select class="ipt-default" name="vendor_id">';
						$output .= '<option value="">Hersteller w&auml;hlen</option>';
								
						$query = "
						SELECT vendor_id,vendor_name
						FROM vendor";
								
						$result = $sql->query($query);
								
						while($row = $result->fetch_array(MYSQLI_ASSOC))
						{
							$output .= '<option value="'.$row['vendor_id'].'">'.$row['vendor_name'].'</option>';
						}
								
						$output .= '</select></p>';
						$output .= '<p><select class="ipt-default" name="model_id">';
						$output .= '<option value="">Modell w&auml;hlen</option>';
								
						$query = "
						SELECT model_id,model_name
						FROM model";
								
						$result = $sql->query($query);
								
						while($row = $result->fetch_array(MYSQLI_ASSOC))
						{
							$output .= '<option value="'.$row['model_id'].'">'.$row['model_name'].'</option>';
						}
								
						$output .= '</select></p>';
						$output .= '<p><input class="ipt-default" name="asset_serial" placeholder="Seriennummer"/></p>';
						$output .= '<p><button class="block btn-default light-blue">weiter <i class="fas fa-arrow-right"></i></button></p>';
						$output .= '<input type="hidden" name="send" value="1"/>';
						$output .= '</form>';
					}
					else if($_GET['category'] == $allowed_category[1])
					{
						require($_SERVER['DOCUMENT_ROOT'].'/include/randomstr.inc.php');
							
						require($_SERVER['DOCUMENT_ROOT'].'/include/strhash.inc.php');
						
						if(!empty($_GET['send']))
						{
							if(empty($_GET['user_id']) || empty($_GET['user_rank_id']) || empty($_GET['user_vname']) || empty($_GET['user_name']) || empty($_GET['user_email']))
							{
								$output .= '<div class="panel dark">';
								$output .= '<p>Es wurden nicht alle Daten gesendet.</p>';
								$output .= '</div>';
							}
							else
							{
								if(strlen($_GET['user_id']) == 8 && preg_match('/[^'.$app_regex['number'].']/',$_GET['user_id']) == 0)
								{
									if(preg_match('/[^'.$app_regex['number'].']/',$_GET['user_rank_id']) == 0)
									{
										$query = sprintf("
										SELECT rank_name_long
										FROM rank
										WHERE rank_id = '%s';",
										$sql->real_escape_string($_GET['user_rank_id']));
											
										$result = $sql->query($query);
											
										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{	
											if(preg_match('/[^'.$app_regex['lowerupperumlsz'].']/',$_GET['user_vname']) == 0)
											{
												if(preg_match('/[^'.$app_regex['lowerupperumlsz'].']/',$_GET['user_name']) == 0)
												{
													preg_match('/'.$app_regex['email'].'/',$_GET['user_email'],$matches);
													
													if(!empty($matches))
													{
														$pos = strpos($_GET['user_email'],'@');

														if($pos)
														{
															$allowed_provider = array('bundeswehr.org');

															$provider = substr($_GET['user_email'],$pos+1);

															if(in_array($provider,$allowed_provider))
															{
																$salt = randomstr(10);
																			
																$password = randomstr(10);
																			
																$user_password = strhash($salt.$password);
																			
																$user_keywords = $_GET['user_id'].' '.$row['rank_name_long'].' '.$_GET['user_vname'].' '.$_GET['user_name'].' '.$_GET['user_email'];
																
																$query = sprintf("
																INSERT INTO user
																(user_id,user_rank_id,user_vname,user_name,user_email,user_password,user_salt,user_keywords)
																VALUES
																('%s','%s','%s','%s','%s','%s','%s','%s');",
																$sql->real_escape_string($_GET['user_id']),
																$sql->real_escape_string($_GET['user_rank_id']),
																$sql->real_escape_string($_GET['user_vname']),
																$sql->real_escape_string($_GET['user_name']),
																$sql->real_escape_string($_GET['user_email']),
																$sql->real_escape_string($user_password),
																$sql->real_escape_string($salt),
																$sql->real_escape_string($user_keywords));
																			
																$sql->query($query);
																			
																if($sql->affected_rows == 1)
																{
																	$output .= '<div class="panel dark">';
																	$output .= '<p><strong>'.$_GET['user_id'].'</strong> wurde hinzugef&uuml;gt.</p>';
																	$output .= '</div>';
																}
																else
																{
																	$output .= '<div class="panel dark">';
																	$output .= '<p>Es konnte kein User hinzugef&uuml;gt werden.</p>';
																	$output .= '<p>Personalnummer oder E-Mail-Adresse bereits vorhanden.</p>';
																	$output .= '</div>';
																}
															}
															else
															{
																$output .= '<div class="panel dark">';
																$output .= '<p>Verwenden Sie eine @bundeswehr.org E-Mail-Adresse.</p>';
																$output .= '</div>';
															}
														}
														else
														{
															$output .= '<div class="panel dark">';
															$output .= '<p>In der E-Mail-Adresse fehlt das @-Zeichen.</p>';
															$output .= '</div>';		
														}
													}
													else
													{
														$output .= '<div class="panel dark">';
														$output .= '<p>Geben Sie eine valide E-Mail-Adresse ein.</p>';
														$output .= '</div>';
													}
												}
												else
												{
													$regex = str_replace('\\','',$app_regex['lowerupperumlsz']);
													
													$output .= '<div class="panel dark">';
													$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den Nachname: '.$regex.'</p>';
													$output .= '</div>';
												}
											}
											else
											{
												$regex = str_replace('\\','',$app_regex['lowerupperumlsz']);
												
												$output .= '<div class="panel dark">';
												$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den Vorname: '.$regex.'</p>';
												$output .= '</div>';
											}
										}
										else
										{
											$output .= '<div class="panel dark">';
											$output .= '<p>Es ist kein Dienstgrad mit der gesendeten ID vorhanden.</p>';
											$output .= '</div>';
										}
									}
									else
									{
										$output .= '<div class="panel dark">';
										$output .= '<p>Die DienstgradID besteht nur aus Zahlen.</p>';
										$output .= '</div>';
									}
								}
								else
								{
									$output .= '<div class="panel dark">';
									$output .= '<p>Die Personalnummer ist 8 Zeichen lang und besteht nur aus Zahlen.</p>';
									$output .= '</div>';
								}
							}
						}
						
						$output .= '<form action="add.php" method="get">';
						$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
						$output .= '<p><input class="ipt-default" type="number" name="user_id" placeholder="Personalnummer"/></p>';
						$output .= '<p><select class="ipt-default" name="user_rank_id">';
						$output .= '<option value="">Dienstgrad w&auml;hlen</option>';
								
						$query = "
						SELECT rank_id,rank_name_long
						FROM rank";
								
						$result = $sql->query($query);
								
						while($row = $result->fetch_array(MYSQLI_ASSOC))
						{
							$output .= '<option value="'.$row['rank_id'].'">'.$row['rank_name_long'].'</option>';
						}
								
						$output .= '</select></p>';
						$output .= '<p><input class="ipt-default" type="text" name="user_vname" placeholder="Vorname"/></p>';
						$output .= '<p><input class="ipt-default" type="text" name="user_name" placeholder="Nachname"/></p>';
						$output .= '<p><input class="ipt-default" type="text" name="user_email" placeholder="E-Mail-Adresse"/></p>';
						$output .= '<p><button class="block btn-default light-blue" type="submit">weiter <i class="fas fa-arrow-right"></i></button></p>';
						$output .= '<input type="hidden" name="send" value="1"/>';
						$output .= '</form>';
					}
					else if($_GET['category'] == $allowed_category[2])
					{
						$showform = 1;
						
						if(!empty($_GET['send']))
						{
							if($_GET['send'] == 1)
							{
								if(empty($_GET['ci_name']))
								{
									$output .= '<div class="panel dark">';
									$output .= '<p>Es wurde kein CI-Name gesendet.</p>';
									$output .= '</div>';
								}
								else
								{
									if(preg_match('/[^'.$app_regex['lowerupperumlnumbersz'].']/',$_GET['ci_name']) == 0)
									{
										$query = sprintf("
										SELECT ci_id
										FROM ci
										WHERE ci_name = '%s';",
										$sql->real_escape_string($_GET['ci_name']));
							
										$result = $sql->query($query);
							
										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="panel dark">';
											$output .= '<p><strong>'.$_GET['ci_name'].'</strong> ist bereits vorhanden.</p>';
											$output .= '</div>';
										}
										else
										{
											$allowed_ci_types = array('string' => 'Zeichenkette','select' => 'SelectBox','url' => 'URL','list' => 'Liste');
											
											$showform = 2;
										}
									}
									else
									{
										$regex = str_replace('\\','',$app_regex['lowerupperumlnumbersz']);
								
										$output .= '<div class="panel dark">';
										$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den CI-Name: '.$regex.'</p>';
										$output .= '</div>';
									}
								}
							}
							else if($_GET['send'] == 2)
							{
								$showform = 2;
								
								if(empty($_GET['ci_name']) || empty($_GET['ci_type']))
								{
									if(empty($_GET['ci_name']))
									{
										$output .= '<div class="panel dark">';
										$output .= '<p>Es wurde kein CI-Name gesendet</p>';
										$output .= '</div>';
										
										$showform = 1;
									}
									else if(empty($_GET['ci_type']))
									{
										$output .= '<div class="panel dark">';
										$output .= '<p>Es wurde kein CI-Typ gesendet.</p>';
										$output .= '</div>';
									}
								}
								else
								{
									if(preg_match('/[^'.$app_regex['lowerupperumlnumbersz'].']/',$_GET['ci_name']) == 0)
									{
										$query = sprintf("
										SELECT ci_id
										FROM ci
										WHERE ci_name = '%s';",
										$sql->real_escape_string($_GET['ci_name']));
										
										$result = $sql->query($query);
										
										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="panel dark">';
											$output .= '<p><strong>'.$_GET['ci_name'].'</strong> ist bereits vorhanden.</p>';
											$output .= '</div>';
											
											$showform = 1;
										}
										else
										{
											if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['ci_type']) == 0)
											{
												$allowed_ci_types = array('string','select','url','list');
											
												if(in_array($_GET['ci_type'],$allowed_ci_types))
												{
													$showform = 3;
												}
												else
												{
													$output .= '<div class="panel dark">';
													$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox,URL und Liste.</p>';
													$output .= '</div>';
												}
											}
											else
											{
												$output .= '<div class="panel dark">';
												$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox,URL und Liste.</p>';
												$output .= '</div>';
											}
										}
									}
									else
									{
										$regex = str_replace('\\','',$app_regex['lowerupperumlnumbersz']);
								
										$output .= '<div class="panel dark">';
										$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den CI-Name: '.$regex.'</p>';
										$output .= '</div>';
										
										$showform = 1;
									}
								}
							}
							else if($_GET['send'] == 3)
							{
								$showform = 3;
								
								if(empty($_GET['ci_name']) || empty($_GET['ci_type']) || empty($_GET['ci_regex']))
								{
									$output .= '<div class="panel dark">';
									
									if(empty($_GET['ci_name']))
									{
										$showform = 1;
										
										$output .= '<p>Es wurde kein CI-Name gesendet.</p>';
									}
									else if(empty($_GET['ci_type']))
									{
										$showform = 2;
										
										$output .= '<p>Es wurde kein CI-Typ gew&auml;hlt</p>';
									}
									else if(empty($_GET['ci_regex']))
									{
										$output .= '<p>Es wurde kein CI-Regex gesendet</p>';
									}
									
									$output .= '</div>';
								}
								else
								{
									if(preg_match('/[^'.$app_regex['lowerupperumlnumbersz'].']/',$_GET['ci_name']) == 0)
									{
										$query = sprintf("
										SELECT ci_id
										FROM ci
										WHERE ci_name = '%s';",
										$sql->real_escape_string($_GET['ci_name']));
										
										$result = $sql->query($query);
										
										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="panel dark">';
											$output .= '<p><strong>'.$_GET['ci_name'].'</strong> ist bereits vorhanden.</p>';
											$output .= '</div>';
											
											$showform = 1;
										}
										else
										{
											if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['ci_type']) == 0)
											{
												$allowed_ci_types = array('string','select','url','list');
											
												if(in_array($_GET['ci_type'],$allowed_ci_types))
												{
													$exit = 0;
													
													if($_GET['ci_type'] == $allowed_ci_types[0])
													{
														if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['ci_regex']) != 0)
														{
															$output .= '<div class="panel dark">';
															$output .= '<p>W&auml;hlen Sie einen Regex aus.</p>';
															$output .= '</div>';
															
															$exit = 1;
														}
														else
														{
															if(!array_key_exists($_GET['ci_regex'],$app_regex))
															{
																$output .= '<div class="panel dark">';
																$output .= '<p>W&auml;hlen Sie einen Regex aus.</p>';
																$output .= '</div>';
															
																$exit = 1;
															}
															else
															{
																$ci_regex = $app_regex[$_GET['ci_regex']];
															}
														}
													}
													else if($_GET['ci_type'] == $allowed_ci_types[1])
													{
														if(preg_match('/[^'.$app_regex['lowerupperumlnumbersz'].']/',$_GET['ci_regex']) != 0)
														{
															$regex = str_replace('\\','',$app_regex['lowerupperumlnumbersz']);
															
															$output .= '<div class="panel dark">';
															$output .= '<p>Verwenden Sie nur folgende Zeichen: '.$regex.'</p>';
															$output .= '<p>Das Komma(,) wird f&uuml;r die Auswahlm&ouml;glichkeiten verwendet.</p>';
															$output .= '</div>';
															
															$exit = 1;
														}
														else
														{
															$pos = strpos($_GET['ci_regex'],',');
															
															if(!$pos)
															{
																$output .= '<div class="panel dark">';
																$output .= '<p>Verwenden Sie ein Komma(,) um die Auswahlm&ouml;glichkeiten festzulegen.</p>';
																$output .= '</div>';
															
																$exit = 1;
															}
															else
															{
																$ci_regex_arr = explode(',',$_GET['ci_regex']);
																
																$ci_regex = json_encode($ci_regex_arr);
															}
														}
													}
													else if($_GET['ci_type'] == $allowed_ci_types[2])
													{
														$ci_regex = $app_regex['url'];	
													}
													
													if(empty($exit))
													{
														$query = sprintf("
														INSERT INTO ci
														(ci_name,ci_type,ci_regex)
														VALUES('%s','%s','%s');",
														$sql->real_escape_string($_GET['ci_name']),
														$sql->real_escape_string($_GET['ci_type']),
														$sql->real_escape_string($ci_regex));
														
														$sql->query($query);
														
														if($sql->affected_rows == 1)
														{
															$output .= '<div class="panel dark">';
															$output .= '<p><strong>'.$_GET['ci_name'].'</strong> wurde erfolgreich hinzugef&uuml;gt.</p>';
															$output .= '</div>';
															
															$showform = 1;
														}
														else
														{
															$output .= '<div class="panel dark">';
															$output .= '<p>Es konnte kein CI hinzugef&uuml;gt werden.</p>';
															$output .= '</div>';
														}
													}
												}
												else
												{
													$output .= '<div class="panel dark">';
													$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox und URL.</p>';
													$output .= '</div>';
													
													$showform = 2;
												}
											}
											else
											{
												$output .= '<div class="panel dark">';
												$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox und URL.</p>';
												$output .= '</div>';
												
												$showform = 2;
											}
										}
									}
									else
									{
										$regex = str_replace('\\','',$app_regex['lowerupperumlnumbersz']);
								
										$output .= '<div class="panel dark">';
										$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den CI-Name: '.$regex.'</p>';
										$output .= '</div>';
										
										$showform = 1;
									}
								}
							}	
						}
						
						$output .= '<form action="add.php" method="get">';
						$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
						
						if($showform == 1)
						{								
							$output .= '<p><input class="ipt-default" type="text" name="ci_name" placeholder="CI-Name"/></p>';
							$output .= '<p><input type="hidden" name="send" value="1"/></p>';
						}
						else if($showform == 2)
						{
							$output .= '<input type="hidden" name="ci_name" value="'.$_GET['ci_name'].'"/>';
							$output .= '<div class="section ipt-default">'.$_GET['ci_name'].'</div>';
							$output .= '<p><select class="ipt-default" name="ci_type">';
							$output .= '<option disabled selected value="">CI-Typ w&auml;hlen</option>';
							
							foreach($allowed_ci_types as $type_name => $type_value)
							{
								$output .= '<option value="'.$type_name.'">'.$type_value.'</option>';
							}
							
							$output .= '</select></p>';
							$output .= '<p><input type="hidden" name="send" value="2"/></p>';
						}
						else if($showform == 3)
						{
							$output .= '<input type="hidden" name="ci_name" value="'.$_GET['ci_name'].'"/>';
							$output .= '<input type="hidden" name="ci_type" value="'.$_GET['ci_type'].'"/>';
							$output .= '<div class="section ipt-default">'.$_GET['ci_name'].'</div>';
							
							if($_GET['ci_type'] == $allowed_ci_types[0])
							{
								$output .= '<div class="section ipt-default">Zeichenkette</div>';
								$output .= '<p><select class="ipt-default" name="ci_regex">';
								$output .= '<option value="">Regex w&auml;hlen</option>';
								
								foreach($app_regex as $regex_name => $regex_value)
								{
									$regex = str_replace('\s',' Leerzeichen ',$regex_value);
									
									$regex = str_replace('\\','',$regex);
									
									$output .= '<option value="'.$regex_name.'">'.$regex.'</option>';
								}
								
								$output .= '</select></p>';
							}
							if($_GET['ci_type'] == $allowed_ci_types[1])
							{
								$output .= '<div class="section ipt-default">SelectBox</div>';
								$output .= '<p><input class="ipt-default" name="ci_regex" placeholder="Option 1,Option 2"/></p>';
							}
							if($_GET['ci_type'] == $allowed_ci_types[2])
							{
								$regex = str_replace('\\','',$app_regex['url']);
								
								$output .= '<div class="section ipt-default">URL</div>';
								$output .= '<input type="hidden" name="ci_regex" value="url"/>';
								$output .= '<div class="section ipt-default">'.$regex.'</div>';
							}
							
							$output .= '<input type="hidden" name="send" value="3"/>';
						}
						
						$output .= '<p><button class="block btn-default light-blue" type="submit">weiter <i class="fas fa-arrow-right"></i></button></p>';
						$output .= '</form>';
					}
					else if($_GET['category'] == $allowed_category[3])
					{
						if(!empty($_GET['id']))
						{
							if(preg_match('/[^'.$app_regex['number'].']/',$_GET['id']) == 0)
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
									
									$showform = 1;
									
									if(!empty($_GET['send']))
									{
										if($_GET['send'] == 1)
										{
											if(empty($_GET['ci_id']))
											{
												$output .= '<div class="panel dark">';
												$output .= '<p>Es wurde keine CI-ID gesendet.</p>';
												$output .= '</div>';
											}
											else
											{
												if(preg_match('/[^'.$app_regex['number'].']/',$_GET['ci_id']) == 0)
												{
													$query = sprintf("
													SELECT ci_name,ci_type,ci_regex
													FROM ci
													WHERE ci_id = '%s';",
													$sql->real_escape_string($_GET['ci_id']));
							
													$result = $sql->query($query);
							
													if($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$exit = 0;
												
														for($i = 0; $i < count($asset_cis); $i++)
														{
															$asset_ci = $asset_cis[$i];
													
															$ci_id = $asset_ci[0];
													
															if($ci_id == $_GET['ci_id'])
															{
																$exit = 1;
 														
																break;
															}
														}
												
														if($exit)
														{
															$output .= '<div class="panel dark">';
															$output .= '<p>CI ist bereits vorhanden.</p>';
															$output .= '</div>';
														}
														else
														{
															$allowed_ci_types = array('string','select','url');
															
															$showform = 2;
														}
													}
													else
													{
														$output .= '<div class="panel dark">';
														$output .= '<p>Es wurde kein CI gefunden.</p>';
														$output .= '</div>';
													}
												}
												else
												{
													$output .= '<div class="panel dark">';
													$output .= '<p>Die CI-ID besteht nur aus Zahlen.</p>';
													$output .= '</div>';
												}
											}
										}
										else if($_GET['send'] == 2)
										{	
											$allowed_ci_types = array('string','select','url');
											
											$showform = 2;
											
											if(empty($_GET['ci_id']) || $_GET['ci_value'] == "")
											{
												$output .= '<div class="panel dark">';
												
												if(empty($_GET['ci_id']))
												{
													$showform = 1;
													
													$output .= '<p>Es wurde keine CI-ID gesendet.</p>';
												}
												else if($_GET['ci_value'] == "")
												{
													$output .= '<p>Es wurde kein CI-Wert gesendet.</p>';
												}
												
												$output .= '</div>';
											}
											else
											{
												if(preg_match('/[^'.$app_regex['number'].']/',$_GET['ci_id']) == 0)
												{
													$query = sprintf("
													SELECT ci_name,ci_type,ci_regex
													FROM ci
													WHERE ci_id = '%s';",
													$sql->real_escape_string($_GET['ci_id']));
							
													$result = $sql->query($query);
							
													if($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$exit = 0;
												
														for($i = 0; $i < count($asset_cis); $i++)
														{
															$asset_ci = $asset_cis[$i];
													
															$ci_id = $asset_ci[0];
													
															if($ci_id == $_GET['ci_id'])
															{
																$exit = 1;
 														
																break;
															}
														}
														
														if($exit)
														{
															$output .= '<div class="panel dark">';
															$output .= '<p>CI ist bereits vorhanden.</p>';
															$output .= '</div>';
														}
														else
														{
															if($row['ci_type'] == 'string' || $row['ci_type'] == 'url')
															{
																if(preg_match('/[^'.$row['ci_regex'].']/',$_GET['ci_value']) != 0)
																{
																	$regex = str_replace('\\','',$row['ci_regex']);
																	
																	$output .= '<div class="panel dark">';
																	$output .= '<p>Verwenden Sie nur folgende Zeichen: '.$regex.'</p>';
																	$output .= '</div>';
																	
																	$exit = 1;
																}
															}
															else if($row['ci_type'] == 'select')
															{
																if(preg_match('/[^'.$app_regex['number'].']/',$_GET['ci_value']) != 0)
																{
																	$output .= '<div class="panel dark">';
																	$output .= '<p>W&auml;hlen Sie eine Option aus.</p>';
																	$output .= '</div>';
																	
																	$exit = 1;
																}
																else
																{
																	$regex_arr = json_decode($row['ci_regex']);
																
																	if(!array_key_exists($_GET['ci_value'],$regex_arr))
																	{
																		$output .= '<div class="panel dark">';
																		$output .= '<p>W&auml;hlen Sie eine Option aus.</p>';
																		$output .= '</div>';
																		
																		$exit = 1;
																	}
																}
															}
															
															if(empty($exit))
															{
																$asset_ci = array($_GET['ci_id'],$_GET['ci_value']);
																
																array_push($asset_cis,$asset_ci);
																
																$query = sprintf("
																UPDATE asset
																SET asset_cis = '%s'
																WHERE asset_id = '%s';",
																$sql->real_escape_string(json_encode($asset_cis)),
																$sql->real_escape_string($_GET['id']));
																
																$sql->query($query);
																
																if($sql->affected_rows == 1)
																{
																	$output .= '<div class="panel dark">';
																	$output .= '<p>CI wurde erfolgreich hinzugef&uuml;gt.</p>';
																	$output .= '</div>';
																	
																	$output .= '<script>'."ch_location('view.php?category=asset&id=".$_GET['id']."&tab=cis',2);".'</script>';
																	
																	$showform = 0;
																}
																else
																{
																	$output .= '<div class="panel dark">';
																	$output .= '<p>CI konnte nicht hinzugef&uuml;gt werden.</p>';
																	$output .= '</div>';
																}
															}		
														}														
													}
													else
													{
														$output .= '<div class="panel dark">';
														$output .= '<p>Es wurde kein CI gefunden.</p>';
														$output .= '</div>';
														
														$showform = 1;
													}
												}
												else
												{
													$output .= '<div class="panel dark">';
													$output .= '<p>Die CI-ID besteht nur aus Zahlen.</p>';
													$output .= '</div>';
													
													$showform = 1;
												}
											}
										}
									}
									
									if($showform)
									{
										$output .= '<form action="add.php" method="get">';
										$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
										$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
										
										if($showform == 1)
										{
											$output .= '<p><select class="ipt-default" name="ci_id">';
									
											$query = "
											SELECT ci_id,ci_name
											FROM ci";
									
											$result = $sql->query($query);
									
											while($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$output .= '<option value="'.$row['ci_id'].'">'.$row['ci_name'].'</option>';
											}
									
											$output .= '</select></p>';
											$output .= '<input type="hidden" name="send" value="1"/>';
										}
										else if($showform == 2)
										{
											$output .= '<input type="hidden" name="ci_id" value="'.$_GET['ci_id'].'"/>';
											$output .= '<div class="section ipt-default">'.$row['ci_name'].'</div>';
											
											if($row['ci_type'] == $allowed_ci_types[0])
											{
												$regex = str_replace('\\','',$row['ci_regex']);
												
												$output .= '<div class="section ipt-default">Zeichenkette</div>';
												$output .= '<p><input class="ipt-default" type="text" name="ci_value" placeholder="'.$regex.'"/></p>';
											}
											else if($row['ci_type'] == $allowed_ci_types[1])
											{
												$regex_arr = json_decode($row['ci_regex']);
												
												$output .= '<div class="section ipt-default">SelectBox</div>';
												$output .= '<p><select class="ipt-default" name="ci_value">';
												$output .= '<option value="">Option w&auml;hlen</option>';
												
												for($i = 0; $i < count($regex_arr); $i++)
												{
													$output .= '<option value="'.$i.'">'.$regex_arr[$i].'</option>';
												}
												
												$output .= '</select></p>';
											}
											else if($row['ci_type'] == $allowed_ci_types[2])
											{
												$regex = str_replace('\\','',$row['ci_regex']);
												
												$output .= '<div class="section ipt-default">URL</div>';
												$output .= '<p><input class="ipt-default" type="url" name="ci_value" placeholder="'.$regex.'"/></p>';
											}
														
											$output .= '<input type="hidden" name="send" value="2"/>';
										}
										
										$output .= '<p><button class="block btn-default light-blue" type="submit">weiter <i class="fas fa-arrow-right"></i></button></p>';
										$output .= '</form>';
									}
								}
								else
								{
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel dark">';
									$output .= '<p>Es wurde kein Asset gefunden.</p>';
									$output .= '</div>';
								}
							}
							else
							{
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel dark">';
								$output .= '<p>Die AssetID besteht nur aus Zahlen.</p>';
								$output .= '</div>';
							}
						}
						else
						{
							$output .= '<h1>Error</h1>';
							$output .= '<div class="panel dark">';
							$output .= '<p>Es wurde keine AssetID gesendet.</p>';
							$output .= '</div>';
						}
					}
					else
					{
						if(!empty($_GET['send']))
						{
							$value = $_GET[$_GET['category'].'_name'];
							
							if(empty($value))
							{
								$output .= '<div class="panel dark">';
								$output .= '<p>Es wurde keine Eingabe gesendet.</p>';
								$output .= '</div>';
							}
							else
							{
								if(preg_match('/[^'.$app_regex['lowerupperumlnumbersz'].']/',$value) == 0)
								{
									$query = sprintf("
									INSERT INTO
									%s
									(%s_name)
									VALUES
									('%s');",
									$sql->real_escape_string($_GET['category']),
									$sql->real_escape_string($_GET['category']),
									$sql->real_escape_string($value));
										
									$sql->query($query);
										
									if($sql->affected_rows == 1)
									{
										$output .= '<div class="panel dark">';
										$output .= '<p><strong>'.$value.'</strong> wurde hinzugef&uuml;gt.</p>';
										$output .= '</div>';
									}
									else
									{
										$output .= '<div class="panel dark">';
										$output .= '<p><strong>'.$value.'</strong> ist bereits vorhanden.</p>';
										$output .= '</div>';
									}
								}
								else
								{
									$regex = str_replace('\s',' Leerzeichen ',$app_regex['lowerupperumlnumbersz']);
									
									$regex = str_replace('\\','',$regex);
									
									$output .= '<div class="panel dark">';
									$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r ihre Eingabe: '.$regex.'</p>';
									$output .= '</div>';
								}
							}			
						}
						
						$output .= '<form action="add.php" method="get">';
						$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
						$output .= '<ul class="flex section">';
						$output .= '<li class="col-s10 col-m10 col-l10">';
						$output .= '<input class="ipt-default" type="text" name="'.$_GET['category'].'_name" placeholder="'.$category_german[$key].'"/>';
						$output .= '</li>';
						$output .= '<li class="col-s2 col-m2 col-l2">';
						$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-arrow-right"></i></button>';
						$output .= '</li>';
						$output .= '</ul>';
						$output .= '<input type="hidden" name="send" value="1"/>';
						$output .= '</form>';
					}
					
					$output .= '</div>';
					$output .= '</div>';
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
		<title>Sheldon #Add</title>
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
