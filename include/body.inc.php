<div class="bg-default">
	<div id="sidebar-category" class="sidebar-left container black-alpha">
		<p class="hide-large">
			<i onclick="ch_style('sidebar-category','display','none');" class="fas fa-times fa-2x"></i>
		</p>
		<ul>
			<li>
				<div class="section text-center">
					<a href="list.php?category=lend&site=0&amount=5&archived=0" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>Leihgaben</p></a>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=asset&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>Assets</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=asset" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=user&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>User</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=user" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=ci&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>CIs</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=ci" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=type&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>Typen</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=type" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=vendor&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>Hersteller</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=vendor" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=model&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>Modelle</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=model" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=building&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>Geb&auml;ude</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=building" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=floor&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>Stockwerke</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=floor" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
			<li>
				<div class="section display-container text-center hover-text-left">
					<a href="list.php?category=room&site=0&amount=5" class="container border border-light-blue light-blue hover-white hover-text-blue"><p>R&auml;ume</p></a>
					<div class="hover-display display-top-right">
						<a href="add.php?category=room" class="container border border-light-blue light-blue hover-white hover-text-blue"><p><i class="fas fa-plus"></i></p></a>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div id="sidebar-filter" class="sidebar-right container black-alpha" style="width:500px;display:none;">
		<h2>Filter f&uuml;r</h2>
		<p>
			<select onchange="loadfilter();" class="input-default border border-light-blue light-blue hover-white hover-text-blue" id="filterinput">
			<option value="lend">Leihgaben</option>
			<option value="asset">Assets</option>
			<option value="user">User</option>
			</select>
		</p>
		<div id="filterdiv">

		</div>
	</div>
	<div class="content-default">
		<div class="container black-alpha">
			<table class="block">
				<tr>
					<td class="col-l6">
						<table>
							<tr>
								<td class="hide-large">
									<i onclick="ch_style('sidebar-category','display','block');" class="fas fa-bars fa-2x"></i>&nbsp;&nbsp;&nbsp;
								</td>
								<td>
									<img src="/images/logo.svg" style="width:50px;"/>&nbsp;&nbsp;&nbsp;
								</td>
								<td>
									<a href="index.php">
										<h1>Sheldon 
											<span class="hide-small">
												| IT-Pool
												<?php
												if(!empty($app_org))
												{
													echo ' | '.$app_org;
												}
												?>
											</span>
										</h1>
									</a>
								</td>
							</tr>
						</table>
					</td>
					<td class="col-l6">
						<table align="right">
							<tr>
								<td>
									<a class="btn-default large" href="cart.php?aktion=view">
										<i class="fas fa-shopping-cart"></i>
										<?php
										if(!empty($cart_count))
										{
											echo $cart_count;
										}
										?>
									</a>
								</td>
								<td>
									<a class="circle border border-light-blue light-blue hover-white hover-text-blue" href="profile.php">
										<?php
										if(!empty($initials))
										{
											echo $initials;
										}
										?>
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="container white-alpha">
			<div onclick="ch_style('sidebar-filter','display','block'); loadfilter();" class="section input-default border border-grey">Suchen in ...</div>
		</div>
		<?php
		if(!empty($output))
		{
			echo $output;
		}
		?>
	</div>
</div>