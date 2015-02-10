<?php
require_once("inc/inc_head.php");
require_once('inc/inc_top.php');
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionsCommande.php");

// Voici les données externes utilisées par le panier
$id_article = null;
$qte_article = 1;

if(isset($_GET['id']) && isset($_GET['qte']))
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
if(isset($_GET['vide']))
{
	viderPanier();
	header("Location:panier.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Panier</title>
	<style>
		#formu{
		float:right;
		position:relative;
		right:10%;
		margin: 0  5px 5px 0;
		}
		#formu2{
		float:left;
		position:relative;
		left:10%;
		margin: 0  5px 5px 0;
		}
</style>
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
			// Voici l'affichage du panier
			echo '<h2>Contenu de votre panier</h2><ul>';
		if (isset($_SESSION['panier']) && count($_SESSION['panier'])>0){
			echo '<div style="text-align:right;"><a href="panier.php?vide" class="btn btn-default" role="button">Vider le panier</a></div></br>';
			echo '<div style="text-align:right;"><a href="panier.php?comm" class="btn btn-default" role="button">Confirmer la commande</a></div>';
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
	if(!estConnecte())
	{ 		
		?>
		<div id="formu">
		<h3>Vous avez dejà un compte</h3>
		<form id="login-nav" accept-charset="UTF-8" action="" method="post" role="form" class="form">
			<div class="form-group">
			   <label for="email" class="sr-only">Adresse email</label>
			   <input type="email" required="" placeholder="Adresse email" id="email" name="email" class="form-control">
			</div>
			<div class="form-group">
			   <label for="password" class="sr-only">Mot de passe</label>
			   <input type="password" required="" placeholder="Mot de passe" id="password" name="password" class="form-control">
			</div>
			<div style="text-align:center;">
				<button class="btn btn-default" type="submit">S'enregistrer</button>
			</div>
		</form>
		</div>

		<div id="formu2">
		<h3>Vous n'avez pas encore de compte</h3>
		<form id="login-nav" accept-charset="UTF-8" action="inscription.php" method="post" role="form" class="form">
			<div class="form-group">
			   <label for="email" class="sr-only">Adresse email</label>
			   <input type="email" required="" placeholder="Adresse email" id="email2" name="email2" class="form-control">
			</div>
			<div style="text-align:center;">
				<button class="btn btn-default" type="submit">Créer mon compte</button>
			</div>
		</form>
		</div>
	<?php
	}
	else
	{
		$total_panier = 0;
		
		if(!isset($_GET['valider']))
		{
				echo '<div class="panel panel-default">
						<div class="panel-heading">Récapitulatif panier</div>

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
						</div>';
					  echo '<hr><div style="text-align:right;"><h3>Total: ', number_format($total_panier, 2, ',', ' '), ' €</div>
			  <form action="panier.php?comm&valider" method="POST">
				<label>Informations supplémentaires : <textarea class="form-control input-sm " type="textarea" id="message" name="message" placeholder="Message" maxlength="500" rows="10" cols="50"></textarea>
				<br/>
				<label>Mode de livraison : </label><select name="liv">';
				foreach(retourneListeLivraison() as $element) // retourne un array de mode de livraison 
						{
							echo '<option value="'.$element->idModeLivraison.'">'.$element->libelleModeLivraison.'</option>'; 
						}
				echo '</select><br/>
				<label>Mode de livraison : </label><select name="paie">';
				foreach(retourneListePaiement() as $element) // retourne un array de mode de livraison 
						{
							echo '<option value="'.$element->idModePaiement.'">'.$element->libelleModePaiement.'</option>'; 
						}
				echo '</select><br/>
				<input type="submit" name="valider"></input>
				</form>';
		}
		else
		{
			if(!isset($_GET['payer']))
			{
				if($_POST['message'] == null)
				{
					$info = " ";
				}
				else
				{
					$info = $_POST['message'];
				}
				$res = ajouterCommande(date("Y-m-d H:i:s"),1,$_POST['paie'],$_POST['liv'],idUtilisateurConnecte(),$info);

				foreach($_SESSION['panier'] as $id_article=>$article_acheté)
				{
					ajouterLigneCommande($res,$id_article,$article_acheté['qte']);
					deduireStock($id_article,$article_acheté['qte']);
				}
				
				echo '<div class="alert alert-success" role="alert">Commande effectuée !</div>
				<a href="panier.php?comm&valider&payer&ok&id='.$res.'" class="btn btn-default" role="button">Payer cette commande</a>';
			}
			else			
			{				
				if(isset($_GET['ok']))
				{
					$commande = retourneCommande($_GET['id']);
					if($commande->modePaiement == 1 || $commande->modePaiement == 2)
					{
						echo "Veuillez adresser votre paiement à l'adresse suivante : </br>".retourneParametre('ordre');
						viderPanier();
						echo '</br><a href="commande.php" class="btn btn-default" role="button">Voir vos commandes</a>';
					}
					else
					{
						changerStatutCommande($_GET['id'],2);
						$message = '<div class="alert alert-success" role="alert">Commande payée.</div>';
						echo $message;
						viderPanier();
						echo '<a href="commande.php" class="btn btn-default" role="button">Voir vos commandes</a>';
					}
				}
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