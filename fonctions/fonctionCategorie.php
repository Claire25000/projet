<?php
function ajouterCategorie($lib)
{
	global $connexion;

	try
	{
		$requete = $connexion->query("SET NAMES 'utf8'"); 
		$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`categorie` values (DEFAULT,'".$lib."')"); //on insère la catégorie dans la base
		$requete->execute();
		return true;
	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
		return false;
	}
}

function modifierCategorie($id,$lib)
{
	global $connexion;
	
	try
	{
		$requete = $connexion->query("SET NAMES 'utf8'"); 
		$requete = $connexion->prepare("update categorie set libelleCategorie = '".$lib."' where idCategorie = ".$id);
		$requete->execute();
		return true;
	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
		return false;
	}
}

function supprimerCategorie($id)
{
	global $connexion;
	
	try
	{
		$requete = $connexion->query("SET NAMES 'utf8'"); 
		$requete = $connexion->prepare("delete from categorie where idCategorie = ".$id);
		$requete->execute();
		return true;
	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
		return false;
	}
}

function listeCategorie()
{
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	$cat = array();
	$requete = $connexion->query("SET NAMES 'utf8'");
	$requete = $connexion->query("SELECT * FROM categorie");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	while($c = $requete->fetch()){
		$cat[] = $c; 
	}
	return $cat;
}

function retourneLibelle($id)
{
	global $connexion;
	
	$sql = $connexion->query("SET NAMES 'utf8'");
	$sql = $connexion->query("select * FROM categorie where idCategorie=".$id);
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$res = $sql->fetch();
	
	return $res->libelleCategorie;
}
?>