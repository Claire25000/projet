<nav class="navbar navbar-default navbar-static-top">
      <div class="container">
<ul class="nav navbar-nav navbar">
<div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
			<!-- -->
          </ul>
          <ul class="nav navbar-nav navbar-right">
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
</div>
</ul>
</div>
</nav>