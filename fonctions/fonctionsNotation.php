<?php
function ajouterNotation($idProduit,$idUtilisateur,$note){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`notation` (`idProduit`, `idUtilisateur`, `note`) VALUES('".$idProduit."', '".$idUtilisateur."', '".$note."');");
		return true; 
	} catch ( Exception $e ) {
		return false;
	}
}
function actualiserNotation($idProduit,$idUtilisateur,$note){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec('UPDATE `webuzzer54gs9`.`notation` SET `note` = '.$note.' WHERE `notation`.`idProduit` = '.$idProduit.' AND `notation`.`idUtilisateur` = '.$idUtilisateur.';');
		return true; 
	} catch ( Exception $e ) {
		return false;
	}
}

function retourneNote($idProduit){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SELECT AVG( note ) as nb FROM `notation`WHERE idProduit =".$idProduit);
	$res = $requete->fetch();
	$nb = $res['nb'];
	
	return $nb;
}
function retourneNoteUtilisateur($idProduit,$idUtilisateur){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SELECT note FROM `notation`WHERE idUtilisateur = ".$idUtilisateur." AND idProduit =".$idProduit);
	$res = $requete->fetch();
	$nb = $res['note'];
	
	return $nb;
}
function aDejaNote($idUtilisateur,$idProduit){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	$requete = $connexion->query("SELECT * FROM `notation` WHERE idProduit = ".$idProduit." AND idUtilisateur = ".$idUtilisateur.""); 

	$requete->setFetchMode(PDO::FETCH_OBJ);
 	if($requete->rowCount() == 1){ // si on a pas 1 résultat, l'authentification est un échec
		return true;
	}
	return false;
}
?>