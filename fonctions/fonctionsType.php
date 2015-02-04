<?php
function retourneLibelleType($idType){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	$requete = $connexion->query("SELECT libelleType FROM type where idType = ".$idType."");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $requete->fetch();
	
	return $enregistrement->libelleType; // on renvoie un objet utilisateur
}
function ajouterType($libelleType){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`type` (`idType`, `libelleType`) VALUES (NULL, '".$libelleType."');");
		return true; 
	} catch ( Exception $e ) {
		return false;
		echo "Une erreur est survenue";
	}
}

function retourneListeType(){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	$types = array();
	$requete = $connexion->query("SELECT * FROM type");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	while($enregistrement = $requete->fetch()){
		$types[] = $enregistrement; // on ajoute à $types[n+1] un objet utilisateur
	}
	return $types;
}
?>