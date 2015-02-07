<?php
require_once("inc/inc_top.php");
require_once('inc/inc_head.php');

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['valid_modif'])){

}
?>
<html>
	<head>
		<?php require_once('inc/inc_head.php');?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Gestion des paramètres/erreurs </title>
	</head>
	<body>
		<?php
			require_once('inc/inc_menu.php');
			if(isset($message))
			{
				echo $message;
			}
		?>
		<form action="parametre.php" method="POST" class="form-horizontal">
			<fieldset>

			<!-- Form Name -->
			<legend>Paramètres</legend>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_mailSite">Mail de contact du site web</label>  
			  <div class="col-md-6">
			  <input value="<?php echo retourneParametre('mailSite'); ?>" id="param_mailSite" name="param_mailSite" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_">Nom du site</label>  
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
					  <input <?php if(isset($true)){echo 'checked="checked"';}?> name="param_repertoireUpload" id="param_repertoireUpload-0" value="1" type="radio">
					  Activer
					</label>
				  </div>
				  <div class="radio">
					<label for="param_repertoireUpload-1">
					  <input <?php if(isset($false)){echo 'checked="checked"';}?> name="param_repertoireUpload" id="param_repertoireUpload-1" value="2" type="radio">
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

				<form class="form-horizontal">
			<fieldset>

			<!-- Form Name -->
			<legend>Erreurs</legend>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_">Text Input</label>  
			  <div class="col-md-6">
			  <input id="param_" name="param_" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>

			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="singlebutton"> </label>
			  <div class="col-md-4">
				<button id="singlebutton" name="singlebutton" class="btn btn-primary">Button</button>
			  </div>
			</div>

			</fieldset>
		</form>
		
	</body>
</html>