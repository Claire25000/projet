<?php
require_once('inc/inc_top.php');
if(!isset($_GET['id'])) // si on a pas d'id produit on redirige
{
	header("Location:404.php?err=202");
	exit;
}else{
	if(is_numeric($_GET['id'])){ // si l'id est uniquement numérique
		$idProduit = intval($_GET['id']); // on enregistre une valeur numérique forée de l'ID
	}else{ // si l'ID n'est pas numérique on redirige vers sa version numérique
		header("Location:produit.php?id=".intval($_GET['id']).""); 
		exit;
	}
	
}
require_once("fonctions/fonctionComm.php");
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionPanier.php");
require_once("fonctions/fonctionsNotation.php");

if(isset($_GET['supp']))
{
	if(!isset($_POST['validSuprComm'])){
		$message = '<div class="alert alert-info" role="alert">
					<form method="POST" action="produit.php?id='.$idProduit.'&supp" class="form-horizontal">
					<fieldset>
					<!-- Multiple Radios (inline) -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="validSuprComm">Valider la supression du commentaire ?</label>
					  <div class="col-md-4"> 
						<label class="radio-inline" for="validSuprComm-0">
						  <input name="validSuprComm" id="validSuprComm-0" value="1" checked="checked" type="radio">
						  Oui
						</label> 
						<label class="radio-inline" for="validSuprComm-1">
						  <input name="validSuprComm" id="validSuprComm-1" value="2" type="radio">
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
	}else if(isset($_POST['validSuprComm']) && $_POST['validSuprComm'] == '1'){
		supprimerCommentaire($idProduit);
	}	
}

if(isset($_GET['ajouterPanier']))
{
	if(retourneStock($idProduit) - getQteProduit($idProduit) > 0){ // si la quantité du produit ajouté ne dépasse pas le stock du produit<
		if(ajouterPanier($idProduit,1)){$message = '<div class="alert alert-success" role="alert">Le produit a été ajouté à votre panier.</div>';}
	}else{
		$message = '<div class="alert alert-danger" role="alert">Le produit n\'est plus en stock suffisant, n\'hésitez pas à nous contacter !</div>';
	}
}

if(isset($_POST['note']) || isset($_GET['note'])){
	if(isset($_GET['note'])){$note = $_GET['note'];}else{$note = $_POST['note'];}
	
	if(estConnecte()){
		if(aDejaNote(idUtilisateurConnecte(),$idProduit)){ // l'utilisateur a déja noter on met a jour 
			if(actualiserNotation($idProduit,idUtilisateurConnecte(),$note)){
				$message = '<div class="alert alert-success" role="alert">Votre note a été mise à jour, merci !</div>';
			}
		}
		else if(ajouterNotation($idProduit,idUtilisateurConnecte(),$note)){ // l'utilisateur n'avais pas encore noté : on insert la note
			$message = '<div class="alert alert-success" role="alert">Votre note a été ajoutée, merci !</div>';
		}
	}else{
		$message = '<div class="alert alert-danger" role="alert">Vous devez êtres connecté pour noter ce produit.</div>';
	}	
}

if(isset($_POST['commenter'])){
	if(estConnecte()){
		if(ajouterCommentaire($idProduit,$_POST['message'])){
			$message = '<div class="alert alert-success" role="alert">Votre commentaire a été ajouté, merci !</div>';
		}
	}	
}

 //on récupère le produit voulu
