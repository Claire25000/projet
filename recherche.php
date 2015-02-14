<?php
require_once('inc/inc_top.php');
require_once("fonctions/fonctionProd.php");
if(isset($_GET['q'])){
	$recherche = $_GET['q'];
}
if(isset($recherche)) // si le champ recherche contient quelque chose
{ 
	$prod = rechercheProduit($recherche);
	if($prod != null)
	{
		header("Location:produit.php?id=".$prod->idProduit);
	}
	else
	{
		$message = '<p>Aucun produit ne correspond à votre recherche. Veillez vérifier le nom du produit.</p>';
	}
}
else
{
	$message = '<p>Veuillez saisir une recherche ! </p>';
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Recherche</title>
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
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
