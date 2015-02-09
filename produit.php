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
	if(retourneStock($_GET['id']) - getQteProduit($_GET['id']) > 0){ // si la quantité du produit ajouté ne dépasse pas le stock du produit<
		if(ajouterPanier($_GET['id'],1)){$message = '<div class="alert alert-success" role="alert">Le produit a été ajouté à votre panier.</div>';}
		
	}else{
		$message = '<div class="alert alert-danger" role="alert">Le produit n\'est plus en stock suffisant, n\'hésitez pas à nous contacter !</div>';
	}
	
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
		$req = $connexion->query("SET NAMES 'utf8'");	
		$req = $connexion->query("Select produit.*, categorie.libelleCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$_GET['id']);
		$req->setFetchMode(PDO::FETCH_OBJ);
		$res = $req->fetch();
		 //on récupère le produit voulu
		 
		// --------------------------------- AFFICHAGE DU PRODUIT
		$sql = $connexion->query("SET NAMES 'utf8'"); 
		$sql = $connexion->query("Select nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$_GET['id']);
		$sql->setFetchMode(PDO::FETCH_OBJ);
		?>
		
		<div class="content-wrapper">	
			<div style="padding:1%" class="item-container">	
				<div class="container" style="padding-left:15px">	
					
						<div style="padding: 1%" class="product col-md-4 service-image-left">
							<img id="item-display" src="<?php echo "".retourneParametre("repertoireUpload")."".$res->image."";?>" alt=""></img>
						</div>
						
						<!-- AJOUT DE PLUSIEURS IMAGES 
						<div class="container service1-items col-sm-2 col-md-2 pull-left">
							<center>
								<a id="item-1" class="service1-item">
									<img src="http://www.corsair.com/Media/catalog/product/g/s/gs600_psu_sideview_blue_2.png" alt=""></img>
								</a>
								<a id="item-2" class="service1-item">
									<img src="http://www.corsair.com/Media/catalog/product/g/s/gs600_psu_sideview_blue_2.png" alt=""></img>
								</a>
								<a id="item-3" class="service1-item">
									<img src="http://www.corsair.com/Media/catalog/product/g/s/gs600_psu_sideview_blue_2.png" alt=""></img>
								</a>
							</center>
						</div>-->
					
						
					<div class="col-md-7">
						<div class="product-title">
							<?php 
							  echo $res->nomProduit; 
							  if(estConnecte() && estAdmin(idUtilisateurConnecte())){echo " <a href='admin/produit.php?modif&id=".$_GET['id']."'>[Modifier]</a>";} // Si admin : Affiche un lien pour modifier le produit
							?>
						</div>
						<div class="product-desc"></div>
						<div class="product-rating"><i class="fa fa-star gold"></i> <i class="fa fa-star gold"></i> <i class="fa fa-star gold"></i> <i class="fa fa-star gold"></i> <i class="fa fa-star-o"></i> </div>
						<hr>
						<div class="product-price"><?php echo $res->prixProduit; ?> €</div>
						<?php 
							$stockActuel = retourneStock($res->idProduit) - getQteProduit($res->idProduit);
							if($stockActuel>0){
								echo '<div class="product-stock">En Stock</div>';
							}else{
								echo '<div style="color:red" class="product-stock">En rupture de Stock</div>';
							}
						?>
						<hr>
						<div class="btn-group cart">
							<div style="text-align:right;"><a href="produit.php?ajouterPanier&id=<?php echo $res->idProduit; ?>" <?php if($stockActuel<1){echo 'class="btn btn-danger" disabled="disabled"';}else{echo 'class="btn btn-success"';} ?> role="button">Ajouter au panier</a></div>
						</div>
						<!--<div class="btn-group wishlist">
							<button type="button" class="btn btn-danger">
								Add to wishlist 
							</button>
						</div>-->
					</div>
				</div> 
			</div>
			<div class="container-fluid">		
				<div class="col-md-12 product-info">
						<ul id="myTab" class="nav nav-tabs nav_tabs"> <!-- onglets produit -->
							
							<li class="active"><a href="#service-one" data-toggle="tab">DESCRIPTION</a></li>
							<li><a href="#service-two" data-toggle="tab">COMMENTAIRES</a></li>
							<!--<li><a href="#service-three" data-toggle="tab">?</a></li>-->
							
						</ul>
					<div id="myTabContent" class="tab-content">
							<div class="tab-pane fade in active" id="service-one">
								<section class="container product-info">
									<p>
										<?php echo $res->descriptionProduit; ?>
									</p>
									<!-- EXEMPLES D'AFFICHAGE NORMAL DES CARACTERISTIQUES (a adapter avec le systeme de data actuel 
									<h3>Corsair Gaming Series GS600 Features:</h3>
									<li>It supports the latest ATX12V v2.3 standard and is backward compatible with ATX12V 2.2 and ATX12V 2.01 systems</li>
									<li>An ultra-quiet 140mm double ball-bearing fan delivers great airflow at an very low noise level by varying fan speed in response to temperature</li>
									<li>80Plus certified to deliver 80% efficiency or higher at normal load conditions (20% to 100% load)</li>-->

										<?php
											// ------------------------------------------------------------------------------ AFFICHAGE CARACTERISTIQUES //
											$sql = $connexion->query("SET NAMES 'utf8'"); 
											$sql = $connexion->query("Select data.idNom, nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$_GET['id']);
											$sql->setFetchMode(PDO::FETCH_OBJ);
											
											echo "<h3>Caractéristiques : </h3>
											<ul>";
											
													while($resultat = $sql->fetch())
													{
														echo "<tr>
														<li>".$resultat->nom." : ".$resultat->valeur."</li>";
													}
												echo "</ul><br/>";
											//echo '<div style="text-align:right;"><a href="produit.php?ajouterPanier&id='.$res->idProduit.'" class="btn btn-default" role="button">Ajouter au panier</a></div>';
										?>
								</section>		  
							</div>
							
						<div class="tab-pane fade" id="service-two">
							<section class="container">
								<?php
									// ------------------------------------------------------------------------------ GESTION COMMENTAIRES//
									if(estConnecte() == 'true')
									{
										$req = $connexion->query("SET NAMES 'utf8'");	
										$req = $connexion->query("Select produit.*, categorie.libelleCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$_GET['id']);
										$req->setFetchMode(PDO::FETCH_OBJ);
										$res = $req->fetch();
										
										if(retourneParametre("afficherCommentaire") == 'true')
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
							</section>
						</div>
						
						<div class="tab-pane fade" id="service-three">
							<!--*-->
						</div>
					</div>
					<hr>
				</div>
			</div>
		</div>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>