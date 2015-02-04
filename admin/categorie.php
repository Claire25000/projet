<?php
require_once("inc/inc_top.php");
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
		?>
		<?php
		if(isset($_GET['id'])){
			echo 'catégorie : '.$_GET['id'];
			afficherProduitAdmin($_GET['id']);
		}else{
			echo 'pas d\'id de categorie';
		}
		?>
	</body>
</html>
