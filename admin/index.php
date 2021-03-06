<?php
require_once("inc/inc_top.php");
require_once("../fonctions/fonctionsCommande.php");

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['email']) && isset($_POST['password'])){
	if(connecteUtilisateur($_POST['email'],$_POST['password']))
		{
			if(estAdmin()){
				$message = '<div class="alert alert-success" role="alert">Connexion réussie</div>';
			}else{
				header('Location: ../404.php?err=1'); // // utilisateur connecté mais pas admin : on redirige
			}
		}
		else{
			$message = "Echec de l'authentification.<br/>";
		}
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Accueil Administrateur</title>
  </head>
  <body>
  <div class="container">
  <?php
  if(estConnecte() && estAdmin()){
			require_once('inc/inc_menu.php');
		}
		if(isset($message)){
			echo $message;
		}
		
	echo '<div class="jumbotron">';
	if(!estConnecte()){ // si l'utilisateur n'est pas connecté on affiche le formulaire?>
		<div class="row" style="margin-top:20px">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<form method="post" role="form">
					<fieldset>
						<h2>Veuillez vous enregistrer</h2>
						<hr class="colorgraph">
						<div class="form-group">
							<input value="admin@admin.fr" type="email" name="email" id="email" class="form-control input-lg" placeholder="Addresse email">
						</div>
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Mot de passe">
						</div>

						<hr class="colorgraph">
						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6">
								<input type="submit" class="btn btn-lg btn-success btn-block" value="Se connecter">
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6">
								<a href="../" class="btn btn-lg btn-primary btn-block">
									<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Retour au site
								</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<?php }else{ // sinon, si il est connecté
			?>
			<p>
			<?php 
				if(nombreCommandeEnCoursTotal()>0){
				 echo '<div class="alert alert-warning" role="alert">Vous avez  <a href="commande.php">'.nombreCommandeEnCoursTotal().' commandes en attente</a>.</div>';
				}
				?>
			
			
			
			</p>	
			<?php
		}
		?>
	</div>
	</div>
	</body>
</html>