<?php
require_once("fonctionsSysteme.php");

function ajouterCommentaire($idProd,$comm)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`commentaire` values (".$idProd.",".idUtilisateurConnecte().",'".addslashes($comm)."',NOW())"); //on insère le commentaire dans la base
			$requete->execute();
			return true;			
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function retourneListeCommentaire($idProd)
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select commentaire.* from commentaire where idProduit = '".$idProd."' ORDER BY date DESC;");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}

function supprimerCommentaire($idProd)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->prepare("delete from commentaire where idUtilisateur = ".idUtilisateurConnecte()." and idProduit = ".$idProd);
			$requete->execute();	
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
		}
}

function commentaireExiste($idProd,$idUtilisateur)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select commentaire.* from commentaire where idProduit = ".$idProd." and idUtilisateur =".$idUtilisateur);
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	if($req->fetch())
	{
		return 1;
	}
	else
	{
		return 0;
	}
}
?>