$req = $connexion->query("SET NAMES 'utf8'");	
$req = $connexion->query("Select produit.*, categorie.libelleCategorie, categorie.idCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$idProduit);
$req->setFetchMode(PDO::FETCH_OBJ);
$resProd = $req->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>  
	<?php require_once("inc/inc_head.php");?>
    <title><?php echo $resProd->nomProduit; ?></title>
	<style>
	.user_name{
    font-size:14px;
    font-weight: bold;
	}
	.comments-list .media{
		border-bottom: 1px dotted #ccc;
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
		if(isset($message)){
			echo $message;
		}
		?>
		
		<div class="content-wrapper">	
			<div style="margin-top: -2%;padding:1%" class="item-container">	
				<div class="container" style="padding-left:15px">	
					
						<div style="padding: 1%" class="product col-md-4 service-image-left">
							<img id="item-display" src="<?php echo "".retourneParametre("repertoireUpload")."".$resProd->image."";?>" alt=""></img>
						</div>
					<div class="col-md-7">
						<div class="product-title">
							<?php 
							  echo $resProd->nomProduit; 
							  if(estConnecte() && estAdmin(idUtilisateurConnecte())){echo " <a href='admin/produit.php?modif&id=".$idProduit."&idCat=".$resProd->idCategorie."'>[Modifier]</a>";} // Si admin : Affiche un lien pour modifier le produit
							?>
						</div>
						<div class="product-desc">
							<!---------------------------------------- affichage de la notation ------------------------------->
							<?php
								$nombreEtoilePleine = ceil(retourneNote($idProduit)); // Arrondi au chiffre supérieur
								$nombreEtoileVide = 5 - $nombreEtoilePleine;
								$etoileActuelle = 5;

								echo '<div class="rating">';
									for ($i = 1; $i <= $nombreEtoileVide; $i++) { // étoiles vides (différence entre la note actuelle et la note max)
										echo '<a class="etoileVide" href="produit.php?id='.$idProduit.'&note='.$etoileActuelle.'" title="Donner '.$etoileActuelle.' étoiles">★</a>';
										$etoileActuelle-=1;
										
									}
									for ($i = 1; $i <= $nombreEtoilePleine; $i++) { // étoiles pleines (note du produit)
										echo '<a class="etoilePleine" href="produit.php?id='.$idProduit.'&note='.$etoileActuelle.'" title="Donner '.$etoileActuelle.' étoiles">★</a>';
										$etoileActuelle-=1;
										
									}
								echo '</div>';
							?>
							<?php 
							if(aDejaNote(idUtilisateurConnecte(),$idProduit)){ // si l'utilisateur a déja noté
								$dejaNote = true;
							}else{
								$dejaNote = false;
							}
							?>
						</div>

						<hr>
						<div class="product-price"><?php echo $resProd->prixProduit; ?> €</div>
						<?php 
							$stockActuel = retourneStock($resProd->idProduit) - getQteProduit($resProd->idProduit);
							if($stockActuel>0){
								echo '<div class="product-stock">En Stock</div>';
							}else{
								echo '<div style="color:red" class="product-stock">En rupture de Stock</div>';
							}
						?>
						<hr>
						<?php if(!estConnecte() || !estAdmin(idUtilisateurConnecte()))
						{
							?>
							<div class="btn-group cart">
								<div style="text-align:right;"><a href="produit.php?ajouterPanier&id=<?php echo $resProd->idProduit; ?>" <?php if($stockActuel<1){echo 'class="btn btn-danger" disabled="disabled"';}else{echo 'class="btn btn-success"';} ?> role="button">Ajouter au panier</a></div>
							</div>
							<?php
						}
						?>
					</div>
				</div> 
			</div>
			<div class="container-fluid">		
				<div class="col-md-12 product-info">
					<ul id="myTab" class="nav nav-tabs nav_tabs"> <!-- onglets produit -->
						<li class="active"><a href="#service-one" data-toggle="tab">DESCRIPTION</a></li>
						<li><a href="#service-two" data-toggle="tab">COMMENTAIRES</a></li>
						<li><a href="#service-three" data-toggle="tab">NOTATION</a></li>
					</ul>
					
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade in active" id="service-one">
							<section class="container product-info">
								<p>
									<?php echo $resProd->descriptionProduit; ?>
								</p>
									<?php
										// ------------------------------------------------------------------------------ AFFICHAGE CARACTERISTIQUES //
										$sql = $connexion->query("SET NAMES 'utf8'"); 
										$sql = $connexion->query("Select data.idNom, nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$idProduit);
										$sql->setFetchMode(PDO::FETCH_OBJ);
										
										echo "<h3>Caractéristiques : </h3>
										<ul>";
										
												while($resultat = $sql->fetch())
												{
													echo "<tr>
													<li>".$resultat->nom." : ".$resultat->valeur."</li>";
												}
											echo "</ul><br/>";
										//echo '<div style="text-align:right;"><a href="produit.php?ajouterPanier&id='.$resProd->idProduit.'" class="btn btn-default" role="button">Ajouter au panier</a></div>';
									?>
							</section>		  
						</div>
							
						<div class="tab-pane fade" id="service-two">
							<section class="container">
								<?php
									// ------------------------------------------------------------------------------ GESTION COMMENTAIRES//
									echo '<div class="container">';
									if(retourneParametre("afficherCommentaire") == 'true')
									{
										if(estConnecte() == 'true')
										{
											if(commentaireExiste($idProduit,idUtilisateurConnecte()) == 1) // si l'utilisateur a deja commenter
											{
												echo '</br><h3>Vous ne pouvez publiquer qu\'un commentaire par produit.</h3>';
											}
											else{ // si l'utilisateur n'a pas commenter : zone de commentaire
												echo '
													<form action="produit.php?id='.$idProduit.'" method="POST">
														<div>        
															<br style="clear:both">
																<div class="form-group col-md-12 ">                                
																	<label id="messageLabel" for="message">Commentaire : </label>
																	<textarea class="form-control input-sm " type="textarea" id="message" name="message" placeholder="Message" maxlength="250" style="width: 100%; height: 87px;"></textarea>
																		<span class="help-block"><p id="characterLeft" class="help-block ">Limite atteinte</p></span>                    
																</div>
															<br style="clear:both">
															<div class="form-group col-md-4">
															<button class="form-control input-sm btn btn-success disabled" id="btnSubmit" name="commenter" type="submit" style="height:35px">Publier mon commentaire</button>    
														</div>
													</form>
												</br></br>';
											}
										}
										
									  echo '<div class="container">
												<div class="row">
													<div class="col-md-11">
													  <div class="page-header">
													  </div> 
													   <div class="col-md-12 comments-list">';
													   ////////////////////////////////////////////////////////////////////////////////////////////////////////
														foreach(retourneListeCommentaire($idProduit) as $element) // on boucle sur les commentaires du produit
														{
															$date = new DateTime($element->date);
															$note = '';
															if(aDejaNote($element->idUtilisateur,$idProduit) == true){$note = intval(retourneNoteUtilisateur($idProduit,$element->idUtilisateur)).'/5';} // si l'user a noté on affiche la note
																	
															echo '	<div class="media">
																	<p class="pull-right"><small>le '.$date->format('d/m/Y').'</small></p>
																		<div class="media-body">
																			<h4 class="media-heading user_name">';
															if(estConnecte() == 'true'){if($element->idUtilisateur == idUtilisateurConnecte()){echo "  <a href='produit.php?id=".$idProduit."&supp'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>";}}
															echo '		'.retourneUtilisateur($element->idUtilisateur)->login.'
																				<span class="label label-primary">'.$note.'</span>
																			</h4>
																			<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> '.$element->comm.'
																		</div>
																	</div>';
														}
														////////////////////////////////////////////////////////////////////////////////////////////////////////
											echo 	'	</div>
														</div>
													</div>
												</div>
											</div>';
									}
								?>
							</section>
						</div>
						<div class="tab-pane fade" id="service-three">
							<section style="padding-top:3%;" class="container">
								<div class="col-lg-6">
								<form action="produit.php?id=<?php echo $idProduit;?>" method="POST">
								<div class="input-group">
								  <select id="note" name="note" class="form-control">
								  <?php
									if(aDejaNote(idUtilisateurConnecte(),$idProduit)){$note = retourneNoteUtilisateur($idProduit,idUtilisateurConnecte());}
									
									for ($i = 1; $i <= 5; $i++) {
										if(isset($note)){
											if($note == $i){ // si l'utilisateur a noté on préséléctionne la note
												echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
											}else{
												echo ' <option value="'.$i.'">'.$i.'</option>';
											}
										}else{
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
										
									}
									?>
								  </select>
								  <span class="input-group-btn">
									<?php
									if(aDejaNote(idUtilisateurConnecte(),$idProduit)){
										echo '<button class="btn btn-default" type="submit">Mettre à jour la note</button>';
									}else{
										echo '<button class="btn btn-default" type="submit">Noter le produit</button>';
									}
									?>
									
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