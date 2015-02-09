<?php
require_once("inc/inc_top.php");

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['valid_modif']) || isset($_POST['valid_error'])){ // -------------------- MODIFICATION DES PARAMETRES ------------------ //
	foreach($_POST as $key => $value) {
	  	try
		{
			unset($_SESSION[$key]);
			
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("UPDATE `parametre` SET `valeur` = '".addslashes($value)."' WHERE `parametre`.`cle` = '".addslashes($key)."'");
			$query->execute();
		}
		catch(Exception $e)
		{
			$message = '<div class="alert alert-danger" role="alert">Erreur lors de la mise à jour des données</div>';
		}
		chargerParametres();
		chargerErreurs();
	}
	if(isset($_POST['valid_modif'])){
		$message = '<div class="alert alert-success" role="alert">Les paramètres ont été mis à jour</div>';
	}else if(isset($_POST['valid_error'])){
		$message = '<div class="alert alert-success" role="alert">Les erreurs ont été mises à jour</div>';
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Gestion des paramètres/erreurs</title>
  </head>
  <body>
   <div class="container">
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
		echo ' <div class="jumbotron">';
		?>
		<form action="parametre.php" method="POST" class="form-horizontal">
			<fieldset>

			<!-- Form Name -->
			<legend>Paramètres</legend>
			<p></p>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_mailSite">Mail de contact du site web</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneParametre('mailSite'); ?>" id="param_mailSite" name="param_mailSite" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_nomSite">Nom du site</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneParametre('nomSite'); ?>" id="param_nomSite" name="param_nomSite" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_repertoireUpload">Repertoire d'upload des images</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneParametre('repertoireUpload'); ?>" id="param_repertoireUpload" name="param_repertoireUpload" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_ordre">Ordre</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneParametre('ordre'); ?>" id="param_ordre" name="param_ordre" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>
			<!-- Multiple Radios -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_repertoireUpload">Commentaire</label>
			  <div class="col-md-4">
				  <div class="radio">
					<?php 
						if(retourneParametre('afficherCommentaire') == 'true'){$true='';}
						else if(retourneParametre('afficherCommentaire') == 'false'){$false='';}; 
					?>
					<label for="param_repertoireUpload-0">
					  <input <?php if(isset($true)){echo 'checked="checked"';}?> name="param_afficherCommentaire" id="param_repertoireUpload-0" value="true" type="radio">
					  Activer
					</label>
				  </div>
				  <div class="radio">
					<label for="param_repertoireUpload-1">
					  <input <?php if(isset($false)){echo 'checked="checked"';}?> name="param_afficherCommentaire" id="param_repertoireUpload-1" value="false" type="radio">
					  Désactiver
					</label>
				  </div>
			  </div>
			</div>
			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="valid_modif"> </label>
			  <div class="col-md-4">
				<button id="valid_modif" name="valid_modif" class="btn btn-primary">Modifier les paramètres</button>
			  </div>
			</div>
			</fieldset>
		</form>

		
		
		<form action="parametre.php" method="POST" class="form-horizontal">
			<fieldset>

			<!-- Form Name -->
			<legend>Erreurs</legend>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="error_0">Erreur 0</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneErreur('0'); ?>" id="error_0" name="error_0" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-md-4 control-label" for="error_1">Erreur 1</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneErreur('1'); ?>" id="error_1" name="error_1" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-md-4 control-label" for="error_404">Erreur 404</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneErreur('404'); ?>" id="error_404" name="error_404" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>

			
			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="valid_error"> </label>
			  <div class="col-md-4">
				<button id="valid_error" name="valid_error" class="btn btn-primary">Modifier les erreurs</button>
			  </div>
			</div>

			</fieldset>
		</form>
		</div>
	</div>
	</body>
</html>