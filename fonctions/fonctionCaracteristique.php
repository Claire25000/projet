<?php
function ifNomExist($nom)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select * from data_nom");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		if($res->nom == $nom)
		{
			return $res->idNom;
		}
	}
	return 0;
}

function ifValeurExist($valeur)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select * from data_valeur");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		if($res->valeur == $valeur)
		{
			return $res->idValeur;
		}
	}
	return 0;
}

function ajouterNom($nom)
{
	global $connexion;

	try
	{
		$requete = $connexion->query("SET NAMES 'utf8'"); 
		$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`data_nom` values (DEFAULT,'".addslashes($nom)."')"); //on insère le nom dans la base
		$requete->execute();
		return true;
	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
		return false;
	}
}

function ajouterValeur($valeur)
{
	global $connexion;
	
	try
	{
		$requete = $connexion->query("SET NAMES 'utf8'"); 
		$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`data_valeur` values (DEFAULT,'".addslashes($valeur)."')"); //on insère la valeur dans la base
		$requete->execute();
		return true;
	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
		return false;
	}
}

function genererNomCategorie($cat)
{
	global $connexion;
	
	$noms = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select distinct data_nom.* from data_nom, data, produit where data_nom.idNom = data.idNom and data.idProduit = produit.idProduit and idCategorie = ".$cat);
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$noms[] = $res;
	}
	
	return $noms;
}

function genererValeurNom($nom)
{
	global $connexion;
	
	$val = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select distinct data_valeur.* from data_valeur, data where data.idValeur = data_valeur.idValeur and idNom = ".$nom);
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$val[] = $res;
	}
	
	return $val;
}

function getIdNom($nom)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select * from data_nom where nom = '".addslashes($nom)."'");
	$req->setFetchMode(PDO::FETCH_OBJ);
	$res = $req->fetch();
	
	return $res->idNom;
}

function getIdValeur($val)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select * from data_valeur where valeur = '".addslashes($val)."'");
	$req->setFetchMode(PDO::FETCH_OBJ);
	$res = $req->fetch();
	
	return $res->idValeur;
}

?>