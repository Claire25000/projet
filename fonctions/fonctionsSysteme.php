<?php
function idUtilisateurConnecte(){
	return $_SESSION['idUtilisateur '];
}
function typeUtilisateurConnecte(){
	return $_SESSION['typeUtilisateur '];
}

function chargerParametres(){
	// Devrons êtres chargés via la base de donnée en version finale
	if(!isset($_SESSION['param_mailSite'])){$_SESSION['param_mailSite'] = 'mailEnvoye@mail.ru';}
	if(!isset($_SESSION['param_nomSite'])){$_SESSION['param_nomSite'] = 'Le site internet des patates';}
	if(!isset($_SESSION['param_afficherCommentaire'])){$_SESSION['param_afficherCommentaire'] = 'true';}
	if(!isset($_SESSION['param_repertoireUpload'])){$_SESSION['param_repertoireUpload'] = './upload/';}
}
function retourneParametre($libelle){
	if($_SESSION['param_'.$libelle.''] != null){
		return $_SESSION['param_'.$libelle.''];
	}
	return "PARAMETRE INCONNU";
}
function chargerErreurs(){
	// Devrons êtres chargés via la base de donnée en version finale
	if(!isset($_SESSION['error_0'])){$_SESSION['error_0'] = 'Erreur inconue';}
	if(!isset($_SESSION['error_1'])){$_SESSION['error_1'] = 'Vous n\'avez pas les privilèges nécéssaires';}
	if(!isset($_SESSION['error_404'])){$_SESSION['error_404'] = 'Erreur 404 page non trouvée';}
}
function retourneErreur($libelle){
	if($_SESSION['error_'.$libelle.''] != null){
		return $_SESSION['error_'.$libelle.''];
	}
	return "ERREUR INCONNUE";
}
function envoyeMail($sujet,$message,$destinataire){
	$headers = "From: \"expediteur moi\"<moi@domaine.com>\n";
	$headers .= "Reply-To: moi@domaine.com\n";
	$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"";
	if(mail($destinataire,$sujet,$message,$headers)){
		echo "L'email a bien été envoyé.";
	}else{
		echo "Une erreur c'est produite lors de l'envois de l'email.";
	}
}
// --------------------------------- FONCTIONS IMAGE  --------------------------------- //



?>