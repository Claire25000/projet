<?php
require_once("fonctionsUtilisateur.php");

function retourneClient($idUtilisateur){
	global $connexion; // on définie la variables globale de connection dans la fonction
	$requete = $connexion->query("SELECT * FROM client where idUtilisateur=".$idUtilisateur."");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $requete->fetch();

	return $enregistrement; // on renvoie un objet client
}
// ajouterClient("clientx","clientx","nomx","prenomx","adressex","emailx")
// retourne true si le client est ajouté, false sinon
function ajouterClient($login,$password,$nom,$prenom,$adresse,$email){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$type=3; // Type 3 : client
	$idUtilisateur = ajouterUtilisateur($login,$password,$email,$type);
	
	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`client` (`idUtilisateur`, `nomCli`, `prenomCli`, `adrCli`) VALUES ('".$idUtilisateur."', '".$nom."', '".$prenom."', '".$adresse."');");
		return true; 
	} catch ( Exception $e ) {
		return false;
		echo "Une erreur est survenue";
	}
}
// nombreClient()
// retourne le nombre total de client
function nombreClient(){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SELECT idUtilisateur FROM client");
	$requete->setFetchMode(PDO::FETCH_OBJ);
 	return $requete->rowCount();
}

function changerNomClient($idClient,$nomClient){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("UPDATE `webuzzer54gs9`.`client` SET `nomCli` = '".$nomClient."' WHERE `client`.`idUtilisateur` =".$idClient.";");
		return true; 
	} catch ( Exception $e ) {
		return false;
		echo "Une erreur est survenue";
	}
}

function changerPrenomClient($idClient,$prenomClient){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("UPDATE `webuzzer54gs9`.`client` SET `prenomCli` = '".$prenomClient."' WHERE `client`.`idUtilisateur` =".$idClient.";");
		return true; 
	} catch ( Exception $e ) {
		return false;
		echo "Une erreur est survenue";
	}
}
function changerAdresseClient($idClient,$adresse){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("UPDATE `webuzzer54gs9`.`client` SET `adrCli` = '".$adresse."' WHERE `client`.`idUtilisateur` =".$idClient.";");
		return true; 
	} catch ( Exception $e ) {
		return false;
		echo "Une erreur est survenue";
	}
}
?>