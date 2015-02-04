<?php
require_once("inc/inc_top.php");
if(!estConnecte() || estAdmin()){ // si non connecté, on login en tant qu'admin
	header('Location: 404.php?err=1');
}
require_once("fonctions/fonctionsCommande.php");
?>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	</head>
	
	<body>
		<?php
			require_once('inc/inc_menu.php');
		?>
		<p> suivi des commandes et archibes </p>
	</body>
</html>
