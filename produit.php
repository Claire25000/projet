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
	ajouterPanier($_GET['id'],1);
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

		 
		if(estConnecte() == true)
		{
			if(estAdmin(idUtilisateurConnecte()) == true)
			{
				echo "<a href='produit.php?modif&id=".$_GET['id']."'>Modifier le produit</a>";
			}
		}
		echo "<table border='1'>
				<tr>
					<th>Numéro du produit</th>
					<th>Nom du produit</th>
					<th>Description du produit</th>
					<th>Prix du produit</th>
					<th>Catégorie</th>
				</tr>";
				
			$sql = $connexion->query("SET NAMES 'utf8'"); 
			$sql = $connexion->query("Select nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$_GET['id']);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			echo "<tr>
					<td>".$res->idProduit."</td>
					<td>".$res->nomProduit."</td>
					<td>".$res->descriptionProduit."</td>
					<td>".$res->prixProduit."</td>
					<td>".$res->libelleCategorie."</td>
				 </tr>
				</table>";
			// ------------------------------------------------------------------------------ //
			$sql = $connexion->query("SET NAMES 'utf8'"); 
			$sql = $connexion->query("Select data.idNom, nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$_GET['id']);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			echo "<label>Caractéristiques : </label>
			<ul>";
			
					while($resultat = $sql->fetch())
					{
						echo "<tr>
						<li>".$resultat->nom." : ".$resultat->valeur."</li>";
					}
				echo "</ul><br/>";
			echo '<div style="text-align:right;"><a href="produit.php?ajouterPanier&id='.$res->idProduit.'" class="btn btn-default" role="button">Ajouter au panier</a></div>';
		
				// ------------------------------------------------------------------------------ //
			if(estConnecte() == true)
			{
				$req = $connexion->query("SET NAMES 'utf8'");	
				$req = $connexion->query("Select produit.*, categorie.libelleCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$_GET['id']);
				$req->setFetchMode(PDO::FETCH_OBJ);
				$res = $req->fetch();
				
				if(retourneParametre("afficherCommentaire"))
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
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>