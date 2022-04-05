<div class="bg-default">
	<div id="category-nav" class="nav container dark">
		<p class="hide-large"><i onclick="ch_style('category-nav','display','none');" class="fas fa-times fa-2x"></i></p>
		<p class="hide-large text-center"><a class="btn-default light-blue large" href="user.php">
		<?php
		if(!empty($initials))
		{
			echo $initials;
		}
		?>
		</a>
		</p>
		<ul>
			<li><p><a href="list.php?category=asset&site=0&site_amount=5" class="block btn-default light-blue">Assets</a></p></li>
			<li><p><a href="list.php?category=ci&site=0&site_amount=5" class="block btn-default light-blue">CIs</a></p></li>
			<li><p><a href="list.php?category=building&site=0&site_amount=5" class="block btn-default light-blue">Geb&auml;ude</a></p></li>
			<li><p><a href="list.php?category=vendor&site=0&site_amount=5" class="block btn-default light-blue">Hersteller</a></p></li>
			<li><p><a href="list.php?category=model&site=0&site_amount=5" class="block btn-default light-blue">Modelle</a></p></li>
			<li><p><a href="list.php?category=room&site=0&site_amount=5" class="block btn-default light-blue">R&auml;ume</a></p></li>
			<li><p><a href="list.php?category=floor&site=0&site_amount=5" class="block btn-default light-blue">Stockwerke</a></p></li>
			<li><p><a href="list.php?category=type&site=0&site_amount=5" class="block btn-default light-blue">Typen</a></p></li>
			<li><p><a href="list.php?category=user&site=0&site_amount=5" class="block btn-default light-blue">User</a></p></li>
		</ul>
	</div>
	<div class="content-default">
		<div class="container dark">
			<table class="block">
				<tr>
					<td class="col-l8">
						<table>
							<tr>
								<td class="hide-large"><i onclick="ch_style('category-nav','display','block');" class="fas fa-bars fa-2x"></i>&nbsp;&nbsp;&nbsp;</td>
								<td><img src="/images/logo.svg" style="width:50px;"/>&nbsp;&nbsp;&nbsp;</td>
								<td><a href="index.php"><h1>Sheldon <span class="hide-small">| MatDB
								<?php
								if(!empty($app_org))
								{
									echo ' | '.$app_org;
								}
								?>
								</span></h1></a></td>
							</tr>
						</table>
					</td>
					<td class="col-l4 text-right">
						<a class="btn-default large" href="cart.php?aktion=view">
							<i class="fas fa-shopping-cart"></i>
							<?php
							if(!empty($cart_count))
							{
								echo $cart_count;
							}
							?>
						</a>
						<a class="hide-medium hide-small btn-default light-blue large" href="profile.php">
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
		</div>
		<div class="container white">
			<form action="search.php" method="get">
				<ul class="flex section">
					<li clasS="col-s4 col-m2 col-l2"><select class="ipt-default light-blue" name="category"><option value="asset">Assets</option><option value="user">User</option></select></li>
					<li class="col-s6 col-m8 col-l9"><input class="ipt-default" type="text" name="search" value="" placeholder="Suchbegriff"/><input type="hidden" name="site" value="0"/><input type="hidden" name="site_amount" value="5"/></li>
					<li clasS="col-s2 col-m2 col-l1"><button class="btn-default block light-blue" type="submit"><i class="fas fa-search"></i></button></li>
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