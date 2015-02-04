<?php
if(!isset($_GET['id']))
{
	header("Location:404.php?err=202");
	exit;
}

require_once('inc/inc_top.php');
require_once("fonctions/fonctionComm.php");
require_once("fonctions/fonctionProd.php");

if(isset($_GET['supp']))
{
	supprimerCommentaire($_GET['id']);
}

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
      <div class="jumbotron">
		<?php
		require_once('inc/inc_menu.php');
		?>
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
			afficherProduitDetails($_GET['id']);
			
			if(estConnecte() == true)
			{
				if(retourneParametre("afficherCommentaire"))
				{
					afficherCommentaire($_GET['id']);
					foreach(retourneListeCommentaire($_GET['id']) as $element)
					{
						$date = new DateTime($element->date);
						echo '<br/>Par '.retourneUtilisateur($element->idUtilisateur)->login.' le '.$date->format('d/m/Y').' : '.$element->comm;
						if($element->idUtilisateur == idUtilisateurConnecte())
						{
							echo "  <a href='produit.php?id=".$_GET['id']."&supp'>Supprimer</a>";
						}
					}
				}
			}
		?>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>