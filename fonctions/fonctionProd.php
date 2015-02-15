<?php
require_once("fonctionCaracteristique.php");
require_once("fonctionsUtilisateur.php");
require_once("fonctionsSysteme.php");


function retourneProduit($id)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select produit.* from produit where idProduit = ".$id);
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	return $req->fetch();
}

function rechercheProduit($nom)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select produit.* from produit where nomProduit LIKE '%".$nom."%'");
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	return $req->fetch();
}

// retourne le stock du produit (int)
function retourneStock($idProduit){
	$produit = retourneProduit($idProduit);
	return $produit->stockProduit;
}

function getIdProduit($nom)
{
	global $connexion;
	try
		{
			$req = $connexion->query("SET NAMES 'utf8'");
			$req = $connexion->query("Select idProduit from produit where nomProduit = '".addslashes($nom)."'");
			$req->setFetchMode(PDO::FETCH_OBJ);
			$res = $req->fetch();
			return $res->idProduit;
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function ajouterProduit($nom,$desc,$prix,$cat,$img,$stock)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->query("SET NAMES 'utf8'");
			$requete = $connexion->prepare('INSERT INTO `webuzzer54gs9`.`produit` values (DEFAULT,"'.mysql_real_escape_string($nom).'","'.mysql_real_escape_string($desc).'",'.mysql_real_escape_string($prix).','.mysql_real_escape_string($cat).',"'.$img.'","'.mysql_real_escape_string($stock).'");'); //on insère le produit dans la base
			$requete->execute();
			return true;
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function ajouterData($idProd,$idNom,$idVal)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->query("SET NAMES 'utf8'"); 
			$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`data` values (DEFAULT,".mysql_real_escape_string($idProd).",".mysql_real_escape_string($idNom).",".mysql_real_escape_string($idVal).")"); //on insère la data dans la base
			$requete->execute();
			return true;
			
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function supprimerProduit($id)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
		try
		{
			$requete = $connexion->prepare("Delete from data where idProduit = ".mysql_real_escape_string($id)); //on supprime les datas correspondantes au produit dans la base
			$requete->execute();
			
			$sql = $connexion->prepare("Delete from produit where idProduit = ".mysql_real_escape_string($id)); //on supprime le produit de la base
			$sql->execute();
			return true;
			
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function modifierData($id,$idNom,$idVal)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update data set idValeur = ".mysql_real_escape_string($idVal)." where idProduit = ".mysql_real_escape_string($id)." and idNom = ".mysql_real_escape_string($idNom));
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}
// fonction de modification d'un produit (avec le lien de l'image)
function modifierProduit($id,$nom,$desc,$prix,$cat,$img,$stock)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare('update produit set nomProduit = "'.mysql_real_escape_string($nom).'",descriptionProduit = "'.mysql_real_escape_string($desc).'",prixProduit = '.mysql_real_escape_string($prix).',idCategorie = '.mysql_real_escape_string($cat).',image = "'.mysql_real_escape_string($img).'",stockProduit = "'.mysql_real_escape_string($stock).'" where idProduit = '.mysql_real_escape_string($id));
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}
// fonction de modification d'un produit (sans le lien de l'image)
function modifierProduit2($id,$nom,$desc,$prix,$cat,$stock)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update produit set nomProduit = '".mysql_real_escape_string($nom)."',descriptionProduit = '".mysql_real_escape_string($desc)."',prixProduit = ".mysql_real_escape_string($prix).",idCategorie = ".mysql_real_escape_string($cat).",stockProduit = '".mysql_real_escape_string($stock)."' where idProduit = ".mysql_real_escape_string($id)."");
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}

function supprimerData($idNom, $id)
{
	global $connexion;
	
	try
		{
			$query = $connexion->prepare("delete from data where idProduit=".mysql_real_escape_string($id)." and idNom=".mysql_real_escape_string($idNom));
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}

function genererCategorieProduit(){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	if(!isset($_POST['ok']))
	{
		$requete = $connexion->query("SET NAMES 'utf8'");
		$requete = $connexion->query("SELECT * FROM categorie");
		$requete->setFetchMode(PDO::FETCH_OBJ);
		
		echo "<form action='produit.php' method='POST'>
			<div class='col-lg-4 input-group'> 
				<select name='cat' class='form-control'>";
				while($enregistrement = $requete->fetch())
				{
					if($_GET['idCat'] == $enregistrement->idCategorie)
					{
						echo "<option selected value='".$enregistrement->idCategorie."'>".$enregistrement->libelleCategorie."</option>";
					}else
					{
						echo "<option value='".$enregistrement->idCategorie."'>".$enregistrement->libelleCategorie."</option>";
					}
					
				}
				echo "</select>
				 <span class='input-group-btn'>
					<input type='submit' name='ok' value='Rechercher' class='btn btn-default'></input>
				</span>
			    </div><!-- /input-group -->
			</form>
			<br/><br/>";
	}
	else
	{
		header("Location:produit.php?idCat=".$_POST['cat']);
	}
}

function deduireStock($id,$qte)
{
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->query("SELECT * FROM produit where idProduit=".$id);
			$requete->setFetchMode(PDO::FETCH_OBJ);
			$res = $requete->fetch();
			
			$stock = ($res->stockProduit) - $qte;

			$query = $connexion->prepare("update produit set stockProduit = ".$stock." where idProduit = ".$id);
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}

function augmenterStock($id,$qte)
{
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->query("SELECT * FROM produit where idProduit=".$id);
			$requete->setFetchMode(PDO::FETCH_OBJ);
			$res = $requete->fetch();
			
			$stock = ($res->stockProduit) + $qte;

			$query = $connexion->prepare("update produit set stockProduit = ".$stock." where idProduit = ".$id);
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}
?>	