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
				$allowed_category = array('asset','user','ci','lend','vendor','model','type','building','floor','room');

				if(in_array($_GET['category'],$allowed_category))
				{
					$category_german = array('Asset','User','CI','Leihgabe','Hersteller','Modell','Typ','Geb&auml;ude','Stockwerk','Raum');

					$array_key = array_search($_GET['category'],$allowed_category);

					if(preg_match('/[^0-9]/',$_GET['id']) == 0)
					{
						if($_GET['category'] == $allowed_category[0])
						{
							if(empty($_GET['tab']))
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel dark">';
								$output .= '<p>Es wurde kein Tab gew&auml;hlt.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
							else
							{
								if(preg_match('/[^a-z]/',$_GET['tab']) == 0)
								{
									$allowed_tabs = array('general','location','cis','lend');

									if(in_array($_GET['tab'],$allowed_tabs))
									{
										$output .= '<div class="container">';
										$output .= '<h1>Asset anzeigen</h1>';

										if($_GET['tab'] == $allowed_tabs[0])
										{
											$query = sprintf("
											SELECT type_name,vendor_name,model_name,asset_serial
											FROM asset
											INNER JOIN type ON type_id = asset_type_id
											INNER JOIN vendor ON vendor_id = asset_vendor_id
											INNER JOIN model ON model_id = asset_model_id
											WHERE asset_id = '%s'
											LIMIT 1;",
											$sql->real_escape_string($_GET['id']));

											$result = $sql->query($query);

											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$type = $row['type_name'];

												$vendor = $row['vendor_name'];

												$model = $row['model_name'];

												$serial = $row['asset_serial'];

												$output .= '<div class="nowrap overflow-scroll">';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tl border-light-blue white text-light-blue" href="#">Allgemein</a>';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tl border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=location">Lokation</a>';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tlr border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=cis">CIs</a>';
												$output .= '</div>';

												$output .= '<div class="black-alpha">';

												$output .= '<ul class="flex">';
												$output .= '<li class="col-s12 col-m6 col-l6">';
												$output .= '<div class="margin">';
												$output .= '<form action="change.php" method="get">';
												$output .= '<h3>Typ</h3>';
												$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
												$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
												$output .= '<input type="hidden" name="attr" value="type"/>';
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s10 col-m10 col-l10">';
												$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" style="height:53px;" name="attr_value">';
												$output .= '<option value="">'.$type.'</option>';

												$query = "
												SELECT type_id,type_name
												FROM type";

												$result = $sql->query($query);

												while($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$output .= '<option value="'.$row['type_id'].'">'.$row['type_name'].'</option>';
												}

												$output .= '</select>';
												$output .= '<li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';

												$output .= '<li class="col-s12 col-m6 col-l6">';
												$output .= '<div class="margin">';
												$output .= '<form action="change.php" method="get">';
												$output .= '<h3>Hersteller</h3>';
												$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
												$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
												$output .= '<input type="hidden" name="attr" value="vendor"/>';
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s10 col-m10 col-l10">';
												$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" style="height:53px;" name="attr_value">';
												$output .= '<option value="">'.$vendor.'</option>';

												$query = "
												SELECT vendor_id,vendor_name
												FROM vendor";

												$result = $sql->query($query);

												while($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$output .= '<option value="'.$row['vendor_id'].'">'.$row['vendor_name'].'</option>';
												}

												$output .= '</select>';
												$output .= '<li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';
												$output .= '</ul>';

												$output .= '<ul class="flex">';
												$output .= '<li class="col-s12 col-m6 col-l6">';
												$output .= '<div class="margin">';
												$output .= '<form action="change.php" method="get">';
												$output .= '<h3>Modell</h3>';
												$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
												$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
												$output .= '<input type="hidden" name="attr" value="model"/>';
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s10 col-m10 col-l10">';
												$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" style="height:53px;" name="attr_value">';
												$output .= '<option value="">'.$model.'</option>';

												$query = "
												SELECT model_id,model_name
												FROM model";

												$result = $sql->query($query);

												while($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$output .= '<option value="'.$row['model_id'].'">'.$row['model_name'].'</option>';
												}

												$output .= '</select>';
												$output .= '<li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';

												$output .= '<li class="col-s12 col-m6 col-l6">';
												$output .= '<div clasS="margin">';
												$output .= '<form action="change.php" method="get">';
												$output .= '<h3>Seriennummer</h3>';
												$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
												$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
												$output .= '<input type="hidden" name="attr" value="serial"/>';
												$output .= '<ul class="flex section">';
												$output .= '<li class="col-s10 col-m10 col-l10">';
												$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" placeholder="Seriennummer" value="'.$serial.'"/>';
												$output .= '</li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';
												$output .= '</ul>';

												$output .= '</div>';
											}
										}
										else if($_GET['tab'] == $allowed_tabs[1])
										{
											$query = sprintf("
											SELECT building_name,floor_name,room_name
											FROM asset
											INNER JOIN building ON building_id = asset_building_id
											INNER JOIN floor ON floor_id = asset_floor_id
											INNER JOIN room ON room_id = asset_room_id
											WHERE asset_id = '%s';",
											$sql->real_escape_string($_GET['id']));

											$result = $sql->query($query);

											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$building = $row['building_name'];

												$floor = $row['floor_name'];

												$room = $row['room_name'];

												$output .= '<div class="nowrap overflow-scroll">';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tl border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=general">Allgemein</a>';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tl border-light-blue white text-light-blue" href="#">Lokation</a>';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tlr border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=cis">CIs</a>';
												$output .= '</div>';

												$output .= '<div class="black-alpha">';

												$output .= '<ul class="flex">';
												$output .= '<li class="col-s12 col-m6 col-l4">';
												$output .= '<div class="margin">';
												$output .= '<form action="change.php" method="get">';
												$output .= '<h3>Geb&auml;ude</h3>';
												$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
												$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
												$output .= '<input type="hidden" name="attr" value="building"/>';
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s10 col-m10 col-l10">';
												$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" name="attr_value">';
												$output .= '<option value="">'.$building.'</option>';

												$query = "
												SELECT building_id,building_name
												FROM building";

												$result = $sql->query($query);

												while($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$output .= '<option value="'.$row['building_id'].'">'.$row['building_name'].'</option>';
												}

												$output .= '</select>';
												$output .= '<li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';

												$output .= '<li class="col-s12 col-m6 col-l4">';
												$output .= '<div class="margin">';
												$output .= '<form action="change.php" method="get">';
												$output .= '<h3>Stockwerk</h3>';
												$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
												$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
												$output .= '<input type="hidden" name="attr" value="floor"/>';
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s10 col-m10 col-l10">';
												$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" name="attr_value">';
												$output .= '<option value="">'.$floor.'</option>';

												$query = "
												SELECT floor_id,floor_name
												FROM floor";

												$result = $sql->query($query);

												while($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$output .= '<option value="'.$row['floor_id'].'">'.$row['floor_name'].'</option>';
												}

												$output .= '</select>';
												$output .= '<li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';

												$output .= '<li class="col-s12 col-m12 col-l4">';
												$output .= '<div class="margin">';
												$output .= '<form action="change.php" method="get">';
												$output .= '<h3>Raum</h3>';
												$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
												$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
												$output .= '<input type="hidden" name="attr" value="room"/>';
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s10 col-m10 col-l10">';
												$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" name="attr_value">';
												$output .= '<option value="">'.$room.'</option>';

												$query = "
												SELECT room_id,room_name
												FROM room";

												$result = $sql->query($query);

												while($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$output .= '<option value="'.$row['room_id'].'">'.$row['room_name'].'</option>';
												}

												$output .= '</select>';
												$output .= '<li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';
												$output .= '</ul>';

												$output .= '</div>';
											}
										}
										else if($_GET['tab'] == $allowed_tabs[2])
										{
											$query = sprintf("
											SELECT asset_cis
											FROM asset
											WHERE asset_id = '%s';",
											$sql->real_escape_string($_GET['id']));

											$result = $sql->query($query);

											if($row = $result->fetch_array(MYSQLI_ASSOC))
											{
												$output .= '<div class="nowrap overflow-scroll">';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tl border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=general">Allgemein</a>';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tl border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=location">Lokation</a>';
												$output .= '<a class="col-s6 col-m4 col-l4 btn-default border border-tlr border-light-blue white text-light-blue" href="#">CIs</a>';
												$output .= '</div>';

												$output .= '<div class="display-container black-alpha">';

												$asset_cis = json_decode($row['asset_cis']);

												$asset_cis_count = count($asset_cis);

												if($asset_cis_count > 0)
												{
													$j = 0;

													$output .= '<ul class="flex">';

													for($i = 0; $i < count($asset_cis); $i++)
													{
														$asset_ci = $asset_cis[$i];

														$ci_id = $asset_ci[0];

														$ci_value = $asset_ci[1];

														$query = sprintf("
														SELECT ci_name,ci_type,ci_regex
														FROM ci
														WHERE ci_id = '%s';",
														$sql->real_escape_string($ci_id));

														$result = $sql->query($query);

														if($row = $result->fetch_array(MYSQLI_ASSOC))
														{
															if($row['ci_type'] == 'string' || $row['ci_type'] == 'select' || $row['ci_type'] == 'url')
															{
																$output .= '<li class="col-s12 col-m12 col-l6">';
																$output .= '<div class="margin">';

																$output .= '<form action="change.php" method="get">';
																$output .= '<table><tr>';
																$output .= '<td><a href="del.php?category=cis&id='.$_GET['id'].'&ci_id='.$i.'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab']).'"><i class="fas fa-times medium hover-rotate-90"></i></a>&nbsp;&nbsp;&nbsp;</td>';
																$output .= '<td><h3>'.$row['ci_name'].'</h3></td>';
																$output .= '</tr></table>';
																$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
																$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
																$output .= '<input type="hidden" name="attr" value="cis"/>';
																$output .= '<input type="hidden" name="ci_id" value="'.$i.'"/>';
																$output .= '<ul class="flex">';

																if($row['ci_type'] == 'string' || $row['ci_type'] == 'select')
																{
																	$output .= '<li class="col-s10 col-m10 col-l10">';

																	if($row['ci_type'] == 'string')
																	{
																		$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" placeholder="'.$row['ci_regex'].'" value="'.$ci_value.'"/>';
																	}
																	else if($row['ci_type'] == 'select')
																	{
																		$ci_regex = json_decode($row['ci_regex']);

																		$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" name="attr_value">';
																		$output .= '<option disabled selected value="">'.$ci_regex[$ci_value].'</option>';

																		for($I = 0; $I < count($ci_regex); $I++)
																		{
																			$output .= '<option value="'.$I.'">'.$ci_regex[$I].'</option>';
																		}

																		$output .= '</select>';
																	}

																	$output .= '</li>';
																	$output .= '<li class="col-s2 col-m2 col-l2">';
																	$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
																	$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
																	$output .= '</li>';
																}
																else if($row['ci_type'] == 'url')
																{
																	$host = parse_url($ci_value,PHP_URL_HOST);

																	$output .= '<li class="col-s8 col-m8 col-l8">';
																	$output .= '<div id="attr-show-'.$i.'" class="input-default border border-tbl border-grey nowrap overflow-hide"><a href="'.$ci_value.'" target="_blank">'.$ci_value.'</a></div>';
																	$output .= '<input id="attr-input-'.$i.'" class="input-default border border-grey focus-border-light-blue" style="display:none;" type="url" name="attr_value" value="'.$ci_value.'"/>';
																	$output .= '</li>';
																	$output .= '<li class="col-s2 col-m2 col-l2">';
																	$output .= '<div id="attr-placeholder-'.$i.'" class="input-default border border-tb border-grey text-center">';

																	if($host == $_SERVER['HTTP_HOST'])
																	{
																		$output .= '<i class="fas fa-link"></i>';
																	}
																	else
																	{
																		$output .= '<i class="fas fa-external-link-alt"></i>';
																	}

																	$output .= '</div>';
																	$output .= '<button id="attr-cancel-'.$i.'" onclick="cedit('.$i.');" class="block btn-default border border-red red hover-white hover-text-red" type="button" style="display:none;"><i class="fas fa-times"></i></button>';
																	$output .= '</li>';
																	$output .= '<li class="col-s2 col-m2 col-l2">';
																	$output .= '<button id="attr-edit-'.$i.'" onclick="edit('.$i.');" class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="button"><i class="fas fa-edit"></i></button>';
																	$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
																	$output .= '<button id="attr-save-'.$i.'" class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit" style="display:none;"><i class="fas fa-save"></i></button>';
																	$output .= '</li>';
																}

																$output .= '</ul>';
																$output .= '</form>';
																$output .= '</div>';
																$output .= '</li>';
															}
															else if($row['ci_type'] == 'list')
															{
																$ci_value_str = implode(',',$ci_value);

																$output .= '<li class="col-s12 col-m12 col-l12">';
																$output .= '<div class="margin">';

																$output .= '<form action="change.php" method="get">';
																$output .= '<table><tr>';
																$output .= '<td><a href="del.php?category=cis&id='.$_GET['id'].'&ci_id='.$i.'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab']).'"><i class="fas fa-times medium hover-rotate-90"></i></a>&nbsp;&nbsp;&nbsp;</td>';
																$output .= '<td><h3>'.$row['ci_name'].'</h3></td>';
																$output .= '</tr></table>';
																$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
																$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
																$output .= '<input type="hidden" name="attr" value="cis"/>';
																$output .= '<input type="hidden" name="ci_id" value="'.$i.'"/>';

																$output .= '<div id="attr-show-'.$i.'">';

																for($J = 0; $J < count($ci_value); $J++)
																{
																	$output .= '<div class="margint inline container border border-light-blue light-blue"><p>'.$ci_value[$J].'</p></div> ';
																}

																$output .= '&nbsp;&nbsp;&nbsp;<i onclick="edit('.$i.');" class="fas fa-edit medium"></i>';
																$output .= '</div>';
																$output .= '<div id="attr-input-'.$i.'" style="display:none;">';
																$output .= '<ul class="flex">';
																$output .= '<li class="col-s2 col-m2 col-l1">';
																$output .= '<button onclick="cedit('.$i.');" class="block btn-default border border-red red hover-white hover-text-red" type="button"><i class="fas fa-times"></i></button>';
																$output .= '</li>';
																$output .= '<li class="col-s8 col-m8 col-l10">';
																$output .= '<input class="input-default border border-tb border-grey focus-border-light-blue" name="attr_value" value="'.$ci_value_str.'"/>';
																$output .= '</li>';
																$output .= '<li class="col-s2 col-m2 col-l1">';
																$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
																$output .= '</li>';
																$output .= '</ul>';
																$output .= '</div>';
																$output .= '</form>';
																$output .= '</div>';
																$output .= '</li>';
															}
														}
													}

													$output .= '</ul>';
													$output .= '<div class="container">';
													$output .= '<p><a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="add.php?category=cis&id='.$_GET['id'].'">CI hinzuf&uuml;gen <i class="fas fa-plus"></i></a></p>';
													$output .= '</div>';
												}
												else
												{
													$output .= '<div class="container">';
													$output .= '<table class="block section"><tr>';
													$output .= '<td>Es wurden noch keine CIs hinzugef&uuml;gt.</td>';
													$output .= '<td class="text-right"><a class="btn-default border border-light-blue light-blue hover-white hover-text-blue" href="add.php?category=cis&id='.$_GET['id'].'"><i class="fas fa-plus"></i></a></td>';
													$output .= '</tr></table>';
													$output .= '</div>';
												}

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
										$output .= '<p>Es sind nur folgende Tabs vorhanden: Allgemein, Lokation und CIs</p>';
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
									$output .= '<p>Es sind nur folgende Tabs vorhanden: Allgemein, Lokation und CIs.</p>';
									$output .= '</div>';
									$output .= '</div>';
									$output .= '</div>';
								}
							}
						}
						else if($_GET['category'] == $allowed_category[1])
						{
							if(empty($_GET['tab']))
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Es wurde kein Tab gew&auml;hlt.</p>';
								$output .= '</div>';
								$output .= '</div>';
								$output .= '</div>';
							}
							else
							{
								if(preg_match('/[^a-z]/',$_GET['tab']) == 0)
								{
									$allowed_tabs = array('general','location','lend');

									if(in_array($_GET['tab'],$allowed_tabs))
									{
										$query = sprintf("
										SELECT user_admin,user_active
										FROM user
										WHERE user_id = '%s';",
										$sql->real_escape_string($_GET['id']));

										$result = $sql->query($query);

										if($row = $result->fetch_array(MYSQLI_ASSOC))
										{
											$user_admin = $row['user_admin'];

											$user_active = $row['user_active'];

											$output .= '<div class="container">';
											$output .= '<h1>User anzeigen</h1>';

											if($_GET['tab'] == $allowed_tabs[0])
											{
												$query = sprintf("
												SELECT user_id,user_vname,user_name,user_email,rank_name_long
												FROM user
												INNER JOIN rank ON user_rank_id = rank_id
												WHERE user_id = '%s';",
												$sql->real_escape_string($_GET['id']));

												$result = $sql->query($query);

												if($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$vname = $row['user_vname'];

													$name = $row['user_name'];

													$email = $row['user_email'];

													$output .= '<div class="nowrap overflow-scroll">';
													$output .= '<a class="col-s6 col-m6 col-l6 btn-default border border-tl border-light-blue white text-light-blue" href="#">Allgemein</a>';
													$output .= '<a class="col-s6 col-m6 col-l6 btn-default border border-tlr border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=location">Lokation</a>';
													$output .= '</div>';

													$output .= '<div class="black-alpha">';

													$output .= '<ul class="flex">';

													$output .= '<li class="col-s12 col-m6 col-l6">';
													$output .= '<div class="margin">';
													$output .= '<h3>Personalnummer</h3>';
													$output .= '<div class="section input-default border border-grey">'.$row['user_id'].'</div>';
													$output .= '</div>';
													$output .= '</li>';

													$output .= '<li class="col-s12 col-m6 col-l6">';
													$output .= '<div class="margin">';
													$output .= '<form action="change.php" method="get">';
													$output .= '<h3>Dienstgrad</h3>';
													$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
													$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
													$output .= '<input type="hidden" name="attr" value="rank"/>';
													$output .= '<ul class="flex section">';
													$output .= '<li class="col-s10 col-m10 col-l10">';
													$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" style="height:53px;" name="attr_value">';
													$output .= '<option disabled selected value="">'.$row['rank_name_long'].'</option>';

													$query = "
													SELECT rank_id,rank_name_long
													FROM rank";

													$result = $sql->query($query);

													while($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$output .= '<option value="'.$row['rank_id'].'">'.$row['rank_name_long'].'</option>';
													}

													$output .= '</select>';
													$output .= '</li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';
													$output .= '</div>';
													$output .= '</li>';

													$output .= '</ul>';
													$output .= '<ul class="flex">';

													$output .= '<li class="col-s12 col-m6 col-l6">';
													$output .= '<div class="margin">';
													$output .= '<form action="change.php" method="get">';
													$output .= '<h3>Vorname</h3>';
													$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
													$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
													$output .= '<input type="hidden" name="attr" value="vname"/>'; 
													$output .= '<ul class="flex">';
													$output .= '<li class="col-s10 col-m10 col-l10">';
													$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" placeholder="Vorname" value="'.$vname.'"/>';
													$output .= '</li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';
													$output .= '</div>';
													$output .= '</li>';

													$output .= '<li class="col-s12 col-m6 col-l6">';
													$output .= '<div class="margin">';
													$output .= '<form action="change.php" method="get">';
													$output .= '<h3>Nachname</h3>';
													$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
													$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
													$output .= '<input type="hidden" name="attr" value="name"/>';
													$output .= '<ul class="flex">';
													$output .= '<li class="col-s10 col-m10 col-l10">';
													$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" placeholder="Nachname" value="'.$name.'"/>';
													$output .= '</li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';
													$output .= '</div>';
													$output .= '</li>';

													$output .= '</ul>';
													$output .= '<div class="container">';

													$output .= '<form action="change.php" method="get">';
													$output .= '<h3>E-Mail-Adresse</h3>';
													$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
													$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
													$output .= '<input type="hidden" name="attr" value="email"/>';
													$output .= '<ul class="flex section">';
													$output .= '<li class="col-s10 col-m10 col-l10">';
													$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" placeholder="E-Mail-Adresse" value="'.$email.'"/>';
													$output .= '</li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';

													$output .= '</div>';
													$output .= '</div>';
												}
											}
											else if($_GET['tab'] == $allowed_tabs[1])
											{
												$query = sprintf("
												SELECT building_name,floor_name,room_name
												FROM user
												INNER JOIN building ON building_id = user_building_id
												INNER JOIN floor ON floor_id = user_floor_id
												INNER JOIN room ON room_id = user_room_id
												WHERE user_id = '%s';",
												$sql->real_escape_string($_GET['id']));

												$result = $sql->query($query);

												if($row = $result->fetch_array(MYSQLI_ASSOC))
												{
													$building = $row['building_name'];

													$floor = $row['floor_name'];

													$room = $row['room_name'];

													$output .= '<div class="nowrap overflow-scroll">';
													$output .= '<a class="col-s6 col-m6 col-l6 btn-default border border-tl border-light-blue light-blue hover-white hover-text-blue" href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=general">Allgemein</a>';
													$output .= '<a class="col-s6 col-m6 col-l6 btn-default border border-tlr border-light-blue white text-light-blue" href="#">Lokation</a>';
													$output .= '</div>';

													$output .= '<div class="black-alpha">';

													$output .= '<ul class="flex">';

													$output .= '<li class="col-s12 col-m6 col-l4">';
													$output .= '<div class="margin">';
													$output .= '<form action="change.php" method="get">';
													$output .= '<h3>Geb&auml;ude</h3>';
													$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
													$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
													$output .= '<input type="hidden" name="attr" value="building"/>';
													$output .= '<ul class="flex">';
													$output .= '<li class="col-s10 col-m10 col-l10">';
													$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" style="height:53px;" name="attr_value">';
													$output .= '<option disabled selected value="">'.$building.'</option>';

													$query = "
													SELECT building_id,building_name
													FROM building";

													$result = $sql->query($query);

													while($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$output .= '<option value="'.$row['building_id'].'">'.$row['building_name'].'</option>';
													}

													$output .= '</select>';
													$output .= '<li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';
													$output .= '</div>';
													$output .= '</li>';

													$output .= '<li class="col-s12 col-m6 col-l4">';
													$output .= '<div class="margin">';
													$output .= '<form action="change.php" method="get">';
													$output .= '<h3>Stockwerk</h3>';
													$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
													$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
													$output .= '<input type="hidden" name="attr" value="floor"/>';
													$output .= '<ul class="flex">';
													$output .= '<li class="col-s10 col-m10 col-l10">';
													$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" style="height:53px;" name="attr_value">';
													$output .= '<option disabled selected value="">'.$floor.'</option>';

													$query = "
													SELECT floor_id,floor_name
													FROM floor";

													$result = $sql->query($query);

													while($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$output .= '<option value="'.$row['floor_id'].'">'.$row['floor_name'].'</option>';
													}

													$output .= '</select>';
													$output .= '<li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';
													$output .= '</div>';
													$output .= '</li>';

													$output .= '<li class="col-s12 col-m12 col-l4">';
													$output .= '<div class="margin">';
													$output .= '<form action="change.php" method="get">';
													$output .= '<h3>Raum</h3>';
													$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
													$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
													$output .= '<input type="hidden" name="attr" value="room"/>';
													$output .= '<ul class="flex">';
													$output .= '<li class="col-s10 col-m10 col-l10">';
													$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" style="height:53px;" name="attr_value">';
													$output .= '<option disabled selected value="">'.$room.'</option>';

													$query = "
													SELECT room_id,room_name
													FROM room";

													$result = $sql->query($query);

													while($row = $result->fetch_array(MYSQLI_ASSOC))
													{
														$output .= '<option value="'.$row['room_id'].'">'.$row['room_name'].'</option>';
													}

													$output .= '</select>';
													$output .= '<li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';
													$output .= '</div>';
													$output .= '</li>';
													$output .= '</ul>';

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
											$output .= '<p>Es konnte kein User gefunden werden.</p>';
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
										$output .= '<p>Es sind nur folgende Tabs vorhanden: Allgemein und Lokation.</p>';
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
									$output .= '<p>Es sind nur folgende Tabs vorhanden: Allgemein und Lokation.</p>';
									$output .= '</div>';
									$output .= '</div>';
									$output .= '</div>';
								}
							}
						}
						else if($_GET['category'] == $allowed_category[2])
						{
							$query = sprintf("
							SELECT ci_name,ci_type,ci_regex
							FROM ci
							WHERE ci_id = '%s';",
							$sql->real_escape_string($_GET['id']));

							$result = $sql->query($query);

							if($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>CI anzeigen</h1>';

								$output .= '<form action="change.php" method="get">';
								$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
								$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
								$output .= '<input type="hidden" name="attr" value="name"/>';
								$output .= '<ul class="flex section">';
								$output .= '<li class="col-s10 col-m10 col-l10">';
								$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" placeholder="CI-Name" value="'.$row['ci_name'].'"/>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
								$output .= '</li>';
								$output .= '</ul>';
								$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'"/>';
								$output .= '</form>';

								$output .= '<form action="change.php" method="get">';
								$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
								$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
								$output .= '<input type="hidden" name="attr" value="type"/>';
								$output .= '<ul class="flex section">';
								$output .= '<li class="col-s10 col-m10 col-l10">';
								$output .= '<select class="input-default border border-tbl border-grey focus-border-light-blue" name="attr_value">';
								$output .= '<option selected disabled value="">';

								if($row['ci_type'] == 'string')
								{
									$output .= 'Zeichenkette';
								}
								else if($row['ci_type'] == 'select')
								{
									$output .= 'SelectBox';
								}
								else if($row['ci_type'] == 'url')
								{
									$output .= 'URL';
								}
								else if($row['ci_type'] == 'list')
								{
									$output .= 'Liste';
								}

								$output .= '</option>';
								$output .= '<option value="string">Zeichenkette</option>';
								$output .= '<option value="select">Selectbox</option>';
								$output .= '<option value="url">URL</option>';
								$output .= '<option value="list">Liste</option>';
								$output .= '</select>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
								$output .= '</li>';
								$output .= '</ul>';
								$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'"/>';
								$output .= '</form>';

								if($row['ci_type'] == 'string' || $row['ci_type'] == 'select')
								{
									$output .= '<form action="change.php" method="get">';
									$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
									$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
									$output .= '<input type="hidden" name="attr" value="regex"/>';
									$output .= '<ul class="flex section">';
									$output .= '<li class="col-s10 col-m10 col-l10">';

									if($row['ci_type'] == 'string')
									{
										$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" placeholder="CI-Regex" value="'.$row['ci_regex'].'"/>';
									}
									else if($row['ci_type'] == 'select')
									{
										$regex_arr = json_decode($row['ci_regex']);

										$regex = implode(',',$regex_arr);

										$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" value="'.$regex.'"/>';
									}

									$output .= '</li>';
									$output .= '<li class="col-s2 col-m2 col-l2">';
									$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
									$output .= '</li>';
									$output .= '</ul>';
									$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'"/>';
									$output .= '</form>';
								}
								else if($row['ci_type'] == 'url')
								{
									$output .= '<div class="section input-default border border-grey">URL_REGEX</div>';
								}
								else if($row['ci_type'] == 'list')
								{
									$output .= '<div class="section input-default border border-grey">LIST_REGEX</div>';
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
						else if($_GET['category'] == $allowed_category[3])
						{
							$query = sprintf("
							SELECT user_vname,user_name,lend_user_id,lend_assets,lend_description,lend_start,lend_end
							FROM lend
							INNER JOIN user ON user_id = lend_user_id
							WHERE lend_id = '%s';",
							$sql->real_escape_string($_GET['id']));
							
							$result = $sql->query($query);
							
							if($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								$lend_assets = json_decode($row['lend_assets']);
								
								$output .= '<div class="panel">';
								$output .= '<div class="container white-alpha">';
								$output .= '<h1>Leihgabe anzeigen</h1>';
								$output .= '<h2>User</h2>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>'.$row['lend_user_id'].'</p>';
								$output .= '<p>'.$row['user_name'].', '.$row['user_vname'].'</p>';
								$output .= '</div>';
								
								
								$output .= '</div>';
								$output .= '</div>';
							}
						}
						else
						{
							$query = sprintf("
							SELECT %s_name
							FROM %s
							WHERE %s_id = '%s';",
							$sql->real_escape_string($_GET['category']),
							$sql->real_escape_string($_GET['category']),
							$sql->real_escape_string($_GET['category']),
							$sql->real_escape_string($_GET['id']));

							$result = $sql->query($query);

							if($row = $result->fetch_array(MYSQLI_NUM))
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>'.$category_german[$array_key].' anzeigen</h1>';
								$output .= '<form action="change.php" method="get">';
								$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
								$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
								$output .= '<input type="hidden" name="attr" value="name"/>';
								$output .= '<ul class="flex section">';
								$output .= '<li class="col-s10 col-m10 col-l10">';
								$output .= '<input class="input-default border border-tbl border-grey focus-border-light-blue" type="text" name="attr_value" placeholder="'.$category_german[$array_key].'" value="'.$row[0].'"/>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit"><i class="fas fa-save"></i></button>';
								$output .= '</li>';
								$output .= '</ul>';
								$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'"/>';
								$output .= '</form>';
								$output .= '</div>';
								$output .= '</div>';
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white-alpha">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel black-alpha">';
								$output .= '<p>Es wurde kein '.$category_german[$array_key].' mit der gesendeten ID gefunden.</p>';
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
}
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #View</title>
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
