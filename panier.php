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
	label
	{
		display: block;
		width: 150px;
		float: left;
	}
</style>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron" style="min-height:700px">
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message))
		{
			echo $message;
		}
		if(!isset($_GET['comm']))
		{
			// Voici l'affichage du panier
			echo '<legend>Contenu de votre panier</legend>
				<p></p><ul>';
		if (isset($_SESSION['panier']) && count($_SESSION['panier'])>0){
			echo '<div style="text-align:right;"><a href="panier.php?vide" class="btn btn-default" role="button">Vider le panier</a></div></br>';
			echo '<div style="text-align:right;"><a href="panier.php?comm" class="btn btn-default" role="button">Confirmer la commande</a></div>';
				$total_panier = 0;
				foreach($_SESSION['panier'] as $id_article=>$article_acheté){
						// On affiche chaque ligne du panier : nom, prix et quantité modifiable + 2 boutons : modifier la qté et supprimer l'article
						
						$produit = retourneProduit($id_article);		
						$stockMax = retourneStock($id_article);
						
						if (isset($article_acheté['qte'])){
								echo '<li>
								<form action="panier.php" method="POST" class="form-horizontal">
								<fieldset><h4>
								 ', $_SESSION['panier'][$id_article]['qte'],' ',$produit->nomProduit, ' (', number_format($produit->prixProduit, 2, ',', ' '), ' €)</h4>',
								 '<input type="hidden" name="id" value='.$id_article.' />								  
								<div class="form-group">
								<label class="col-md-1 control-label">Quantité</label>  
								<div class="col-lg-4 input-group"> 
								<select name="qte" class="form-control">';
								
								// ------------------------------------------------- AFFICHAGE ET PRESELECTION DE LA QUANTITE EN PANIER DU PRODUIT (<select>)--------------//
								$qtePanierProduit = getQteProduit($id_article); // on recupere le nombre de produit actuel en panier
								for ($i = 1; $i <= $stockMax; $i++) { // on boucle sur le stock du produit actuel
								
									if($qtePanierProduit != $i){ 
										echo '<option value="'.$i.'">'.$i.'</option>';
									}else{ // on préséléctionne la valeur qui est acutellement dans le panier de l'utilisateur
										echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
									}
									
								}
							   echo '</select></div></div>
							   <div class="form-group">
								  <label class="col-md-1 control-label"> </label>
								  <div class="col-md-4">
									<input type="submit" name="modifier" value="Modifier la quantité" class="btn btn-primary"/>
									<input type="submit" name="supprimer" value="Supprimer" class="btn btn-primary"/>
								  </div>
								</div>
								</fieldset>
								</form>
								</li>';
								
								// Calcule le prix total du panier 
								$total_panier += $produit->prixProduit * $article_acheté['qte'];
						}
			echo '<div style="text-align:right;"><h3>Total: ', number_format($total_panier, 2, ',', ' '), ' €</h3></div>'; // Affiche le total du panier
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
		<h3>Vous avez dejà un compte</h3>
		<form id="login-nav" accept-charset="UTF-8" action="" method="post" role="form" class="form">
			<fieldset>
			<div class="form-group">
			   <label for="email" class="sr-only">Adresse email</label>
			   <input type="email" required="" placeholder="Adresse email" id="email" name="email" class="form-control" style="width:50%;">
			</div>
			<div class="form-group">
			   <label for="password" class="sr-only">Mot de passe</label>
			   <input type="password" required="" placeholder="Mot de passe" id="password" name="password" class="form-control" style="width:50%;">
			</div>
			<div style="text-align:left;">
				<button class="btn btn-default" type="submit">S'enregistrer</button>
			</div>
			</fieldset>
		</form>
		<br/>
		<h3>Vous n'avez pas encore de compte</h3>
		<form id="login-nav" accept-charset="UTF-8" action="inscription.php" method="post" role="form" class="form">
			<fieldset>
			<div class="form-group">
			   <label for="email" class="sr-only">Adresse email</label>
			   <input type="email" required="" placeholder="Adresse email" id="email2" name="email2" class="form-control" style="width:50%;">
			</div>
			<div style="text-align:left;">
				<button class="btn btn-default" type="submit">Créer mon compte</button>
			</div>
			</fieldset>
		</form>
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
									<td>'.number_format($produit->prixProduit, 2, ',', ' ').' €</td>
									<td>'.$article_acheté['qte'].'</td>
									<td>'.number_format($produit->prixProduit*$article_acheté['qte'], 2, ',', ' ').' €</td>						
								</tr>';
								$total_panier += $produit->prixProduit*$article_acheté['qte'];
						}
						
							echo '</tbody></table>
						</div>';
					  echo '<div style="text-align:right;"><h3>Total: ', number_format($total_panier, 2, ',', ' '), ' €</div>
			  <form action="panier.php?comm&valider" method="POST" class="form-horizontal">
			  <fieldset>
			  <legend>Confirmation</legend>
				<p></p>
				<div class="form-group">
				  <label class="col-md-3 control-label">Informations supplémentaires : </label>  
					  <div class="col-md-6">
						<textarea class="form-control input-sm " type="textarea" id="message" name="message" placeholder="Message" maxlength="500" rows="5"></textarea>
					  </div>
					</div>
					<div class="form-group">
				<label class="col-md-3 control-label">Mode de livraison :</label>  
				<div class="col-lg-6 input-group"> 
				<select name="liv" class="form-control">';
					$res = retourneFrais(2);
				foreach(retourneListeLivraison() as $element) // retourne un array de mode de livraison 
						{
							echo '<option value="'.$element->idModeLivraison.'">'.$element->libelleModeLivraison.'</option>'; 
						}
				echo '</select>(Frais supplémentaires pour envoi par colissimo : '.$res->frais.' €)</div></div>';
				
				echo '<label class="col-md-3 control-label">Mode de paiement :</label> 
				<div class="col-lg-6 input-group"> 
				<select name="paie" class="form-control">';
				foreach(retourneListePaiement() as $element) // retourne un array de mode de livraison 
						{
							echo '<option value="'.$element->idModePaiement.'">'.$element->libelleModePaiement.'</option>'; 
						}
				echo '</select></div><br/>
				<input type="hidden" name="prix" value="'.$total_panier.'"></input>
				<div class="form-group">
					  <label class="col-md-3 control-label"> </label>
					  <div class="col-md-4">
					<input type="submit" name="valider" value="Valider" class="btn btn-primary"/>
					</div></div>
					</fieldset>
				</form>';
		}
		else
		{
			if(!isset($_GET['payer']))
			{
				$total_panier += $_POST['prix'];
				if($_POST['message'] == null)
				{
					$info = " ";
				}
				else
				{
					$info = $_POST['message'];
				}
				
				if($_POST['liv'] == 2)
				{
					$resultat = retourneFrais(2);
					$total_panier += $resultat->frais;
				}
				$res = ajouterCommande(date("Y-m-d H:i:s"),1,$_POST['paie'],$_POST['liv'],idUtilisateurConnecte(),$info);
	
				foreach($_SESSION['panier'] as $id_article=>$article_acheté)
				{
					ajouterLigneCommande($res,$id_article,$article_acheté['qte']);
					deduireStock($id_article,$article_acheté['qte']);
				}
				
				viderPanier();
				echo '<div class="alert alert-success" role="alert">Commande effectuée !</div>
				<h3>Prix total : '.number_format($total_panier, 2, ',', ' '), ' €</h3>
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
						echo '</br><a href="commande.php" class="btn btn-default" role="button">Voir vos commandes</a>';
					}
					else
					{
						changerStatutCommande($_GET['id'],2);
						$message = '<div class="alert alert-success" role="alert">Commande payée.</div>';
						echo $message;
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