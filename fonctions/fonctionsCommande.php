<?php

function retourneCommande($idCommande)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select commande.* from commande where idCommande = ".$idCommande);
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	return $req->fetch();
}


function retourneListeCommandeEnCours($idClient)
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select commande.* from commande where (statut = 1 or statut = 2) and idClient = ".$idClient." order by commande.idCommande desc");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}

function retourneHistoriqueCommande($idClient)
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select commande.* from commande where idClient = ".$idClient." order by commande.idCommande desc");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}

function listeCommande()
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select commande.* from commande order by commande.idCommande desc");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}


function ajouterCommande($date,$statut,$modePaiement,$modeLivraison,$idClient,$info)
{
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`commande` (`idCommande`, `date`, `statut`, `modePaiement`, `modeLivraison`, `idClient`, `info`) VALUES (NULL, '".$date."', '".$statut."', '".$modePaiement."', '".$modeLivraison."','".$idClient."','".$info."');");
		return $connexion->lastInsertId(); 
	} catch ( Exception $e ) {
		return false;
	}
}

function ajouterLigneCommande($idCommande, $idProd, $qte)
{
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`lignecommande` (`idProduit`, `idCommande`, `nombre`) VALUES (".$idProd.", ".$idCommande.", ".$qte.");");
		return true; 
	} catch ( Exception $e ) {
		return false;
	}
}

function retourneLigneCommande($id)
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select * from lignecommande where idCommande = ".$id);
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}

function supprimerCommande($idCommande)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
		try
		{
			$requete = $connexion->prepare("Delete from commande where idCommande = ".$idCommande); //on supprime les commandes dans la base
			$requete->execute();
			
			return true;	
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function supprimerLigneCommande($idCommande,$idProd)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
		try
		{
			$requete = $connexion->prepare("Delete from lignecommande where idProduit = ".$idProd." and idCommande = ".$idCommande); //on supprime les lignes de commandes dans la base
			$requete->execute();
			
			return true;	
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function nombreCommandeEnCours($idClient)
{
	global $connexion;
	
	$requete = $connexion->query(" SELECT COUNT(*) AS nb FROM commande where (statut = 1 or statut = 2) and idClient =".$idClient);
	$res = $requete->fetch();
	$nb = $res['nb'];
	
	return $nb;
}

function nombreCommandeEnCoursTotal()
{
	global $connexion;
	
	$requete = $connexion->query("SELECT COUNT(*) AS nb FROM commande where (statut = 1 or statut = 2)");
	$res = $requete->fetch();
	$nb = $res['nb'];
	
	return $nb;
}

function nombreCommandeHistorique($idClient)
{
	global $connexion;
	
	$requete = $connexion->query(" SELECT COUNT(*) AS nb FROM commande where idClient =".$idClient);
	$res = $requete->fetch();
	$nb = $res['nb'];
	
	return $nb;
}

function changerStatutCommande($idCommande,$statut)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update commande set statut = '".$statut."' where idCommande = ".$idCommande."");
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}

function changerModePaiementCommande($idCommande,$modePaiement)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update commande set modePaiement = '".$modePaiement."' where idCommande = ".$idCommande."");
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}

function changerModeLivraisonCommande($idCommande,$modeLivraison)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update commande set modeLivraison = '".$modeLivraison."' where idCommande = ".$idCommande."");
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}

function retourneStatut($idStatut)
{
	global $connexion;

	$query = $connexion->query("SET NAMES 'utf8'"); 
	$query = $connexion->query("select libelleStatut from statut where idStatut =".$idStatut);
	$query->setFetchMode(PDO::FETCH_OBJ);
	$res = $query->fetch();
	
	return $res->libelleStatut;
}

function retourneLivraison($idLivraison)
{
	global $connexion;

	$query = $connexion->query("SET NAMES 'utf8'"); 
	$query = $connexion->query("select libelleModeLivraison from modeLivraison where idModeLivraison =".$idLivraison);
	$query->setFetchMode(PDO::FETCH_OBJ);
	$res = $query->fetch();
	
	return $res->libelleModeLivraison;
}

function retourneFrais($idLiv)
{
	global $connexion;
	
	$query = $connexion->query("SET NAMES 'utf8'"); 
	$query = $connexion->query("select frais from modeLivraison where idModeLivraison =".$idLiv);
	$query->setFetchMode(PDO::FETCH_OBJ);
	return $query->fetch();
}

function retourneListeLivraison()
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select modelivraison.* from modelivraison");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}

function retournePaiement($idPaiement)
{
	global $connexion;

	$query = $connexion->query("SET NAMES 'utf8'"); 
	$query = $connexion->query("select libelleModePaiement from modePaiement where idModePaiement =".$idPaiement);
	$query->setFetchMode(PDO::FETCH_OBJ);
	$res = $query->fetch();
	
	return $res->libelleModePaiement;
}

function retourneListePaiement()
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select modepaiement.* from modepaiement");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}

?>