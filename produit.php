<?php
if(!isset($_GET['id']))
{
	header("Location:404.php?err=202");
	exit;
}

require_once('inc/inc_top.php');
require_once("fonctions/fonctionComm.php");
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionPanier.php");

if(isset($_GET['supp']))
{
	supprimerCommentaire($_GET['id']);
}

if(isset($_GET['ajouterPanier']))
{
	ajouterPanier($_GET['id'],$_GET['qte']);
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
				$req = $connexion->query("SET NAMES 'utf8'");	
				$req = $connexion->query("Select produit.*, categorie.libelleCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$_GET['id']);
				$req->setFetchMode(PDO::FETCH_OBJ);
				$res = $req->fetch();
				
				echo '<div style="text-align:right;"><a href="produit.php?ajouterPanier&id='.$res->idProduit.'&qte=1" class="btn btn-default" role="button">Ajouter au panier</a></div>';
			
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