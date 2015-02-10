<?php
require_once('inc/inc_top.php');
if(!isset($_GET['id']))
{
	header("Location:404.php?err=202");
	exit;
}
require_once("fonctions/fonctionComm.php");
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionPanier.php");
require_once("fonctions/fonctionsNotation.php");

if(isset($_GET['supp']))
{
	supprimerCommentaire($_GET['id']);
}

if(isset($_GET['ajouterPanier']))
{
	ajouterNotation(73,1,5);
	if(retourneStock($_GET['id']) - getQteProduit($_GET['id']) > 0){ // si la quantité du produit ajouté ne dépasse pas le stock du produit<
		if(ajouterPanier($_GET['id'],1)){$message = '<div class="alert alert-success" role="alert">Le produit a été ajouté à votre panier.</div>';}
	}else{
		$message = '<div class="alert alert-danger" role="alert">Le produit n\'est plus en stock suffisant, n\'hésitez pas à nous contacter !</div>';
	}
}

if(isset($_POST['note'])){
	if(estConnecte()){
		if(ajouterNotation($_GET['id'],idUtilisateurConnecte(),$_POST['note'])){
			$message = '<div class="alert alert-success" role="alert">Votre note a été ajoutée, merci !</div>';
		}
	}else{
		$message = '<div class="alert alert-danger" role="alert">Vous devez êtres connecté pour noter ce produit.</div>';
	}	
}

 //on récupère le produit voulu
$req = $connexion->query("SET NAMES 'utf8'");	
$req = $connexion->query("Select produit.*, categorie.libelleCategorie, categorie.idCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$_GET['id']);
$req->setFetchMode(PDO::FETCH_OBJ);
$res = $req->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>  
	<?php require_once("inc/inc_head.php");?>
    <title><?php echo $res->nomProduit; ?></title>
  </head>
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron">
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}		 
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
							  if(estConnecte() && estAdmin(idUtilisateurConnecte())){echo " <a href='admin/produit.php?modif&id=".$_GET['id']."&idCat=".$res->idCategorie."'>[Modifier]</a>";} // Si admin : Affiche un lien pour modifier le produit
							?>
						</div>
						<div class="product-desc">
							<?php printf("%.2f",retourneNote($_GET['id'])); // retourne la moyenne des notes du produit arondies x,xx?>
							<?php 
							if(aDejaNote(idUtilisateurConnecte(),$_GET['id'])){ // si l'utilisateur a déja noté
								$dejaNote = true;
							}else{
								$dejaNote = false;
							}
							?>
						</div>
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
						<?php if(estConnecte() && estAdmin(idUtilisateurConnecte()))
						{
						}
						else
						{
						?>
						<div class="btn-group cart">
							<div style="text-align:right;"><a href="produit.php?ajouterPanier&id=<?php echo $res->idProduit; ?>" <?php if($stockActuel<1){echo 'class="btn btn-danger" disabled="disabled"';}else{echo 'class="btn btn-success"';} ?> role="button">Ajouter au panier</a></div>
						</div>
						<?php
						}
						?>
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
							<?php
							if(aDejaNote(idUtilisateurConnecte(),$_GET['id'])){ // s'il a déja noter on affiche pas l'encart
								//echo '<li><a href="#" data-toggle="tab">Vous avez noté</a></li>';
							}else{
								echo '<li><a href="#service-three" data-toggle="tab">NOTATION</a></li>';
							}
							?>
							
							
						</ul>
					<div id="myTabContent" class="tab-content">
							<div class="tab-pane fade in active" id="service-one">
								<section class="container product-info">
									<p>
										<?php echo $res->descriptionProduit; ?>
									</p>
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
									if(retourneParametre("afficherCommentaire") == 'true')
									{
										$req = $connexion->query("SET NAMES 'utf8'");	
										$req = $connexion->query("Select produit.*, categorie.libelleCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$_GET['id']);
										$req->setFetchMode(PDO::FETCH_OBJ);
										$res = $req->fetch();
										
										if(estConnecte() == 'true')
										{
											if(!isset($_POST['btnSubmit']))
											{
												$req = $connexion->query("Select idProduit, nomProduit from produit where idProduit = ".$res->idProduit);
												$req->setFetchMode(PDO::FETCH_OBJ);
												$res = $req->fetch(); //on récupère le produit voulu
											
												echo '
												<div class="container">
													<form action="produit.php?id='.$res->idProduit.'" method="POST">
														<div>        
															<br style="clear:both">
																<div class="form-group col-md-4 ">                                
																	<label id="messageLabel" for="message">Commentaire : </label>
																	<textarea class="form-control input-sm " type="textarea" id="message" name="message" placeholder="Message" maxlength="250" style="width: 574px; height: 87px;"></textarea>
																		<span class="help-block"><p id="characterLeft" class="help-block ">You have reached the limit</p></span>                    
																</div>
															<br style="clear:both">
															<div class="form-group col-md-2">
															<button class="form-control input-sm btn btn-success disabled" id="btnSubmit" name="btnSubmit" type="submit" style="height:35px"> Envoyer</button>    
														</div>
													</form>
												</div>
												</br></br>';
											}
											else
											{
												ajouterCommentaire($res->idProduit,$_POST['message']);
												echo '<div class="alert alert-success" role="alert">Commentaire ajouté !</div>';
											}
										}
											
										foreach(retourneListeCommentaire($_GET['id']) as $element)
										{
											$date = new DateTime($element->date);
											echo '<br/><img src="crayon.png" alt="" width="30" height="30"></img> <u>Par '.retourneUtilisateur($element->idUtilisateur)->login.' le '.$date->format('d/m/Y').' :</u> '.$element->comm;
											
											if(estConnecte() == 'true')
											{
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
							<section style="padding-top:3%;" class="container">
								<div class="col-lg-6">
								<form action="produit.php?id=<?php echo $_GET['id'];?>" method="POST">
								<div class="input-group">
								  <select id="note" name="note" class="form-control">
										          <option value="1">1</option>
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
								  <span class="input-group-btn">
									<button class="btn btn-default" type="submit">Noter le produit</button>
								  </span>
								</div>
								</form>
							  </div>
							</section>
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