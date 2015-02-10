<?php
require_once('inc/inc_top.php');
if(!estConnecte() || estAdmin()){ // si non connecté, on login en tant qu'admin
	header('Location: 404.php?err=1');
}
if(isset($_POST['nomClient'])){
	if(changerNomClient(idUtilisateurConnecte(),$_POST['nomClient'])){
		$message = '<div class="alert alert-success" role="alert">Le nom à été mis à jour</div>';
	}
}
if(isset($_POST['prenomClient'])){
	if(changerPrenomClient(idUtilisateurConnecte(),$_POST['prenomClient'])){
		$message = '<div class="alert alert-success" role="alert">Le prenom à été mis à jour</div>';
	}
}
if(isset($_POST['mail'])){
	if(changerEmailUtilisateur(idUtilisateurConnecte(),$_POST['mail'])){
		$message = '<div class="alert alert-success" role="alert">L\'email à été mis à jour</div>';
	}
}
if(isset($_POST['adresse'])){
	if(changerAdresseClient(idUtilisateurConnecte(),$_POST['adresse'])){
		$message = '<div class="alert alert-success" role="alert">L\'adresse à été mise à jour</div>';
	}
}
if(isset($_POST['ancienMdp']) && isset($_POST['nouveauMdp1']) && isset($_POST['nouveauMdp2'])){
	if($_POST['nouveauMdp1'] == $_POST['nouveauMdp2']){
		if(changerPasswordUtilisateur(idUtilisateurConnecte(),$_POST['ancienMdp'],$_POST['nouveauMdp1'])){
			$message = '<div class="alert alert-success" role="alert">Le mot de passe à été mis à jour</div>';
		}else{
			$message = '<div class="alert alert-warning" role="alert">L\'ancien mot de passe saisi est incorect</div>';
		}
	}else{
		$message = '<div class="alert alert-warning" role="alert">Les mots de passe ne sont pas identiques</div>';
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Mon profil</title>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron" style="min-height:700px">
		<?php
		require_once('inc/inc_menu.php');
		?>
		<?php
		if(isset($message)){
			echo $message;
		}
		$client = retourneClient(idUtilisateurConnecte());
		$utilisateur = retourneUtilisateur(idUtilisateurConnecte());
		?>
		<form class="form-horizontal" method="post" action="profil.php">
			<fieldset>
				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="nomClient">Nom </label>  
				  <div class="col-md-4">
				  <input id="nomClient" value="<?php echo $client->nomCli; ?>" name="nomClient" class="form-control input-md" required="" type="text">
				  </div>
				</div>
				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="singlebutton"></label>
				  <div class="col-md-4">
					<button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Mettre à jour</button>
				  </div>
				</div>
			</fieldset>
		</form>

		<form class="form-horizontal" method="post" action="profil.php">
			<fieldset>
				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="prenomClient">Prénom </label>  
				  <div class="col-md-4">
				  <input id="prenomClient" value="<?php echo $client->prenomCli; ?>" name="prenomClient" class="form-control input-md" required="" type="text">
				  </div>
				</div>
				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="singlebutton"></label>
				  <div class="col-md-4">
					<button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Mettre à jour</button>
				  </div>
				</div>
			</fieldset>
		</form>
		
		<form class="form-horizontal" method="post" action="profil.php">
			<fieldset>
				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="adresse">Adresse complète </label>  
				  <div class="col-md-4">
				  <input id="adresse" value="<?php echo $client->adrCli; ?>" name="adresse" class="form-control input-md" required="" type="text">
				  </div>
				</div>
				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="singlebutton"></label>
				  <div class="col-md-4">
					<button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Mettre à jour</button>
				  </div>
				</div>
			</fieldset>
		</form>
		
		<form class="form-horizontal" method="post" action="profil.php">
			<fieldset>
				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="mail">Mail </label>  
				  <div class="col-md-4">
				  <input id="mail" value="<?php echo $utilisateur->email; ?>" name="mail" class="form-control input-md" required="" type="text">
				  </div>
				</div>
				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="singlebutton"></label>
				  <div class="col-md-4">
					<button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Mettre à jour</button>
				  </div>
				</div>
			</fieldset>
		</form>
		<hr>
		<form class="form-horizontal" method="post" action="profil.php">
			<fieldset>
				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="ancienMdp">Ancien mot de passe </label>  
				  <div class="col-md-4">
				  <input id="ancienMdp" placeholder="Ancien mot de passe" name="ancienMdp" class="form-control input-md" required="" type="password">
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-md-2 control-label" for="nouveauMdp1">Nouveau mot de passe </label>  
				  <div class="col-md-4">
				  <input id="nouveauMdp1" placeholder="Ancien mot de passe" name="nouveauMdp1" class="form-control input-md" required="" type="password">
				  </div>
				</div>
				<div class="form-group">
				  <label class="col-md-2 control-label" for="nouveauMdp2">Nouveau mot de passe (répéter)</label>  
				  <div class="col-md-4">
				  <input id="nouveauMdp2" placeholder="Ancien mot de passe" name="nouveauMdp2" class="form-control input-md" required="" type="password">
				  </div>
				</div>
				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-2 control-label" for="singlebutton"></label>
				  <div class="col-md-4">
					<button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Mettre à jour</button>
				  </div>
				</div>
			</fieldset>
		</form>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
