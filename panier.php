<?php
require_once('inc/inc_top.php');
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionPanier.php");

if(!estConnecte()){ // si non connecté
	header('Location: 404.php?err=1');
}
// Voici les données externes utilisées par le panier
$id_article = null;
$qte_article = 1;

if(isset($_GET['id']))
{
	$id_article = $_GET['id'];
	$qte_article = $_GET['qte'];
}
elseif(isset($_POST['id']))
{
	$id_article = $_POST['id'];
	$qte_article = $_POST['qte'];
}

if(isset($_POST['modifier']))  
{
	modifierQte($id_article,$qte_article);
} 
elseif(isset($_POST['supprimer']))
{
	supprimerArticle($_POST['id']);
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Panier</title>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron">
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message))
		{
			echo $message;
		}
		if(!isset($_GET['comm']))
		{
			echo '<div style="text-align:right;"><a href="panier.php?comm" class="btn btn-default" role="button">Confirmer la commande</a></div>';
			// Voici l'affichage du panier
			echo '<h2>Contenu de votre panier</h2><ul>';
			if (isset($_SESSION['panier']) && count($_SESSION['panier'])>0){
					$total_panier = 0;
					foreach($_SESSION['panier'] as $id_article=>$article_acheté){
							// On affiche chaque ligne du panier : nom, prix et quantité modifiable + 2 boutons : modifier la qté et supprimer l'article
							
							$produit = retourneProduit($id_article);		
							
							if (isset($article_acheté['qte'])){
									echo '<li><form action="panier.php" method="POST">', $_SESSION['panier'][$id_article]['qte'],' ',$produit->nomProduit, ' (', number_format($produit->prixProduit, 2, ',', ' '), ' €) ',
									 '<input type="hidden" name="id" value='.$id_article.' />
									  <br />Qté: <select name="qte">
											<option selected="selected" value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>
									  <input type="submit" name="modifier" value="Modifier la quantité" />
									  <input type="submit" name="supprimer" value="Supprimer" />
									</form>
									</li>';
									
									// Calcule le prix total du panier 
									$total_panier += $produit->prixProduit * $article_acheté['qte'];
					}
					echo '<hr><h3>Total: ', number_format($total_panier, 2, ',', ' '), ' €'; // Affiche le total du panier
			}
	}
else { echo 'Votre panier est vide'; } // Message si le panier est vide
echo "</ul>";
}
else
{
	$total_panier = 0;
	$liste = array();
	
	if(!isset($_GET['valider']))
	{
			echo '<div style="margin-top:50px;"><div class="panel panel-default">
		<div class="panel panel-default">
					<div class="panel-heading">Récapitulatif de commande</div>

					<table class="table">
							<thead><tr>
								<th>Image</th>
								<th>Nom du produit</th>
								<th>Prix unitaire</th>
								<th>Quantité</th>
								<th>Prix</th>
							</tr></thead>
							<tbody>';
			
				foreach($_SESSION['panier'] as $id_article=>$article_acheté)
					{
						$liste[] = $id_article;
						$produit = retourneProduit($id_article);
						
						echo '<tr>
								<td>
									<img style="max-width: 130px;" src="'.retourneParametre("repertoireUpload").''.$produit->image.'" alt="'.$produit->nomProduit.'"/>
								</td>
								<td>'.$produit->nomProduit.'</td>
								<td>'.$produit->prixProduit.' €</td>
								<td>'.$article_acheté['qte'].'</td>
								<td>'.$produit->prixProduit*$article_acheté['qte'].' €</td>						
							</tr>';
							$total_panier += $produit->prixProduit*$article_acheté['qte'];
					}
					
						echo '</tbody></table>
					</div>
				  </div>';
				  echo '<hr><div style="text-align:right;"><h3>Total: ', number_format($total_panier, 2, ',', ' '), ' €</div>
		  <form action="panier.php?valider" method="POST">
			<label>Informations supplémentaires : <textarea class="form-control input-sm " type="textarea" id="message" name="message" placeholder="Message" maxlength="500" rows="10"></textarea>'; 
	}
	else
	{
	
	}
}
?>
 </div>
</div><!-- /container -->
<?php require_once("inc/inc_footer.php"); ?>
</body>
</html>