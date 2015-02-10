<?php
require_once('inc/inc_top.php');
$new = 0;

if(isset($_POST['login']) && isset($_POST['pass1']) && isset($_POST['pass2']) && isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['adresse'])){
	if($_POST['pass1'] == $_POST['pass2']){
		if(ajouterClient($_POST['login'],$_POST['pass1'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['email'])){
			$message = '<div class="alert alert-warning" role="alert">Inscription effectuée avec succès.</div>';

			if($_POST['ok'] == 1)
			{
				connecteUtilisateur($_POST['email'],$_POST['pass1']);
				header("Location:panier.php?comm");
			}
		}
	}
}
if(isset($_POST['email2']))
{
	$mail = $_POST['email2'];
	$new = 1;
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Inscription</title>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron">
		<?php
		require_once('inc/inc_menu.php');
	
		if(isset($message)){
			echo $message;
		}		
		?>

		<h3> Inscription </h3>
		<br/>
		<form method="post" action="inscription.php" class="form-horizontal">
		<fieldset>
		<?php
		echo '<input name="ok" type="hidden" value="'.$new.'"></input>';
		?>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="login">Nom d'utilisateur</label>  
		  <div class="col-md-4">
		  <input id="login" name="login" placeholder="Nom" class="form-control input-md" required="" type="text">
			
		  </div>
		</div>

		<!-- Password input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="pass1">Mot de passe</label>
		  <div class="col-md-4">
			<input id="pass1" name="pass1" placeholder="Mot de passe" class="form-control input-md" required="" type="password">
			
		  </div>
		</div>

		<!-- Password input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="pass2">Mot de passe (répeter)</label>
		  <div class="col-md-4">
			<input id="pass2" name="pass2" placeholder="Mot de passe (répéter)" class="form-control input-md" required="" type="password">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="prenom">Prénom</label>  
		  <div class="col-md-4">
		  <input id="prenom" name="prenom" placeholder="Prénom" class="form-control input-md" required="" type="text">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="nom">Nom</label>  
		  <div class="col-md-4">
		  <input id="nom" name="nom" placeholder="Nom" class="form-control input-md" required="" type="text">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="email">Email</label>  
		  <div class="col-md-4">
		  <?php 
		  if(isset($mail))
		  {
			echo '<input id="email" name="email" placeholder="Email" class="form-control input-md" required="" type="email" value="'.$mail.'">';
		  }
		  else
		  {
			echo '<input id="email" name="email" placeholder="Email" class="form-control input-md" required="" type="email">';
		  }
		  ?>
		  </div>
		</div>

		<!-- Textarea -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="adresse">Adresse complète</label>
		  <div class="col-md-4">                     
			<textarea class="form-control" id="adresse" name="adresse"></textarea>
		  </div>
		</div>
		
		<div class="form-group">
		  <label class="col-md-4 control-label" for="singlebutton"> </label>
		  <div class="col-md-4">
			<button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">S'inscrire</button>
		  </div>
		</div>
		</fieldset>
		</form>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
