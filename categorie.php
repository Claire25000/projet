
<?php
if(!isset($_GET['id'])){
	header('Location: ../404.php?err=404'); // si on a pas d'ID, on redirige immédiatement en erreur
}else{
	if(is_numeric($_GET['id'])){ // si l'id est uniquement numérique
		$idCategorie = intval($_GET['id']); // on enregistre une valeur numérique forée de l'ID
	}else{ // si l'ID n'est pas numérique on redirige vers sa version numérique
		header("Location:categorie.php?id=".intval($_GET['id'])."&titleCat=".$_GET['titleCat'].""); 
		exit;
	}
	
}

require_once('inc/inc_top.php');
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionCategorie.php");


if(isset($_GET['croissant'])){
	$maRequete = "Select * from produit where idCategorie=".$idCategorie." ORDER BY prixProduit ASC";
}else if(isset($_GET['decroissant'])){
	$maRequete = "Select * from produit where idCategorie=".$idCategorie." ORDER BY prixProduit DESC";
}else if(isset($_GET['note'])){
	$maRequete = "SELECT *,AVG(notation.note) as noteMoyenne FROM `produit`,`notation` WHERE produit.idProduit = ".$idCategorie." AND produit.idProduit = notation.idProduit ORDER BY AVG(notation.note) DESC"; // A FAIRE
}else{
	$maRequete = "Select * from produit where idCategorie=".$idCategorie." ORDER BY idProduit DESC";
}
$req = $connexion->query("SET NAMES 'utf8'");
$req = $connexion->query($maRequete);
$req->setFetchMode(PDO::FETCH_OBJ);
			
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Catégorie <?php echo retourneLibelle($idCategorie); ?></title>
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
		<form class="form-horizontal"> <!-- select pour modifier le tri des produits de la categorie -->
			<fieldset>
			<!-- Select Basic -->
			<div class="form-group">
			  <label class="col-md-6 control-label" for="tri"></label>
			  <div class="col-md-6">
				<select id="tri" name="tri" class="form-control" onChange="top.location.href='categorie.php?id=<?php echo $idCategorie;?>&titleCat=<?php echo $_GET['titleCat'];?>'+this.options[this.selectedIndex].value;">
				  <option value="#">Trier les articles</option>
				  <option <?php if(isset($_GET['croissant'])){echo'selected="selected"';}?> value="&croissant">Prix : ordre croissant</option>
				  <option <?php if(isset($_GET['decroissant'])){echo'selected="selected"';}?> value="&decroissant">Prix : ordre décroissant</option>
				  <option <?php if(isset($_GET['note'])){echo'selected="selected"';}?> value="&note">Note moyenne</option>
				  <option <?php if(isset($_GET['date'])){echo'selected="selected"';}?> value="&date">Date d'ajout</option>
				  
				</select>
			  </div>
			</div>
			</fieldset>
			</form>

		<?php
			$i = 1; // variable d'incrementation
			 //on récupère les produits voulus
			while($res = $req->fetch())
			{
				if($i == 1){
					echo '<div class="row form-group product-chooser">';
				}
				
				// Encart de présentation du produit
				echo '
				
				  <div class="col-sm-6 col-md-3">
					<div style="min-height: 410px" class="thumbnail">
					  <img src="'.retourneParametre("repertoireUpload").''.$res->image.'" alt="">
					  <div class="caption">
						<h3>'.$res->nomProduit.'</h3>
						<p>'.number_format($res->prixProduit, 2, ',', ' ').' €</p>
						<p><a href="produit.php?id='.$res->idProduit.'" class="btn btn-primary btn-lg btn-block" role="button">Fiche produit</a></p>
						</div>
					</div>
				  </div>
				
				';
				
				if($i%4==0 && $i != 1){ // tous les 4 enregistrement on passe a une nouvelle ligne
					echo '</div>';
					echo '<div class="row form-group product-chooser">';
				}
			
				//echo "".$res->idProduit."";
				echo '';
				
				
				$i++;
			}
		?>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
