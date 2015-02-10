<?php
function idUtilisateurConnecte(){
	if(isset($_SESSION['idUtilisateur '])){
		return $_SESSION['idUtilisateur '];
	}
	return 0;
}
function typeUtilisateurConnecte(){
	if(isset($_SESSION['typeUtilisateur '])){
		return $_SESSION['typeUtilisateur '];
	}
	return 0;
}

function chargerParametres(){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SET NAMES 'utf8'"); 
	$requete = $connexion->query("SELECT * FROM parametre WHERE cle LIKE 'param_%'");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	while($enregistrement = $requete->fetch()){
		if(!isset($_SESSION[''.$enregistrement->cle.''])){$_SESSION[''.$enregistrement->cle.''] = ''.$enregistrement->valeur.'';}
	}
	/*
	if(!isset($_SESSION['param_mailSite'])){$_SESSION['param_mailSite'] = 'mailEnvoye@mail.ru';}
	*/
}
function retourneParametre($libelle){
	if($_SESSION['param_'.$libelle.''] != null){
		return $_SESSION['param_'.$libelle.''];
	}
	return "PARAMETRE INCONNU";
}
function chargerErreurs(){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SET NAMES 'utf8'"); 
	$requete = $connexion->query("SELECT * FROM parametre WHERE cle LIKE 'error_%'");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	while($enregistrement = $requete->fetch()){
		if(!isset($_SESSION[''.$enregistrement->cle.''])){$_SESSION[''.$enregistrement->cle.''] = ''.$enregistrement->valeur.'';}
	}
	/*
	if(!isset($_SESSION['error_0'])){$_SESSION['error_0'] = 'Erreur inconue';}
	*/
}
function retourneErreur($libelle){
	if($_SESSION['error_'.$libelle.''] != null){
		return $_SESSION['error_'.$libelle.''];
	}
	return "ERREUR INCONNUE";
}

/*function envoyeMail($sujet,$message,$destinataire){

$expediteur = retourneParametre('mailSite');
$reponse = $expediteur;

mail($destinataire,$sujet,$message,
     "From: $expediteur\r\n".
        "Reply-To: $reponse\r\n".
        "Content-Type: text/html; charset=\"iso-8859-1\"\r\n");
}*/
function envoyeMail($sujet,$message,$destinataire){
	// rien
}

// --------------------------------- FONCTIONS IMAGE  --------------------------------- //
?>