<?php
require_once('inc/inc_top.php');

require_once("../fonctions/fonctionComm.php");
require_once("../fonctions/fonctionProd.php");

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}
?>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	
	<body>
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
		genererCategorieProduit();
		echo 'page de présentation des produits <br/>';
		if(isset($_GET['ajout']))
		{
			formAjouterProduit();
		}
		elseif(isset($_GET['supp']))
		{
			formSupprimerProduit($_GET['id'],$_GET['idCat']);
		}
		elseif(isset($_GET['modif']))
		{
			formModifierProduit($_GET['id']);
			if(isset($_GET['idNom']))
			{
				formAjouterCaracteristiquesValeur($_GET['idNom']);
			}else
			{
				$sql = $connexion->query("SET NAMES 'utf8'"); 
				$sql = $connexion->query("Select idCategorie from produit where idProduit = ".$_GET['id']);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$res = $sql->fetch();
				
				formAjouterCaracteristiquesNom($res->idCategorie);
			}
		}
		elseif(isset($_GET['id']))
		{
			afficherProduitDetails($_GET['id']);
		}
		elseif(isset($_GET['idCat']))
		{
			echo 'Catégorie : '.$_GET['idCat'];
			afficherProduitAdmin($_GET['idCat']);
		}
	?>
	</body>
</html>
