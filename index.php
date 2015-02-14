<?php
if(isset($_GET['logout'])){
	$message = '<div class="alert alert-danger" role="alert">Vous avez été déconnecté</div>';
}
require_once('inc/inc_top.php');
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Accueil</title>
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
		<p style="padding-top:3%">
			<?php echo retourneParametre('textAccueil'); ?>
		</p>
		<hr>
		<p>Derniers articles ajoutés : </p>
		<?php //----------------------------------------------- Affichage des derniers produits --------------------------//
			$maRequete = "Select * from produit ORDER BY idProduit DESC LIMIT 0,3";
			$req = $connexion->query("SET NAMES 'utf8'");
			$req = $connexion->query($maRequete);
			$req->setFetchMode(PDO::FETCH_OBJ);
			while($res = $req->fetch()){
								echo '
				
				  <div class="col-sm-4 col-md-4">
					<div style="" class="thumbnail">
						<a href="produit.php?id='.$res->idProduit.'">
							<img style="border:2px solid gray" src="'.retourneParametre("repertoireUpload").''.$res->image.'" alt="">
						</a>
					  <div class="caption">
						<h3>'.$res->nomProduit.'</h3>
						</div>
					</div>
				  </div>
				
				';
			}
		?>
		
		
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
