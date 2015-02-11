    <nav style="margin-bottom:5%" class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="/index.php"> Accueil site</a></li> 
			<li><a href="produit.php"> Gestion des produits </a></li>
			<li><a href="commande.php"> Gestion des commandes </a></li>
			<?php if(estWebmaster()){ echo '
					<li><a href="categorie.php"> Gestion des catégories </a></li>
					<li><a href="utilisateur.php"> Gestion utilisateurs </a></li>
					<li><a href="parametre.php"> Gestion des parametres </a></li>';
				  }
			?>
			<li><a href="?deco"> Déconnexion </a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav><br/>