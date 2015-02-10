<?php
require_once('inc/inc_top.php');
if(!isset($_GET['err'])){ // si aucun code d'erreur fourni
	$codeErreur = '0';
}else{
	$codeErreur = ''.$_GET['err'].'';
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Erreurs</title>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron">
		<?php
			require_once('inc/inc_menu.php');
		?>
		<p>
			<?php echo retourneErreur($codeErreur); ?><br/>
		</p>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
