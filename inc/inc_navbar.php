    <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header"><!-- nom du site menu -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../"><?php echo retourneParametre('nomSite'); ?></a>
        </div>
		<div class="col-sm-3 col-md-3"><!-- champ de recherche-->
        <form class="navbar-form" role="search" method="get" action="recherche.php">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Recherche" name="q">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        </form>
		</div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
			<!-- -->
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <!--<li class="active"><a href="./">Static top <span class="sr-only">(current)</span></a></li>-->
			<?php if(estAdmin()){ echo '<li><a href="/admin"> Administration </a></li>';
				  }else if(estConnecte()){
								  //echo '<li><a href="commande.php"> Commande </a></li>';
								  echo '<li style="margin-top:2px;margin-bottom:2px"><a href="commande.php"> Commande </a></li>';
				  }else{
					echo '<li><a href="inscription.php"> S\'inscrire </a></li>';
				  }
			?>
            
			
			<li class="dropdown"><!-- panier -->
				<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYBAMAAAASWSDLAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAANSAAADUgEQACRKAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAACdQTFRF////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIpVOngAAAAx0Uk5TAAIFMTJtcKHP4e71mFbgRgAAAFZJREFUGNNjYKAyUIBQTCCCfSuEE10AJDjOJIDYbGcagCTzmmMgTs4pAxDlBZJiO7MErJgFJJVzygGiFSgFkwBLwSVAUnAJkBRCgoHBcwqSixgFGKgPAOicF+k9k1fCAAAAAElFTkSuQmCC59e0aed1ee129ae1c19ec5b5ffca85d1"/>
					<?php if(!estConnecte()){ echo "Se connecter";}else{echo "Mon compte";}?>
				</a>
				<ul style="padding: 15px;min-width: 280px;" class="dropdown-menu">
				<form id="login-nav" accept-charset="UTF-8" action="" method="post" role="form" class="form">
					<?php if(!estConnecte()){ // si le visiteur n'est pas connecté on affiche le form de connexion?> 
					<div class="form-group">
					   <label for="email" class="sr-only">Addresse email</label>
					   <input type="email" required="" placeholder="Addresse email" id="email" name="email" class="form-control">
					</div>
					<div class="form-group">
					   <label for="password" class="sr-only">Mot de passe</label>
					   <input type="password" required="" placeholder="Mot de passe" id="password" name="password" class="form-control">
					</div>
					<?php } // sinon on affiche le menu pour l'utilisateur ?>
					<div class="form-group" style="margin-bottom: 0px">
					   <?php if(!estConnecte()){
								echo '<button class="btn btn-success btn-block" type="submit">S\'enregistrer</button>';
							 }else if(!estAdmin()){
								echo '<a class="btn btn-success btn-block" href="profil.php">Gestion profil </a>';
								echo '<a class="btn btn-success btn-block" href="?deco">Déconnexion </a>'; // erreur produit.php?id=x --> produit.php?déco
							 }else{
								echo '<a class="btn btn-success btn-block" href="?deco">Déconnexion </a>';
							 }
					   ?>
					</div>
				 </form>
				</ul>
			</li>
			
			<?php require_once("inc_panier_navbar.php"); ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>