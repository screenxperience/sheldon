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
				$allowed_category = array('asset','user','ci','cis','lend','vendor','model','type','building','floor','room');

				if(in_array($_GET['category'],$allowed_category))
				{
					$category_german = array('Asset','User','CI','CIs','Leihgabe','Hersteller','Modell','Typ','Geb&auml;ude','Stockwerk','Raum');

					$array_key = array_search($_GET['category'],$allowed_category);

					$output .= '<div class="container">';
					$output .= '<div class="content-center container white-alpha">';
					$output .= '<h1>'.$category_german[$array_key].' hinzuf&uuml;gen</h1>';

					if($_GET['category'] == $allowed_category[0])
					{
						if(!empty($_GET['send']))
						{
							if(empty($_GET['type_id']) || empty($_GET['vendor_id']) || empty($_GET['model_id']) || empty($_GET['asset_serial']))
							{
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Es wurden nicht alle Daten gesendet.</p>';
								$output .= '</div>';
							}
							else
							{
								if(preg_match('/[^0-9]/',$_GET['type_id']) == 0)
								{
									if(preg_match('/[^0-9]/',$_GET['vendor_id']) == 0)
									{
										if(preg_match('/[^0-9]/',$_GET['model_id']) == 0)
										{
											if(preg_match('/[^A-Z0-9\-\.]/',$_GET['asset_serial']) == 0)
											{
												$asset_cis = array();

												$query = sprintf("
												INSERT INTO
												asset
												(asset_type_id,asset_vendor_id,asset_model_id,asset_serial,asset_cis)
												VALUES
												('%s','%s','%s','%s','%s');",
												$sql->real_escape_string($_GET['type_id']),
												$sql->real_escape_string($_GET['vendor_id']),
												$sql->real_escape_string($_GET['model_id']),
												$sql->real_escape_string($_GET['asset_serial']),
												$sql->real_escape_string(json_encode($asset_cis)));

												$sql->query($query);

												if($sql->affected_rows == 1)
												{
													$output .= '<div class="panel black-alpha">';
													$output .= '<p><strong>'.$_GET['asset_serial'].'</strong> wurde hinzugef&uuml;gt.</p>';
													$output .= '</div>';
												}
												else
												{
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Es konnte kein Asset hinzugef&uuml;gt werden.</p>';
													$output .= '</div>';
												}
											}
											else
											{
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r die Seriennummer: A-Z, 0-9, -.</p>';
												$output .= '</div>';
											}
										}
										else
										{
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>Die ModellID besteht nur aus Zahlen.</p>';
											$output .= '</div>';
										}
									}
									else
									{
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Die HerstellerID besteht nur aus Zahlen.</p>';
										$output .= '</div>';
									}
								}
								else
								{
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Die TypID besteht nur aus Zahlen.</p>';
									$output .= '</div>';
								}
							}
						}

						$output .= '<form action="add.php" method="get">';
						$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
						$output .= '<p><select id="assettype" class="input-default border border-grey focus-border-light-blue" name="type_id">';
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
						$output .= '<p><select id="assetvendor" class="input-default border border-grey focus-border-light-blue" name="vendor_id">';
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
						$output .= '<p><select id="assetmodel" class="input-default border border-grey focus-border-light-blue" name="model_id">';
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
						$output .= '<p><input class="input-default border border-grey focus-border-light-blue" name="asset_serial" placeholder="Seriennummer"/></p>';
						$output .= '<p><button class="col-s6 col-m6 col-l6 btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">weiter <i class="fas fa-arrow-right"></i></button><button onclick="'."loadimportfile('asset')".';" class="col-s6 col-m6 col-l6 btn-default border border-light-blue light-blue hover-white hover-text-blue" type="button">Import <i class="fa-solid fa-file-csv"></i></button></p>';
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
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Es wurden nicht alle Daten gesendet.</p>';
								$output .= '</div>';
							}
							else
							{
								if(strlen($_GET['user_id']) == 8 && preg_match('/[^0-9]/',$_GET['user_id']) == 0)
								{
									if(preg_match('/[^0-9]/',$_GET['user_rank_id']) == 0)
									{
										if(preg_match('/[^a-zA-ZöäüÖÄÜß\-\s]/',$_GET['user_vname']) == 0)
										{
											if(preg_match('/[^a-zA-ZöäüÖÄÜß\-\s]/',$_GET['user_name']) == 0)
											{
												preg_match('/^[a-zA-Z0-9]{1,}+\@{1}+[a-zA-Z]{1,}+\.{1}+[a-zA-Z]{1,}$/',$_GET['user_email'],$matches);

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

															$query = sprintf("
															INSERT INTO user
															(user_id,user_rank_id,user_vname,user_name,user_email,user_password,user_salt)
															VALUES
															('%s','%s','%s','%s','%s','%s','%s');",
															$sql->real_escape_string($_GET['user_id']),
															$sql->real_escape_string($_GET['user_rank_id']),
															$sql->real_escape_string($_GET['user_vname']),
															$sql->real_escape_string($_GET['user_name']),
															$sql->real_escape_string($_GET['user_email']),
															$sql->real_escape_string($user_password),
															$sql->real_escape_string($salt));

															$sql->query($query);

															if($sql->affected_rows == 1)
															{
																$output .= '<div class="panel black-alpha">';
																$output .= '<p><strong>'.$_GET['user_id'].'</strong> wurde hinzugef&uuml;gt.</p>';
																$output .= '</div>';
															}
															else
															{
																$output .= '<div class="panel black-alpha">';
																$output .= '<p>Es konnte kein User hinzugef&uuml;gt werden.</p>';
																$output .= '<p>Personalnummer oder E-Mail-Adresse bereits vorhanden.</p>';
																$output .= '</div>';
															}
														}
														else
														{
															$output .= '<div class="panel black-alpha">';
															$output .= '<p>Verwenden Sie eine @bundeswehr.org E-Mail-Adresse.</p>';
															$output .= '</div>';
														}
													}
													else
													{
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>In der E-Mail-Adresse fehlt das @-Zeichen.</p>';
														$output .= '</div>';
													}
												}
												else
												{
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Geben Sie eine valide E-Mail-Adresse ein.</p>';
													$output .= '</div>';
												}
											}
											else
											{
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den Nachname: a-z, A-Z, 0-9, öäüÖÄÜß-.</p>';
												$output .= '</div>';
											}
										}
										else
										{
											$output .= '<div class="panel black-alpha">';
											$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den Vorname: a-z, A-Z, 0-9, öäüÖÄÜß-.</p>';
											$output .= '</div>';
										}
									}
									else
									{
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Die DienstgradID besteht nur aus Zahlen.</p>';
										$output .= '</div>';
									}
								}
								else
								{
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Die Personalnummer ist 8 Zeichen lang und besteht nur aus Zahlen.</p>';
									$output .= '</div>';
								}
							}
						}

						$output .= '<form action="add.php" method="get">';
						$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
						$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="number" name="user_id" placeholder="Personalnummer"/></p>';
						$output .= '<p><select class="input-default border border-grey focus-border-light-blue" name="user_rank_id">';
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
						$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="user_vname" placeholder="Vorname"/></p>';
						$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="user_name" placeholder="Nachname"/></p>';
						$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="user_email" placeholder="E-Mail-Adresse"/></p>';
						$output .= '<p><button class="col-s6 col-m6 col-l6 btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">weiter <i class="fas fa-arrow-right"></i></button><a href="https://iam.bundeswehr.org/" target="_blank" class="col-s6 col-m6 col-l6 btn-default border border-light-blue light-blue hover-white hover-text-blue" type="button">IAMBw</a></p>';
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
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Es wurde kein CI-Name gesendet.</p>';
									$output .= '</div>';
								}
								else
								{
									if(preg_match('/[^a-zA-ZöäüÖÄÜß0-9\-\.\s]/',$_GET['ci_name']) == 0)
									{
										$query = sprintf("
										SELECT ci_id
										FROM ci
										WHERE ci_name = '%s';",
										$sql->real_escape_string($_GET['ci_name']));

										$result = $sql->query($query);

										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="panel black-alpha">';
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
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den CI-Name: a-z, A-Z, öäüÖÄÜß, 0-9, -.</p>';
										$output .= '</div>';
									}
								}
							}
							else if($_GET['send'] == 2)
							{
								$showform = 2;

								if(empty($_GET['ci_name']) || empty($_GET['ci_type']))
								{
									$output .= '<div class="panel black-alpha">';

									if(empty($_GET['ci_name']))
									{
										$output .= '<p>Es wurde kein CI-Name gesendet.</p>';

										$showform = 1;
									}
									else if(empty($_GET['ci_type']))
									{
										$output .= '<p>Es wurde kein CI-Typ gesendet.</p>';
									}

									$output .= '</div>';
								}
								else
								{
									if(preg_match('/[^a-zA-ZöäüÖÄÜß0-9\-\.\s]/',$_GET['ci_name']) == 0)
									{
										$query = sprintf("
										SELECT ci_id
										FROM ci
										WHERE ci_name = '%s';",
										$sql->real_escape_string($_GET['ci_name']));

										$result = $sql->query($query);

										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="panel black-alpha">';
											$output .= '<p><strong>'.$_GET['ci_name'].'</strong> ist bereits vorhanden.</p>';
											$output .= '</div>';

											$showform = 1;
										}
										else
										{
											if(preg_match('/[^a-z]/',$_GET['ci_type']) == 0)
											{
												$allowed_ci_types = array('string','select','url','list');

												if(in_array($_GET['ci_type'],$allowed_ci_types))
												{
													$showform = 3;
												}
												else
												{
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox,URL und Liste.</p>';
													$output .= '</div>';
												}
											}
											else
											{
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox,URL und Liste.</p>';
												$output .= '</div>';
											}
										}
									}
									else
									{
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den CI-Name: a-z, A-Z, öäüÖÄÜß, 0-9, -.</p>';
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
									if(preg_match('/[^a-zA-ZöäüÖÄÜß0-9\-\.\s]/',$_GET['ci_name']) == 0)
									{
										$query = sprintf("
										SELECT ci_id
										FROM ci
										WHERE ci_name = '%s';",
										$sql->real_escape_string($_GET['ci_name']));

										$result = $sql->query($query);

										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$output .= '<div class="panel black-alpha">';
											$output .= '<p><strong>'.$_GET['ci_name'].'</strong> ist bereits vorhanden.</p>';
											$output .= '</div>';

											$showform = 1;
										}
										else
										{
											if(preg_match('/[^a-z]/',$_GET['ci_type']) == 0)
											{
												$allowed_ci_types = array('string','select','url','list');

												if(in_array($_GET['ci_type'],$allowed_ci_types))
												{
													$exit = 0;

													if($_GET['ci_type'] == $allowed_ci_types[0])
													{
														if(preg_match('/[^azAZ09öäüÖÄÜßs\-\:\\\.]/',$_GET['ci_regex']) != 0)
														{
															$output .= '<div class="container black-alpha">';
															$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den Regex: az, AZs, 09, öäüÖÄÜß, -:\.</p>';
															$output .= '</div>';

															$exit = 1;
														}
														else
														{
															$ci_regex = $_GET['ci_regex'];
														}
													}
													else if($_GET['ci_type'] == $allowed_ci_types[1])
													{
														if(preg_match('/[^a-zA-Z0-9öäüÖÄÜß\s\-\.\,]/',$_GET['ci_regex']) != 0)
														{
															$output .= '<div class="container black-alpha">';
															$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r die Auswahlm&ouml;glichkeiten: a-z, A-Z, 0-9, öäüÖÄÜß, -.,</p>';
															$output .= '</div>';

															$exit = 1;
														}
														else
														{
															$pos = strpos($_GET['ci_regex'],',');

															if(!$pos)
															{
																$output .= '<div class="container black-alpha">';
																$output .= '<p>Verwenden Sie ein Komma um die Auswahlm&ouml;glichkeiten festzulegen.</p>';
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
														$ci_regex = 'a-zA-Z0-9\?\&\=\.\:\/\_\-';
													}
													else if($_GET['ci_type'] == $allowed_ci_types[3])
													{
														$ci_regex = 'a-zA-Z0-9öäüÖÄÜß\s\-\.\,';
													}

													if(!$exit)
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
															$output .= '<div class="panel black-alpha">';
															$output .= '<p><strong>'.$_GET['ci_name'].'</strong> wurde erfolgreich hinzugef&uuml;gt.</p>';
															$output .= '</div>';

															$showform = 1;
														}
														else
														{
															$output .= '<div class="panel black-alpha">';
															$output .= '<p>Es konnte kein CI hinzugef&uuml;gt werden.</p>';
															$output .= '</div>';
														}
													}
												}
												else
												{
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox,URL und Liste.</p>';
													$output .= '</div>';

													$showform = 2;
												}
											}
											else
											{
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Es k&ouml;nnen nur folgende Typen verwendet werden: Zeichenkette,SelectBox,URL und Liste.</p>';
												$output .= '</div>';

												$showform = 2;
											}
										}
									}
									else
									{
										$output .= '<div class="panel black-alpha">';
										$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den CI-Name: a-z, A-Z, öäüÖÄÜß, 0-9, -.</p>';
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
							$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="ci_name" placeholder="CI-Name"/></p>';
							$output .= '<p><input type="hidden" name="send" value="1"/></p>';
						}
						else if($showform == 2)
						{
							$output .= '<input type="hidden" name="ci_name" value="'.$_GET['ci_name'].'"/>';
							$output .= '<div class="section input-default border border-grey">'.$_GET['ci_name'].'</div>';
							$output .= '<p><select class="input-default border border-grey focus-border-light-blue" name="ci_type">';
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
							$output .= '<div class="section input-default border border-grey">'.$_GET['ci_name'].'</div>';

							if($_GET['ci_type'] == $allowed_ci_types[0])
							{
								$output .= '<div class="section input-default border border-grey">Zeichenkette</div>';
								$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="ci_regex" placeholder="CI-Regex"/></p>';
							}
							else if($_GET['ci_type'] == $allowed_ci_types[1])
							{
								$output .= '<div class="section input-default border border-grey">SelectBox</div>';
								$output .= '<p><input class="input-default border border-grey focus-border-light-blue" name="ci_regex" placeholder="Option 1,Option 2"/></p>';
							}
							else if($_GET['ci_type'] == $allowed_ci_types[2])
							{
								$output .= '<div class="section input-default border border-grey">URL</div>';
								$output .= '<input type="hidden" name="ci_regex" value="URL_REGEX"/>';
								$output .= '<div class="section input-default border border-grey focus-border-light-blue">URL_REGEX</div>';
							}
							else if($_GET['ci_type'] == $allowed_ci_types[3])
							{
								$output .= '<div class="section input-default border border-grey">Liste</div>';
								$output .= '<input type="hidden" name="ci_regex" value="LIST_REGEX"/>';
								$output .= '<div class="section input-default border border-grey focus-border-light-blue">LIST_REGEX</div>';
							}

							$output .= '<input type="hidden" name="send" value="3"/>';
						}

						$output .= '<p><button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">weiter <i class="fas fa-arrow-right"></i></button></p>';
						$output .= '</form>';
					}
					else if($_GET['category'] == $allowed_category[3])
					{
						if(!empty($_GET['id']))
						{
							if(preg_match('/[^0-9]/',$_GET['id']) == 0)
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
												$output .= '<div class="panel black-alpha">';
												$output .= '<p>Es wurde keine CI-ID gesendet.</p>';
												$output .= '</div>';
											}
											else
											{
												if(preg_match('/[^0-9]/',$_GET['ci_id']) == 0)
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
															$output .= '<div class="panel black-alpha">';
															$output .= '<p>CI ist bereits vorhanden.</p>';
															$output .= '</div>';
														}
														else
														{
															$allowed_ci_types = array('string','select','url','list');

															$showform = 2;
														}
													}
													else
													{
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Es wurde kein CI gefunden.</p>';
														$output .= '</div>';
													}
												}
												else
												{
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Die CI-ID besteht nur aus Zahlen.</p>';
													$output .= '</div>';
												}
											}
										}
										else if($_GET['send'] == 2)
										{
											$allowed_ci_types = array('string','select','url','list');

											$showform = 2;

											if(empty($_GET['ci_id']) || $_GET['ci_value'] == "")
											{
												$output .= '<div class="panel black-alpha">';

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
												if(preg_match('/[^0-9]/',$_GET['ci_id']) == 0)
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
															$output .= '<div class="panel black-alpha">';
															$output .= '<p>CI ist bereits vorhanden.</p>';
															$output .= '</div>';
														}
														else
														{
															if($row['ci_type'] == $allowed_ci_types[0] || $row['ci_type'] == $allowed_ci_types[2])
															{
																if(preg_match('/[^'.$row['ci_regex'].']/',$_GET['ci_value']) != 0)
																{
																	$regex = str_replace('\s',' Leerzeichen ',$row['ci_regex']);

																	$regex = str_replace('\\','',$row['ci_regex']);

																	$output .= '<div class="panel black-alpha">';
																	$output .= '<p>Verwenden Sie nur folgende Zeichen: '.$regex.'</p>';
																	$output .= '</div>';

																	$exit = 1;
																}
																else
																{
																	$ci_value = $_GET['ci_value'];
																}
															}
															else if($row['ci_type'] == $allowed_ci_types[1])
															{
																if(preg_match('/[^0-9]/',$_GET['ci_value']) != 0)
																{
																	$output .= '<div class="panel black-alpha">';
																	$output .= '<p>W&auml;hlen Sie eine Option aus.</p>';
																	$output .= '</div>';

																	$exit = 1;
																}
																else
																{
																	$regex_arr = json_decode($row['ci_regex']);

																	if(!array_key_exists($_GET['ci_value'],$regex_arr))
																	{
																		$output .= '<div class="panel black-alpha">';
																		$output .= '<p>W&auml;hlen Sie eine Option aus.</p>';
																		$output .= '</div>';

																		$exit = 1;
																	}
																	else
																	{
																		$ci_value = $_GET['ci_value'];
																	}
																}
															}
															else if($row['ci_type'] == $allowed_ci_types[3])
															{
																if(preg_match('/[^'.$row['ci_regex'].']/',$_GET['ci_value']) != 0)
																{
																	$output .= '<div class="panel black-alpha">';
																	$output .= '<p>Verwenden Sie nur folgenden Zeichen in ihrer Liste: a-z, A-Z, 0-9, öäüÖÄÜß, -.,</p>';
																	$output .= '</div>';

																	$exit = 1;
																}
																else
																{
																	$pos = strpos($_GET['ci_value'],',');

																	if(!$pos)
																	{
																		$output .= '<div class="panel black-alpha">';
																		$output .= '<p>Verwenden Sie ein Komma um die Listeneintr&auml;ge zu trennen.</p>';
																		$output .= '</div>';

																		$exit = 1;
																	}
																	else
																	{
																		$ci_value = explode(',',$_GET['ci_value']);
																	}
																}
															}

															if(!$exit)
															{
																$asset_ci = array($_GET['ci_id'],$ci_value);

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
																	$output .= '<div class="panel black-alpha">';
																	$output .= '<p>CI wurde erfolgreich hinzugef&uuml;gt.</p>';
																	$output .= '</div>';

																	$output .= '<script>'."ch_location('view.php?category=asset&id=".$_GET['id']."&tab=cis',2);".'</script>';

																	$showform = 0;
																}
																else
																{
																	$output .= '<div class="panel black-alpha">';
																	$output .= '<p>CI konnte nicht hinzugef&uuml;gt werden.</p>';
																	$output .= '</div>';
																}
															}
														}
													}
													else
													{
														$output .= '<div class="panel black-alpha">';
														$output .= '<p>Es wurde kein CI gefunden.</p>';
														$output .= '</div>';

														$showform = 1;
													}
												}
												else
												{
													$output .= '<div class="panel black-alpha">';
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
											$output .= '<p><select class="input-default border border-grey focus-border-light-blue" name="ci_id">';

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
											$output .= '<div class="section input-default border border-grey">'.$row['ci_name'].'</div>';

											if($row['ci_type'] == $allowed_ci_types[0])
											{
												$regex = str_replace('\s',' Leerzeichen ',$row['ci_regex']);

												$regex = str_replace('\\','',$row['ci_regex']);

												$output .= '<div class="section input-default border border-grey">Zeichenkette</div>';
												$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="ci_value" placeholder="'.$regex.'"/></p>';
											}
											else if($row['ci_type'] == $allowed_ci_types[1])
											{
												$regex_arr = json_decode($row['ci_regex']);

												$output .= '<div class="section input-default border border-grey">SelectBox</div>';
												$output .= '<p><select class="input-default border border-grey focus-border-light-blue" name="ci_value">';
												$output .= '<option value="">Option w&auml;hlen</option>';

												for($i = 0; $i < count($regex_arr); $i++)
												{
													$output .= '<option value="'.$i.'">'.$regex_arr[$i].'</option>';
												}

												$output .= '</select></p>';
											}
											else if($row['ci_type'] == $allowed_ci_types[2])
											{
												$output .= '<div class="section input-default border border-grey">URL</div>';
												$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="url" name="ci_value" placeholder="http://"/></p>';
											}
											else if($row['ci_type'] == $allowed_ci_types[3])
											{
												$output .= '<div class="section input-default border border-grey">Liste</div>';
												$output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="ci_value" placeholder="Eintrag1,Eintrag2,Eintrag3"/></p>';
											}

											$output .= '<input type="hidden" name="send" value="2"/>';
										}

										$output .= '<p><button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">weiter <i class="fas fa-arrow-right"></i></button></p>';
										$output .= '</form>';
									}
								}
								else
								{
									$output .= '<h1>Error</h1>';
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Es wurde kein Asset gefunden.</p>';
									$output .= '</div>';
								}
							}
							else
							{
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Die AssetID besteht nur aus Zahlen.</p>';
								$output .= '</div>';
							}
						}
						else
						{
							$output .= '<h1>Error</h1>';
							$output .= '<div class="panel black-alpha">';
							$output .= '<p>Es wurde keine AssetID gesendet.</p>';
							$output .= '</div>';
						}
					}
					else if($_GET['category'] == $allowed_category[4])
					{
						$lend_user_id = $_SESSION['cart']['user'];
						
						$lend_assets = $_SESSION['cart']['assets'];
						
						if(empty($lend_user_id) || empty($lend_assets) || empty($_GET['lend_end']))
						{
							$output  = '<div class="container">';
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
									if(strlen($_GET['lend_description']) <= 200)
									{	
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
											$lend_start = date('Y-m-d',strtotime('now'));

											$query = sprintf("
											INSERT INTO
											lend
											(lend_creator_id,lend_user_id,lend_assets,lend_archived_assets,lend_description,lend_start,lend_end)
											VALUES
											('%s','%s','%s','%s','%s','%s','%s');",
											$sql->real_escape_string($_SESSION['user']['id']),
											$sql->real_escape_string($lend_user_id),
											$sql->real_escape_string(json_encode($lend_assets)),
											$sql->real_escape_string(json_encode(array())),
											$sql->real_escape_string($lend_description),
											$sql->real_escape_string($lend_start),
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
													$output  = '<div class="container">';
													$output .= '<div class="content-center container white-alpha display-container" style="max-width:600px;">';
													$output .= '<h1>Auftrag erfolgreich</h1>';
													$output .= '<div class="panel black-alpha">';
													$output .= '<p>Leihgabe wurde mit der Nummer '.$row['lend_id'].' im System erfasst.</p>';
													$output .= '</div>'; 
													$output .= '<div class="display-top-right">';
													$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="print.php?category=lend&id='.$row['lend_id'].'"><i class="fas fa-print"></i></a>';
													$output .= '<a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="view.php?category=lend&id='.$row['lend_id'].'"><i class="fas fa-eye"></i></a>';
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
												$output .= '<p>Es konnte kein Eintrag erzeugt werden.</p>';
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
										$output  = '<div class="container">';
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
							else
							{
								$output  = '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Die Bemerkung darf nur 200 Zeichen lang sein und nur folgende Zeichen enthalten: a-z, A-Z, 0-9, öäüÖÄÜß-.</p>';
								$output .= '</div>'; 
								$output .= '</div>'; 
								$output .= '</div>';
							}
						}
					}
					else
					{
						if(!empty($_GET['send']))
						{
							$get = $_GET;

							$category = $get['category'];

							$value = $get[$category.'_name'];

							if(empty($value))
							{
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Bitte f&uuml;llen Sie alle Felder aus.</p>';
								$output .= '</div>';
							}
							else
							{
								if(preg_match('/[^a-zA-Z0-9öäüÖÄÜß\s\-\.]/',$value) == 0)
								{
									$query = sprintf("
									INSERT INTO
									%s
									(%s_name)
									VALUES
									('%s');",
									$sql->real_escape_string($category),
									$sql->real_escape_string($category),
									$sql->real_escape_string($value));

									$sql->query($query);

									if($sql->affected_rows == 1)
									{
										$output .= '<div class="panel black-alpha">';
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
									$output .= '<div class="panel black-alpha">';
									$output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r ihre Eingabe:a-z, A-Z, 0-9, öäüÖÄÜß-.</p>';
									$output .= '</div>';
								}
							}
						}

						$output .= '<form action="add.php" method="get">';
						$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
						$output .= '<ul class="flex section">';
						$output .= '<li class="col-s10 col-m10 col-l10">';
						$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="'.$_GET['category'].'_name" placeholder="'.$category_german[$array_key].'"/>';
						$output .= '</li>';
						$output .= '<li class="col-s2 col-m2 col-l2">';
						$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-arrow-right"></i></button>';
						$output .= '</li>';
						$output .= '</ul>';
						$output .= '<input type="hidden" name="send" value="1"/>';
						$output .= '</form>';
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
