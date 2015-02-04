<?php
// inclus au dessus de tous les fichiers admin
session_start(); // On déclare l'ouverture d'une session
require_once("../fonctions/fonctionsUtilisateur.php");
require_once("../fonctions/fonctionsSysteme.php");
require_once("../connection.php");


if(estConnecte() && !estAdmin()){ // utilisateur connecté mais pas admin : on redirige
	header('Location: ../404.php?err=1');
}
else if(!estConnecte()){ // sinon si'il n'est pas connecté, on lui affiche le formulaire de loggin sur la page d'accueil de l'administration
	if($_SERVER['PHP_SELF'] != "/admin/index.php"){ // s'il est sur la page index, on ne redirige pas
		header('Location: index.php');
	}
}
?>