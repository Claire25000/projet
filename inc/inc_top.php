<?php
// Inclus première ligne des fichiers de /
session_start();

require_once("connection.php");
require_once("fonctions/fonctionsUtilisateur.php");
require_once("fonctions/fonctionsClient.php");
require_once("fonctions/fonctionsSysteme.php");

chargerParametres(); // On charge tous les paramètres du site
chargerErreurs(); // On charge toutes les erreurs du site

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){
		header('Location: index.php');
	}
}
if(isset($_POST['email']) && isset($_POST['password'])){
	echo "ok";
	if(connecteUtilisateur($_POST['email'],$_POST['password']))
		{
			$message = "Connexion réussie<br/>";
		}
		else{
			$message = "Echec de l'authentification.<br/>";
		}
}
?>