<?php
if(isset($_GET['logout'])){
	$message = '<div class="alert alert-danger" role="alert">Vous avez été déconnecté</div>';
}
require_once('inc/inc_top.php');
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Accueil - </title>
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
        <h1><?php echo retourneParametre('nomSite'); ?></h1>
		<p style="padding-top:3%">
			<?php echo retourneParametre('textAccueil'); ?>
		</p>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
