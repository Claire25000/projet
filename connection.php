<?php
// 1 -> en ligne
// 0 -> en local
$online = 0;

if($online == 1){
	try
	{
		$dns = 'mysql:host=mysql51-150.perso;dbname=webuzzer54gs9';
		$utilisateur = 'webuzzer54gs9';
		$motDePasse = 'Webuzzer14da';
		$connexion = new PDO( $dns, $utilisateur, $motDePasse );
	}
	catch(Exception $e)
	{
			die('Erreur : '.$e->getMessage());
	}
}else{
	try
	{
		$dns = 'mysql:host=localhost;dbname=webuzzer54gs9';
		$utilisateur = 'root';
		$motDePasse = '';
		$connexion = new PDO( $dns, $utilisateur, $motDePasse );
	}
	catch(Exception $e)
	{
			die('Erreur : '.$e->getMessage());
	}
}
?>