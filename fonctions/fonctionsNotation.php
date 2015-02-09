<?php
function ajouterNotation($idProduit,$idUtilisateur,$note){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`notation` (`idProduit`, `idUtilisateur`, `note`) VALUES('".$idProduit."', '".$idUtilisateur."', '".$note."');");
		return true; 
	} catch ( Exception $e ) {
		return false;
		echo "Une erreur est survenue";
	}
}
function retourneNote($idProduit){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SELECT AVG( note ) as nb FROM `notation`WHERE idProduit =".$idProduit);
	$res = $requete->fetch();
	$nb = $res['nb'];
	
	return $nb;
}
?>