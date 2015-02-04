<?php
require_once('inc/inc_top.php');
if(isset($_GET['q'])){
	$recherche = $_GET['q'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>recherche - </title>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron">
		<?php
		require_once('inc/inc_menu.php');
		?>
		<?php
		if(isset($recherche)){ // si le champ recherche contiens quelque chose
		echo '<p>Vous avez rechercher "'.$recherche.'".</p>';
		}else{
		echo '<p>Veuillez saisir une recherche. !!!!! </p>';
		}
		?>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
