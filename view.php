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
			if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['category']) == 0)
			{
				$allowed_category = array('asset','user','ci','vendor','model','type','building','floor','room');
				
				if(in_array($_GET['category'],$allowed_category))
				{
					$category_german = array('Asset','User','CI','Hersteller','Modell','Typ','Geb&auml;ude','Stockwerk','Raum');
					
					$key = array_search($_GET['category'],$allowed_category);
					
					if(preg_match('/[^'.$app_regex['number'].']/',$_GET['id']) == 0)
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
								if(preg_match('/[^'.$app_regex['loweruml'].']/',$_GET['tab']) == 0)
								{
									$allowed_tabs = array('general','location','cis');
									
									if(in_array($_GET['tab'],$allowed_tabs))
									{
										$output .= '<div class="container">';
										$output .= '<h1>Asset anzeigen</h1>';
										$output .= '</div>';
										
										$output .= '<ul class="flex">';
										$output .= '<li class="col-s12 col-m12 col-l9">';
										$output .= '<div class="margin">';
												
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
														
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="#" class="block btn-default light-blue">Allgemein</a>';
												$output .= '</li>';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=location" class="block btn-default white">Lokation</a>';
												$output .= '</li>';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=cis" class="block btn-default white">CIs</a>';
												$output .= '</li>';
												$output .= '</ul>';
														
												$output .= '<div class="dark">';
												
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
												$output .= '<select class="ipt-default" name="attr_value">';
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
												$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
												$output .= '<select class="ipt-default" name="attr_value">';
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
												$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
												$output .= '<select class="ipt-default" name="attr_value">';
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
												$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
												$output .= '<input class="ipt-default" type="text" name="attr_value" placeholder="Seriennummer" value="'.$serial.'"/>';
												$output .= '</li>';
												$output .= '<li class="col-s2 col-m2 col-l2">';
												$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';
												$output .= '</ul>';
														
												$output .= '</div>';
												
											}
											else
											{
												$not_found = 1;
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
														
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=general" class="block btn-default white">Allgemein</a>';
												$output .= '</li>';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="#" class="block btn-default light-blue">Lokation</a>';
												$output .= '</li>';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=cis" class="block btn-default white">CIs</a>';
												$output .= '</li>';
												$output .= '</ul>';
														
												$output .= '<div class="dark">';
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
												$output .= '<select class="ipt-default" name="attr_value">';
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
												$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
												$output .= '<select class="ipt-default" name="attr_value">';
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
												$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
												$output .= '<select class="ipt-default" name="attr_value">';
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
												$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
												$output .= '</form>';
												$output .= '</div>';
												$output .= '</li>';
												$output .= '</ul>';
												$output .= '</div>';
											}
											else
											{
												$not_found = 1;
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
												$output .= '<ul class="flex">';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=general" class="block btn-default white">Allgemein</a>';
												$output .= '</li>';
												$output .= '<li class="col-s4 col-m4 col-l4">';
												$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=location" class="block btn-default white">Lokation</a>';
												$output .= '</li>';
												$output .= '<li class="col-s4 col-m3 col-l3">';
												$output .= '<a href="#" class="block btn-default light-blue">CIs</a>';
												$output .= '</li>';
												$output .= '<li class="hide-small col-m1 col-l1">';
												$output .= '<a href="/add.php?category=cis&id='.$_GET['id'].'" class="block btn-default white"><i class="fas fa-plus"></i></a>';
												$output .= '</li>';
												$output .= '</ul>';
												
												$output .= '<div class="dark">';
												
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
															if($j == 2)
															{
																$output .= '</ul>';
																$output .= '<ul class="flex">';
																		
																$j = 0;
															}
																	
															$output .= '<li class="col-s12 col-m12 col-l6">';
															$output .= '<div class="margin">';
															
															$output .= '<form action="change.php" method="get">';
															$output .= '<table><tr>';
															$output .= '<td><a href="del.php?category=cis&id='.$_GET['id'].'&ci_id='.$i.'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab']).'"><i class="fas fa-times medium"></i></a>&nbsp;</td>';
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
																	$output .= '<input class="ipt-default" type="text" name="attr_value" placeholder="'.$row['ci_regex'].'" value="'.$ci_value.'"/>';
																}
																else if($row['ci_type'] == 'select')
																{
																	$ci_regex = json_decode($row['ci_regex']);
																		
																	$output .= '<select class="ipt-default" name="attr_value">';
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
																$output .= '<button class="btn-default block light-blue" type="submit"><i class="fas fa-save"></i></button>';
																$output .= '</li>';
															}
															else if($row['ci_type'] == 'url')
															{	
																$host = parse_url($ci_value,PHP_URL_HOST);
																		
																$output .= '<li class="col-s8 col-m8 col-l8">';
																$output .= '<div id="attr-show-'.$i.'" class="ipt-default nowrap overflow-hide"><a href="'.$ci_value.'" target="_blank">'.$ci_value.'</a></div>';
																$output .= '<input id="attr-input-'.$i.'" class="ipt-default" style="display:none;" type="url" name="attr_value" value="'.$ci_value.'"/>';
																$output .= '</li>';
																$output .= '<li class="col-s2 col-m2 col-l2">';
																$output .= '<div id="attr-placeholder-'.$i.'" class="ipt-default text-center">';
																		
																if($host == $_SERVER['HTTP_HOST'])
																{
																	$output .= '<i class="fas fa-link"></i>';
																}
																else
																{
																	$output .= '<i class="fas fa-external-link-alt"></i>';
																}
																		
																$output .= '</div>';
																$output .= '<button id="attr-cancel-'.$i.'" onclick="cEdit('.$i.');" class="block btn-default red" type="button" style="display:none;"><i class="fas fa-times"></i></button>';
																$output .= '</li>';
																$output .= '<li class="col-s2 col-m2 col-l2">';
																$output .= '<button id="attr-edit-'.$i.'" onclick="edit('.$i.');" class="block btn-default light-blue" type="button"><i class="fas fa-edit"></i></button>';
																$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
																$output .= '<button id="attr-save-'.$i.'" class="block btn-default light-blue" type="submit" style="display:none;"><i class="fas fa-save"></i></button>';
																$output .= '</li>';
															}
																	
															$output .= '</form>';
															$output .= '</div>';
															$output .= '</li>';
																	
															$j++;
														}
													}
													
													$output .= '</ul>';
												}
												else
												{
													$output .= '<div class="container">';
													$output .= '<p>Es wurden noch keine CIs hinzugef&uuml;gt.</p>';
													$output .= '</div>';
												}
												
												$output .= '<div class="hide-large hide-medium container">';
												$output .= '<p><a href="/add.php?category=cis&id='.$_GET['id'].'" class="block btn-default light-blue"><i class="fas fa-plus"></i></a></p>';
												$output .= '</div>';
												$output .= '</div>';
											}
											else
											{
												$not_found = 1;
											}
										}
										
										$output .= '</div>';
										$output .= '</li>';
										$output .= '<li class="col-s12 col-m12 col-l3">';
										$output .= '<div class="margin dark">';
										$output .= '<div class="container">';
										$output .= '<h3>Aktionen</h3>';
										$output .= '</div>';
										$output .= '<ul class="flex">';
										$output .= '<li class="col-s6 col-m6 col-l12">';
										$output .= '<div class="margin">';
										$output .= '<a class="block btn-default light-blue" href="cart.php?aktion=add&category='.$_GET['category'].'&id='.$_GET['id'].'"><i class="fas fa-shopping-cart"></i> <i class="fas fa-plus"></i></a>';
										$output .= '</div>';
										$output .= '</li>';
										$output .= '<li class="col-s6 col-m6 col-l12">';
										$output .= '<div class="margin">';
										$output .= '<a class="block btn-default light-blue" href="del.php?category='.$_GET['category'].'&id='.$_GET['id'].'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/list.php?category='.$_GET['category'].'&site=0&site_amount=5').'"><i class="fas fa-trash"></i></a>';
										$output .= '</div>';
										$output .= '</li>';
										$output .= '</ul>';
										$output .= '</div>';
										$output .= '</li>';
										$output .= '</ul>';
										
										if(!empty($not_found))
										{
											$output  = '<div class="container">';
											$output .= '<div class="content-center white container">';
											$output .= '<div class="panel dark">';
											$output .= '<p>Es wurde kein Asset gefunden.</p>';
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
										$output .= '<p>Es sind nur folgende Tabs vorhanden: Allgemein, Lokation, CIs.</p>';
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
									$output .= '<p>Es sind nur folgende Tabs vorhanden: Allgemein, Lokation, CIs.</p>';
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
									$allowed_tabs = array('general','location');
									
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
											$output .= '</div>';
											
											$output .= '<ul class="flex">';
											$output .= '<li class="col-s12 col-m12 col-l9">';
											$output .= '<div class="margin">';
								
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
														
													$output .= '<ul class="flex">';
													$output .= '<li class="col-s6 col-m6 col-l6">';
													$output .= '<a href="#" class="block btn-default light-blue">Allgemein</a>';
													$output .= '</li>';
													$output .= '<li class="col-s6 col-m6 col-l6">';
													$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=location" class="block btn-default white">Lokation</a>';
													$output .= '</li>';
													$output .= '</ul>';
														
													$output .= '<div class="dark">';
														
													$output .= '<ul class="flex">';
														
													$output .= '<li class="col-s12 col-m6 col-l6">';
													$output .= '<div class="margin">';
													$output .= '<h3>Personalnummer</h3>';
													$output .= '<p><input class="ipt-default" readonly="true" disabled="true" type="number" placeholder="Personalnummer" value="'.$row['user_id'].'"/></p>';
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
													$output .= '<select class="ipt-default" name="attr_value">';
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
													$output .= '<button class="btn-default block light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
													$output .= '<input class="ipt-default" type="text" name="attr_value" placeholder="Vorname" value="'.$vname.'"/>';
													$output .= '</li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="btn-default block light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
													$output .= '<input class="ipt-default" type="text" name="attr_value" placeholder="Nachname" value="'.$name.'"/>';
													$output .= '</li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
													$output .= '<input class="ipt-default" type="text" name="attr_value" placeholder="E-Mail-Adresse" value="'.$email.'"/>';
													$output .= '</li>';
													$output .= '<li class="col-s2 col-m2 col-l2">';
													$output .= '<button class="btn-default block light-blue" type="submit"><i class="fas fa-save"></i></button>';
													$output .= '</li>';
													$output .= '</ul>';
													$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab'].'"/>';
													$output .= '</form>';
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
														
													$output .= '<ul class="flex">';
													$output .= '<li class="col-s6 col-m6 col-l6">';
													$output .= '<a href="view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab=general" class="block btn-default white">Allgemein</a>';
													$output .= '</li>';
													$output .= '<li class="col-s6 col-m6 col-l6">';
													$output .= '<a href="#" class="block btn-default light-blue">Lokation</a>';
													$output .= '</li>';
													$output .= '</ul>';
														
													$output .= '<div class="dark">';
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
													$output .= '<select class="ipt-default" name="attr_value">';
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
													$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
													$output .= '<select class="ipt-default" name="attr_value">';
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
													$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
													$output .= '<select class="ipt-default" name="attr_value">';
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
													$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
											
											$output .= '</div>';
											$output .= '</li>';
											$output .= '<li class="col-s12 col-m12 col-l3">';
											$output .= '<div class="margin dark">';
											$output .= '<div class="container">';
											$output .= '<h3>Aktionen</h3>';
											$output .= '</div>';
											$output .= '<ul class="flex">';
											$output .= '<li class="col-s6 col-m3 col-l12">';
											$output .= '<div class="margin">';
											$output .= '<a href="cart.php?aktion=add&category='.$_GET['category'].'&id='.$_GET['id'].'" class="block btn-default light-blue"><i class="fas fa-shopping-cart"></i> <i class="fas fa-plus"></i></a>';
											$output .= '</div>';
											$output .= '</li>';
											$output .= '<li class="col-s6 col-m3 col-l12">';
											$output .= '<div class="margin">';
											
											if($user_active)
											{
												$status = 0;
												$icon = 'fas fa-times';
											}
											else
											{
												$status = 1;
												$icon = 'fas fa-check';
											}
											
											$output .= '<a href="change.php?category='.$_GET['category'].'&id='.$_GET['id'].'&attr=active&attr_value='.$status.'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab']).'" class="block btn-default light-blue"><i class="'.$icon.'"></i></a>';
											$output .= '</div>';
											$output .= '</li>';
											$output .= '<li class="col-s6 col-m3 col-l12">';
											$output .= '<div class="margin">';
											
											if($user_admin)
											{
												$status = 0;
												$icon = 'fas fa-arrow-down';
											}
											else
											{
												$status = 1;
												$icon = 'fas fa-arrow-up';
											}
											
											$output .= '<a href="change.php?category='.$_GET['category'].'&id='.$_GET['id'].'&attr=admin&attr_value='.$status.'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'&tab='.$_GET['tab']).'" class="block btn-default light-blue"><i class="'.$icon.'"></i></a>';
											$output .= '</div>';
											$output .= '</li>';
											$output .= '<li class="col-s6 col-m3 col-l12">';
											$output .= '<div class="margin">';
											$output .= '<a href="del.php?category='.$_GET['category'].'&id='.$_GET['id'].'&returnto='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/list.php?category='.$_GET['category'].'&site=0&site_amount=5').'" class="block btn-default light-blue"><i class="fas fa-trash"></i></a>';
											$output .= '</div>';
											$output .= '</li>';
											$output .= '</ul>';
											$output .= '</div>';
											$output .= '</li>';
											$output .= '</ul>';
										}
										else
										{
											$output .= '<div class="container">';
											$output .= '<div class="content-center container white">';
											$output .= '<h1>Error</h1>';
											$output .= '<div class="panel dark">';
											$output .= '<p>Es konnte kein User gefunden werden.</p>';
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
										$output .= '<p>Es sind nur folgende Tabs vorhanden: Allgemein und Lokation.</p>';
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
								$output .= '<div class="content-center container white">';
								$output .= '<h1>CI anzeigen</h1>';
								
								$output .= '<form action="change.php" method="get">';
								$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
								$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
								$output .= '<input type="hidden" name="attr" value="name"/>';
								$output .= '<ul class="flex section">';
								$output .= '<li class="col-s10 col-m10 col-l10">';
								$output .= '<input class="ipt-default" type="text" name="attr_value" placeholder="CI-Name" value="'.$row['ci_name'].'"/>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
								$output .= '<select class="ipt-default" name="attr_value">';
								
								if($row['ci_type'] == 'string')
								{
									$output .= '<option selected disabled value="">Zeichenkette</option>';
								}
								else if($row['ci_type'] == 'select')
								{
									$output .= '<option selected disabled value="">SelectBox</option>';
								}
								else if($row['ci_type'] == 'url')
								{
									$output .= '<option selected disbaled value="">URL</option>';
								}
								
								$output .= '<option value="string">Zeichenkette</option>';
								$output .= '<option value="select">Selectbox</option>';
								$output .= '<option value="url">URL</option>';
								$output .= '</select>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
										$output .= '<select class="ipt-default" name="attr_value">';
										$output .= '<option selected disabled value="">'.$row['ci_regex'].'</option>';
										
										foreach($app_regex as $regex_name => $regex_value)
										{
											$regex = str_replace('\s',' Leerzeichen ',$regex_value);
											
											$regex = str_replace('\\','',$regex);
											
											$output .= '<option value="'.$regex_name.'">'.$regex.'</option>';
										}
										
										$output .= '</select>';
									}
									else if($row['ci_type'] == 'select')
									{
										$regex_arr = json_decode($row['ci_regex']);
										
										$regex = implode(',',$regex_arr);
										
										$output .= '<input class="ipt-default" type="text" name="attr_value" value="'.$regex.'"/>';
									}
									
									$output .= '</li>';
									$output .= '<li class="col-s2 col-m2 col-l2">';
									$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
									$output .= '</li>';
									$output .= '</ul>';
									$output .= '<input type="hidden" name="returnto" value="http://'.$_SERVER['HTTP_HOST'].'/view.php?category='.$_GET['category'].'&id='.$_GET['id'].'"/>';
									$output .= '</form>';
								}
								else if($row['ci_type'] == 'url')
								{
									$output .= '<div class="section ipt-default">'.$row['ci_regex'].'</div>';
								}
							}
							else
							{
								$output .= '<div class="container">';
								$output .= '<div class="content-center container white">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel dark">';
								$output .= '<p>Es wurde kein CI gefunden.</p>';
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
								$output .= '<div class="content-center container white">';
								$output .= '<h1>'.$category_german[$key].' anzeigen</h1>';
								$output .= '<form action="change.php" method="get">';
								$output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
								$output .= '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
								$output .= '<input type="hidden" name="attr" value="name"/>';
								$output .= '<ul class="flex section">';
								$output .= '<li class="col-s10 col-m10 col-l10">';
								$output .= '<input class="ipt-default" type="text" name="attr_value" placeholder="'.$category_german[$key].'" value="'.$row[0].'"/>';
								$output .= '</li>';
								$output .= '<li class="col-s2 col-m2 col-l2">';
								$output .= '<button class="block btn-default light-blue" type="submit"><i class="fas fa-save"></i></button>';
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
								$output .= '<div class="content-center container white">';
								$output .= '<h1>Error</h1>';
								$output .= '<div class="panel dark">';
								$output .= '<p>Es wurde kein '.$category_german[$key].' mit der gesendeten ID gefunden.</p>';
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
						$output .= '<p>Die ID besteht nur aus Zahlen.</p>';
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