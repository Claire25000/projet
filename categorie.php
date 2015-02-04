
<?php
if(!isset($_GET['id'])){header('Location: ../404.php?err=404');} // si on a pas d'ID, on redirige immédiatement en erreur
require_once('inc/inc_top.php');
require_once("fonctions/fonctionProd.php");
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
			if(isset($message)){
			echo $message;
		}
		?>
		<?php
			$req = $connexion->query("SET NAMES 'utf8'");
			$req = $connexion->query("Select * from produit where idCategorie=".$_GET['id']."");
			$req->setFetchMode(PDO::FETCH_OBJ);
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
					<div class="thumbnail">
					  <img src="'.$res->image.'" alt="">
					  <div class="caption">
						<h3>Thumbnail label</h3>
						<p>...</p>
						<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
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
