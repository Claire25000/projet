<?php
require_once('inc/inc_top.php');
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionsCommande.php");

// Voici les données externes utilisées par le panier
$id_article = null;
$qte_article = 1;

/*
if(isset($_GET['id']) && isset($_GET['qte']))
{
	//$id_article = $_GET['id'];
	//$qte_article = $_GET['qte'];
}
else*/
if(isset($_POST['id']))
{
	$id_article = $_POST['id'];
	$qte_article = $_POST['qte'];
	$message = '<div class="alert alert-success" role="alert">La quantité a été mise à jour</div>';
}

if(isset($_POST['modifier']))  
{
	modifierQte($id_article,$qte_article);
} 
elseif(isset($_POST['supprimer']))
{
	if(!isset($_POST['validSuprProdPanier'])){
		$message = '<div class="alert alert-info" role="alert">
					<form method="POST" action="panier.php" class="form-horizontal">
					<fieldset>
					<!-- Multiple Radios (inline) -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="validSuprProdPanier">Retirer l\'article du panier ?</label>
					  <div class="col-md-4"> 
						<label class="radio-inline" for="validSuprProdPanier-0">
						  <input name="validSuprProdPanier" id="validSuprProdPanier-0" value="1" checked="checked" type="radio">
						  Oui
						</label> 
						<label class="radio-inline" for="validSuprProdPanier-1">
						  <input name="validSuprProdPanier" id="validSuprProdPanier-1" value="2" type="radio">
						  Non
						</label>
					  </div>
					</div>
					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="singlebutton"></label>
					  <div class="col-md-4">
						<input name="supprimer" id="supprimer" value="'.$_POST['id'].'" type="hidden">
						<button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">Valider</button>
					  </div>
					</div>

					</fieldset>
					</form>
					</div>';
	}else if(isset($_POST['validSuprProdPanier']) && $_POST['validSuprProdPanier'] == '1'){
		supprimerArticle($_POST['supprimer']);
		//echo 'ok';
		//header("Location:panier.php");
		$message = '<div class="alert alert-success" role="alert">L\'article a été supprimé</div>';
	}else{
		$message = '<div class="alert alert-success" role="alert">L\'article n\'a pas été supprimé</div>';
		//echo 'nok';
		//header("Location:panier.php");
	}
}
if(isset($_GET['vide']))
{
	if(!isset($_POST['validViderPanier'])){
		$message = '<div class="alert alert-info" role="alert">
					<form method="POST" action="panier.php?vide" class="form-horizontal">
					<fieldset>
					<!-- Multiple Radios (inline) -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="validViderPanier">Confirmer la supression du panier ?</label>
					  <div class="col-md-4"> 
						<label class="radio-inline" for="validViderPanier-0">
						  <input name="validViderPanier" id="validViderPanier-0" value="1" checked="checked" type="radio">
						  Oui
						</label> 
						<label class="radio-inline" for="validViderPanier-1">
						  <input name="validViderPanier" id="validViderPanier-1" value="2" type="radio">
						  Non
						</label>
					  </div>
					</div>
					<!-- Button -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="singlebutton"></label>
					  <div class="col-md-4">
						<button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">Valider</button>
					  </div>
					</div>

					</fieldset>
					</form>
					</div>';
	}else if(isset($_POST['validViderPanier']) && $_POST['validViderPanier'] == '1'){
		viderPanier();
		header("Location:panier.php");
	}else{
		header("Location:panier.php");
	}
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php"); ?>
    <title>Panier</title>
	<style>
	select{
		display: block;
		width: 150px;
		float: left;
	}
	label
	{
		display: block;
		width: 150px;
		float: left;
	}
	</style>
	<script>
    function run() {
        var modeLiv = document.getElementById("liv").value;
		var sousTotal = document.getElementById("divTotalLivraison").innerHTML;
		sousTotal = parseFloat(sousTotal);
		var fraisLiv;

		if(modeLiv ==2){
			fraisLiv = 0;
			fraisLiv = "<?php $fraisLiv = retourneFrais(2); echo $fraisLiv->frais;?>"
			fraisLiv = parseFloat(fraisLiv);
			document.getElementById("divFraisLivraison").innerHTML = fraisLiv; 
		}else{
			fraisLiv = 0;
			document.getElementById("divFraisLivraison").innerHTML = fraisLiv;
		}	
		var total = sousTotal+fraisLiv;
		total = Math.round(total*100)/100;
		document.getElementById("divSousTotalLivraison").innerHTML = total;
    }
	//
	
	</script>
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
			echo '<div style="text-align:right;"><a href="panier.php?vide" class="btn btn-default" role="button"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Vider le panier</a></div></br>';
			//echo '<div style="text-align:right;"><a href="panier.php?comm" class="btn btn-default" role="button">Confirmer la commande</a></div>';
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
								<div class="col-lg-1"> 
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
								  <div class="col-md-12">
									<input style="margin:1%" type="submit" name="modifier" value="Modifier la quantité" class="btn btn-primary"/>
									<input style="margin:1%" type="submit" name="supprimer" value="Supprimer cet article" class="btn btn-primary"/>
								  </div>
								</div>
								</fieldset>
								</form>
								</li>';
								
								// Calcule le prix total du panier 
								$total_panier += $produit->prixProduit * $article_acheté['qte'];
						}
		}
		echo '<div style="text-align:right;"><h3>Total: ', number_format($total_panier, 2, ',', ' '), ' €</h3></div>'; // Affiche le total du panier
		echo '<div style="text-align:right;"><a href="panier.php?comm" class="btn btn-default" role="button">Confirmer la commande &rarr;</a></div>';
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
					  echo '<div style="text-align:right;"><h3>Total produit: <div style="display: inline;" id="divTotalLivraison">', number_format($total_panier, 2, '.', ' '), '</div> €</div>
							<div style="text-align:right;"><h3>Livraison : <div style="display: inline;" id="divFraisLivraison">0</div> €</h3></div>
							<div style="text-align:right;"><h3>Sous total : <div style="display: inline;" id="divSousTotalLivraison">', number_format($total_panier, 2, '.', ' '), '</div> €</h3></div>
								<form method="POST" action="panier.php?comm&valider" class="form-horizontal">
								<fieldset>
								<!-- Textarea -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="message">Informations supplémentaires</label>
								  <div class="col-md-4">                     
									<textarea class="form-control" id="message" placeholder="Informations diverses" name="message"></textarea>
								  </div>
								</div>

								<!-- Select Basic -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="liv">Mode de livraison</label>
								  <div class="col-md-4">
									<select onchange="run()" id="liv" name="liv" class="form-control">';
												foreach(retourneListeLivraison() as $element) // retourne un array de mode de livraison 
														{
															$res = retourneFrais($element->idModeLivraison);
															echo '<option value="'.$element->idModeLivraison.'">'.$element->libelleModeLivraison.'  '.$res->frais.'€</option>';
														}
												echo '</select>
								  </div>
								</div>
								<!-- Select Basic -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="selectbasic">Mode de paiement</label>
								  <div class="col-md-4">
									<select name="paie" id="paie" class="form-control">';
									  foreach(retourneListePaiement() as $element) // retourne un array de mode de livraison 
										{
											echo '<option value="'.$element->idModePaiement.'">'.$element->libelleModePaiement.'</option>'; 
										}
							   echo '</select>
								  </div>
								</div>

								<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="singlebutton"></label>
								  <div class="col-md-4">
									<button id="singlebutton" name="singlebutton" class="btn btn-primary">Je confirme ma commande </button>
								  </div>
								</div>

								<input type="hidden" name="prix" value="'.$total_panier.'"></input>
								
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
	
				if(isset($_SESSION['panier'])){
					foreach($_SESSION['panier'] as $id_article=>$article_acheté)
					{
						ajouterLigneCommande($res,$id_article,$article_acheté['qte']);
						deduireStock($id_article,$article_acheté['qte']);
					}
				}else{
					echo '<div class="alert alert-danger" role="alert">Une erreur est survenue !</div>';
					die;
				}
				
				viderPanier();
				echo '<div class="alert alert-success" role="alert">La commande a été effectuée avec succès</div>';
				if($_POST['paie'] == 3 || $_POST['paie'] == 4){
					echo '<a href="panier.php?comm&valider&payer&ok&id='.$res.'" class="btn btn-default" role="button">Payer cette commande [fictif]</a><br/><br/>';
				}else{
					echo "<div class='panel panel-default'><div class='panel-body'>Veuillez adresser votre paiement à l'adresse suivante : </br>".retourneParametre('ordre');
					echo '</br>Indiquez la référence de la commande : <b>'.$res.'</b> <br/><br/><a href="commande.php" class="btn btn-default" role="button">Voir vos commandes</a></div></div>';
				}
				
				echo '<h3>Prix total de la commande <b>: '.number_format($total_panier, 2, ',', ' '), ' €</b></h3>';
				
			}
			else			
			{
				if(isset($_GET['ok']))
				{
					$commande = retourneCommande($_GET['id']);
					if($commande->modePaiement == 1 || $commande->modePaiement == 2)
					{
						//echo "Veuillez adresser votre paiement à l'adresse suivante : </br>".retourneParametre('ordre');
						//echo '</br><a href="commande.php" class="btn btn-default" role="button">Voir vos commandes</a>';
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