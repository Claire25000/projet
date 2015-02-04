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
		}
		elseif(isset($_GET['id']))
		{
			afficherProduitDetails($_GET['id']);
			afficherCaracteristiqueAdmin($_GET['id']);
		}
	?>
	</body>
</html>
