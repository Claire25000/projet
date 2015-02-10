<?php
require_once('inc/inc_top.php');

if(isset($_POST['mail']) && isset($_POST['message'])){
	envoyeMail("Message via le formulaire de contact",$_POST['message'],$_POST['mail']);
	$message = '<div class="alert alert-success" role="alert">Votre message a été envoyé</div>';
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Contact - </title>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron" style="min-height:700px">
		<?php
		require_once('inc/inc_menu.php');

		if(isset($message)){
			echo $message;
		}	
		?>
		<form action="contact.php" method="POST" class="form-horizontal">
		<fieldset>

		<!-- Form Name -->
		<legend>Contactez moi</legend>
		<!-- Text input-->
		<div class="form-group">  
		  <div class="col-md-12">
		  <input style="width:100%" id="mail" name="mail" placeholder="Votre adresse e-mail" class="form-control input-md" required="" type="email">
		  </div>
		</div>
		<!-- Textarea -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="message"></label>
		  <div class="col-md-12">                     
			<textarea style="width:100%" class="form-control" id="message" name="message"></textarea>
		  </div>
		</div>
		<!-- Button -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="envoyer"></label>
		  <div class="col-md-12">
			<button id="envoyer" name="envoyer" type="submit" class="btn btn-success">Envoyer votre message</button>
		  </div>
		</div>

		</fieldset>
		</form>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
