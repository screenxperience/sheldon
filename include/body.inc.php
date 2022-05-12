<div class="bg-default">
	<div id="category-nav" class="nav container black-alpha">
		<p class="hide-large">
			<i onclick="ch_style('category-nav','display','none');" class="fas fa-times fa-2x"></i>
		</p>
		<ul>
			<li>
				<p>
					<a href="list.php?category=lend&site=0&amount=5&archived=0" class="block btn-default border border-light-blue light-blue hover-white hover-text-blue">Leihgaben</a>
				</p>
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
	<div class="content-default">
		<div class="container black-alpha">
			<table class="block">
				<tr>
					<td class="col-l6">
						<table>
							<tr>
								<td class="hide-large">
									<i onclick="ch_style('category-nav','display','block');" class="fas fa-bars fa-2x"></i>&nbsp;&nbsp;&nbsp;
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
			<form action="search.php" method="get">
				<ul class="flex section">
					<li clasS="col-s4 col-m2 col-l2">
						<select class="input-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" name="category">
							<option value="asset">Assets</option>
							<option value="user">User</option>
							<option value="lend">Leihgaben</option>
						</select>
					</li>
					<li class="col-s6 col-m8 col-l9">
						<input class="input-default border border-tb border-grey focus-border-light-blue" style="height:53px;" type="text" name="search" value="" placeholder="Suchbegriff"/>
						<input type="hidden" name="site" value="0"/><input type="hidden" name="amount" value="5"/>
					</li>
					<li clasS="col-s2 col-m2 col-l1">
						<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit">
							<i class="fas fa-search"></i>
						</button>
					</li>
				</ul>
			</form>
		</div>
		<?php
		if(!empty($output))
		{
			echo $output;
		}
		?>
	</div>
</div>