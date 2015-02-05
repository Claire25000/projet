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

function afficherProduit($cat)
{
	global $connexion; // on définie la variable globale de connection dans la fonction

		$req = $connexion->query("SET NAMES 'utf8'");
		$req = $connexion->query("Select * from produit where idCategorie=".$cat."");
		$req->setFetchMode(PDO::FETCH_OBJ);
		 //on récupère les produits voulus
		
		echo"
		<table border='1'>
			<tr>
				<th>Numéro du produit</th>
				<th>Nom du produit</th>
			</tr>";
		while($res = $req->fetch())
		{					
			echo "<tr>
				<td>".$res->idProduit."</td>
				<td><a href='produit.php?id=".$res->idProduit."'>".$res->nomProduit."</a></td>
				</tr>";
		}
}

function afficherProduitAdmin($cat)
{
	global $connexion; // on définie la variable globale de connection dans la fonction

		$req = $connexion->query("SET NAMES 'utf8'");	
		$req = $connexion->query("Select * from produit where idCategorie=".$cat."");
		$req->setFetchMode(PDO::FETCH_OBJ);
		 //on récupère les produits voulus
		 
		echo"
		<table border='1'>
			<tr>
				<th>Numéro du produit</th>
				<th>Nom du produit</th>
				<th>Description du produit</th>
				<th>Prix du produit</th>
				<th>Caractéristiques supplémentaires</th>
				<th>Modifier</th>
				<th>Supprimer</th>
			</tr>";
			
		while($res = $req->fetch())
		{
			$sql = $connexion->query("SET NAMES 'utf8'"); 
			$sql = $connexion->query("Select nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$res->idProduit);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			echo "<tr>
				<td>".$res->idProduit."</td>
				<td><a href='produit.php?id=".$res->idProduit."'>".$res->nomProduit."</a></td>
				<td>".$res->descriptionProduit."</td>
				<td>".$res->prixProduit."</td>
				<td>
					<ul>";
					while($resultat = $sql->fetch())
					{
						echo "<li>".$resultat->nom." : ".$resultat->valeur."</li>";
					}
				echo "</ul>
				</td>
					<td><a href='produit.php?modif&id=".$res->idProduit."'>X</a></td>
					<td><a href='produit.php?supp&id=".$res->idProduit."&idCat=".$cat."'>X</a></td>";
				}
			echo "</tr>";
				echo "</table>";
}

function afficherProduitDetails($id)
{
		global $connexion; // on définie la variable globale de connection dans la fonction

		$req = $connexion->query("SET NAMES 'utf8'");	
		$req = $connexion->query("Select produit.*, categorie.libelleCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$id);
		$req->setFetchMode(PDO::FETCH_OBJ);
		$res = $req->fetch();
		 //on récupère le produit voulu
		 
		 echo"
		 <br/>";
		 
		if(estConnecte() == true)
		{
			if(estAdmin(idUtilisateurConnecte()) == true)
			{
				echo "<a href='produit.php?modif&id=".$id."'>Modifier le produit</a>";
			}
		}
		echo "<table border='1'>
			<tr>
				<th>Numéro du produit</th>
				<th>Nom du produit</th>
				<th>Description du produit</th>
				<th>Prix du produit</th>
				<th>Catégorie</th></tr>";
				
			$sql = $connexion->query("SET NAMES 'utf8'"); 
			$sql = $connexion->query("Select nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$id);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			echo "<tr>
				<td>".$id."</td>
				<td>".$res->nomProduit."</td>
				<td>".$res->descriptionProduit."</td>
				<td>".$res->prixProduit."</td>
				<td>".$res->libelleCategorie."</td></tr>
				</table><br/>";
				
				afficherCaracteristique($id);
}

/*function formAjouterProduit($idCat) --> inutile ?
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
		
			echo "<form enctype = 'multipart/form-data' action='produit.php?ajout&idCat=".$idCat."' method='POST'>
					<label>Nom du produit : </label><input type='text' name='nom'></input><br/>
					<label>Description du produit : </label><br/><textarea name='desc' rows='10' cols='50'></textarea><br/>
					<label>Prix du produit : </label><input type='text' name='prix'></input><br/>
					<label>Catégorie du produit : </label>
						<select name='cat'>";
						
						$sql = $connexion->query("SET NAMES 'utf8'"); 
						$sql = $connexion->query("Select idCategorie, libelleCategorie from categorie");
						$sql->setFetchMode(PDO::FETCH_OBJ);
					
						while($resultat = $sql->fetch())
						{
							if($_GET['idCat'] == $resultat->idCategorie)
							{
								echo "<option selected value='".$resultat->idCategorie."'>".$resultat->libelleCategorie."</option>";
							}else
							{
								echo "<option value='".$resultat->idCategorie."'>".$resultat->libelleCategorie."</option>";
							}
						}
						echo "</select><br/><label>Image du produit : </label><input type='text' name='img'></input><br/>
						
						
						<input name = 'photo[]' type = 'file' multiple = 'multiple' size = '70' /><br /></br>
						<input type='submit' name='oka' value='Ajouter'></input>
						</form>";
							
			
		
		if(isset($_POST['oka']))
		{
			$prix = $_POST['prix'];
			//$prix  = number_format($_POST['prix'], 2, '.', ',');
			ajouterProduit($_POST['nom'],$_POST['desc'],$prix,$_POST['cat'],$_POST['img']);
			$id = getIdProduit($_POST['nom']);

			header("Location:produit.php?modif&id=".$id);
		}
}*/

function getIdProduit($nom)
{
	global $connexion;
	
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select idProduit from produit where nomProduit = '".$nom."'");
	$req->setFetchMode(PDO::FETCH_OBJ);
	$res = $req->fetch();
	
	return $res->idProduit;
}

function formAjouterCaracteristiquesNom($cat)
{
	global $connexion;
	
			if(!isset($_POST['okc']))
			{
				echo "<br/><form action='produit.php?modif&id=".$_GET['id']."' method='POST'><label>Nom : </label><select name='carNom'>
					<option value=null> </option>";
						
				$nom = $connexion->query("Select * from data_nom");
				$nom->setFetchMode(PDO::FETCH_OBJ);
			
				
				$noms = genererNomCategorie($cat);
				
				
				if($noms == null)
				{
					while($res = $nom->fetch())
					{
						echo "<option value='".$res->idNom."'>".$res->nom."</option>";
					}
				}
				else
				{
					foreach($noms as $element) // retourne un array des noms
					{
						echo "<option value='".$element->idNom."'>".$element->nom."</option>";
					}
				}
				echo "
				</select> ou <input type='text' name='nom' style='width:100px; height:20px;'></input><br/>
				<input type='submit' name='okc' value='Ajouter'></input>			
				</form>";
			}else
			{
				if($_POST['carNom'] == 'null')
				{
					$e = ifNomExist($_POST['nom']);
					
					if($e == 0)
					{
						ajouterNom($_POST['nom']);
					}
					
					$idNom = getIdNom($_POST['nom']);
					header("Location:produit.php?modif&id=".$_GET['id']."&idNom=".$idNom);
				}
				else
				{
					header("Location:produit.php?modif&id=".$_GET['id']."&idNom=".$_POST['carNom']);
				}
			}
}

function formAjouterCaracteristiquesValeur($nom)
{
	global $connexion;
	
			if(!isset($_POST['okc2']))
			{
				echo "<br/><form action='produit.php?modif&id=".$_GET['id']."&idNom=".$nom."' method='POST'><label>Valeur : </label><select name='carVal'>
					<option value=null> </option>";
						
				$val = $connexion->query("Select * from data_valeur");
				$val->setFetchMode(PDO::FETCH_OBJ);
			
				
				$valeur = genererValeurNom($nom);
				
				if($valeur == null)
				{
					while($res = $val->fetch())
					{
						echo "<option value='".$res->idValeur."'>".$res->valeur."</option>";
					}
				}
				else
				{
					foreach($valeur as $element) // retourne un array des valeurs
					{
						echo "<option value='".$element->idValeur."'>".$element->valeur."</option>";
					}
				}
				echo "
				</select> ou <input type='text' name='val' style='width:100px; height:20px;'></input><br/>
				<input type='submit' name='okc2' value='Ajouter'></input>";
			}else
			{
				if($_POST['carVal'] == 'null')
				{				
					$e = ifValeurExist($_POST['val']);
					echo $e;
					if($e == 0)
					{
						echo 'ok';
						ajouterValeur($_POST['val']);
					}
					
					$idValeur = getIdValeur($_POST['val']);
					ajouterData($_GET['id'],$nom,$idValeur);
					header("Location:produit.php?id=".$_GET['id']);
				}
				else
				{
					ajouterData($_GET['id'],$nom,$_POST['carVal']);
					header("Location:produit.php?id=".$_GET['id']);
				}
			}
}

function ajouterProduit($nom,$desc,$prix,$cat,$img)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->query("SET NAMES 'utf8'");
			$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`produit` values (DEFAULT,'".$nom."','".$desc."',".$prix.",".$cat.",'".$img."');"); //on insère le produit dans la base
			$requete->execute();
			echo'Insertion effectuée avec succès';
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
			$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`data` values (DEFAULT,".$idProd.",".$idNom.",".$idVal.")"); //on insère la data dans la base
			$requete->execute();
			echo "<br/>Caractéristique ajoutée !";
			return true;
			
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

function formSupprimerProduit($id,$idCat)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	echo '
	<form name="frm" action="produit.php?supp&id='.$id.'&idCat='.$idCat.'" method="post">
				<h3>Etes-vous sûre de vouloir supprimer ce produit ?</h3>
				<br/>
				<input type="hidden" name="no" value="'.$id.'">
				<input type="hidden" name="cat" value="'.$idCat.'">
				<input type="radio" name="rep" value="non" checked> Non
				<input type="radio" name="rep" value="oui" > Oui
				<br/><br/>
				<input type="submit" value="Valider">
	</form>
		 ';
	
	$rep = "non"; 
	if(isset($_POST['rep'])){$rep = $_POST['rep'];}
	if(isset($_POST['no'])){$num = $_POST['no'];} 
	if(isset($_POST['cat'])){$cat = $_POST['cat'];} 	

	 
	 if($rep == "oui")
		{
			supprimerProduit($num);
			header("Location:produit.php?idCat=".$cat);
		}
}

function supprimerProduit($id)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
		try
		{
			$requete = $connexion->prepare("Delete from data where idProduit = ".$id); //on supprime les datas correspondantes au produit dans la base
			$requete->execute();
			
			$sql = $connexion->prepare("Delete from produit where idProduit = ".$id); //on supprime le produit de la base
			$sql->execute();
			return true;
			
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
				return false;
		}
}

/*function formModifierProduit($id)
{
	global $connexion;
	
	if(!isset($_POST['okm']))
	{
		$sql = $connexion->query("SET NAMES 'utf8'");
		$sql = $connexion->query("select * FROM produit where idProduit=".$id);
		$sql->setFetchMode(PDO::FETCH_OBJ);
		$res = $sql->fetch();
		
			echo '<form action="produit.php?modif&id='.$id.'" method="POST">
					<input type="hidden"  name="no" value="'.$id.'" ></input>
					<label>Nom : </label><input type="text" name="nom" value="'.$res->nomProduit.'"></input><br/>
					<label>Description du produit : </label><br/><textarea name="desc" rows="10" cols="50">'.$res->descriptionProduit.'</textarea><br/>
					<label>Prix : </label><input type="text" name="prix" value="'.$res->prixProduit.'"></input><br/>
					<label>Catégorie : </label><select name="cat">';
					$req = $connexion->query("SET NAMES 'utf8'");
					$req = $connexion->query("select * FROM categorie");
					$req->setFetchMode(PDO::FETCH_OBJ);
					
					while($resultat = $req->fetch()){
				 
						echo '<option value="'.$resultat->idCategorie.'">'.$resultat->libelleCategorie.'</option>';
					}
					echo "
					</select><br/>
					<label>Image du produit : </label><input type='text' name='img' value='".$res->image."'></input><br/>
					<input type='submit' name='okm' value='Modifier'></input>
			     </form>";	
			
			afficherModifCaracteristique($id);
	}		
	else
	{
		modifierProduit($_POST['no'],$_POST['nom'],$_POST['desc'],$_POST['prix'],$_POST['cat'],$_POST['img']);
		header("Location:produit.php?modif&id=".$id);
	}
}*/

/*function afficherModifCaracteristique($id)
{
	global $connexion;
	
			$sql = $connexion->query("SET NAMES 'utf8'"); 
			$sql = $connexion->query("Select data.idNom, nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$id);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			echo "<table>
				<tr>
				<th>Caractéristiques</th>
				<th>Modifier</th>
				<th>Supprimer</th>
			</tr>";
			
					while($resultat = $sql->fetch())
					{
						echo "<tr>
						<td>".$resultat->nom." : ".$resultat->valeur."</td>
						<td><center><a href='produit.php?modif&dm&id=".$id."&idNom=".$resultat->idNom."'>X</a></center></td>
						<td><center><a href='produit.php?modif&ds&id=".$id."&idNom=".$resultat->idNom."'>X</a></center></td>
						</tr>";
					}
				echo "</table><br/>
				<label>Ajouter de nouvelles caractéristiques : </label>";
			if(isset($_GET['dm']))
			{
				formModifierData($_GET['idNom'],$id);
			}	
			
			if(isset($_GET['ds']))
			{
				formSupprimerData($_GET['idNom'],$id);
			}
}*/

function afficherCaracteristique($id)
{
	global $connexion;
	
			$sql = $connexion->query("SET NAMES 'utf8'"); 
			$sql = $connexion->query("Select data.idNom, nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$id);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			echo "<label>Caractéristiques : </label>
			<ul>";
			
					while($resultat = $sql->fetch())
					{
						echo "<tr>
						<li>".$resultat->nom." : ".$resultat->valeur."</li>";
					}
				echo "</ul><br/>";
}

function formModifierData($idNom,$id)
{
	global $connexion;
	
			echo "<br/><form action='produit.php?modif&id=".$_GET['id']."' method='POST'><label>Valeur : </label><select name='carValeur'>
				<option value=null> </option>";
					
			$val = $connexion->query("Select * from data_valeur");
			$val->setFetchMode(PDO::FETCH_OBJ);
		
			
			$valeur = genererValeurNom($nom);
			
			if($valeur == null)
			{
				while($res = $val->fetch())
				{
					echo "<option value='".$res->idValeur."'>".$res->valeur."</option>";
				}
			}
			else
			{
				foreach($valeur as $element) // retourne un array des valeurs
				{
					echo "<option value='".$element->idValeur."'>".$element->valeur."</option>";
				}
			}
			echo "
			</select>&nbsp;<input type='text' name='val' style='width:100px; height:20px;'></input><br/>
			<input type='submit' name='okm' value='Ajouter'></input>
			</form>";
			
			if(isset($_POST['okm']))
			{
				if($_POST['carNom'] == null)
				{
					$e = ifValeurExist($_POST['val']);
					
					if($e == null)
					{
						ajouterValeur($_POST['val']);
					}
					
					$idValeur = getIdValeur($_POST['val']);
					ajouterData($_GET['id'],$nom,$idValeur);
					header("Location:produit.php?modif&id=".$_GET['id']);
				}
				
				if(isset($_POST['okm']))
				{
					modifierData($id,$idNom,$_POST['carVal']);
					header("Location:produit.php?modif&id=".$_GET['id']);
				}
			}
}

function modifierData($id,$idNom,$idVal)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update data set idValeur = ".$idVal." where idProduit = ".$id." and idNom = ".$idNom);
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
function modifierProduit($id,$nom,$desc,$prix,$cat,$img)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update produit set nomProduit = '".$nom."',descriptionProduit = '".$desc."',prixProduit = ".$prix.",idCategorie = ".$cat.",image = '".$img."' where idProduit = ".$id."");
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
function modifierProduit2($id,$nom,$desc,$prix,$cat)
{
	global $connexion;
	
	try
		{
			$query = $connexion->query("SET NAMES 'utf8'"); 
			$query = $connexion->prepare("update produit set nomProduit = '".$nom."',descriptionProduit = '".$desc."',prixProduit = ".$prix.",idCategorie = ".$cat." where idProduit = ".$id."");
			$query->execute();
			return true;
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
			return false;
		}
}
function formSupprimerData($idNom,$id)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	echo '
	<form name="frm" action="produit.php?ds&id='.$id.'&idNom='.$idNom.'" method="post">
				<h3>Etes-vous sûre de vouloir supprimer cette caractéristique ?</h3>
				<br/>
				<input type="hidden" name="no" value="'.$id.'">
				<input type="hidden" name="nom" value="'.$idNom.'">
				<input type="radio" name="rep" value="non" checked> Non
				<input type="radio" name="rep" value="oui" > Oui
				<br/><br/>
				<input type="submit" value="Valider">
	</form>
		 ';
	
	$rep = "non"; 
	if(isset($_POST['rep'])){$rep = $_POST['rep'];}
	if(isset($_POST['no'])){$num = $_POST['no'];}
	if(isset($_POST['nom'])){$nom = $_POST['nom'];} 	

	 
	 if($rep == "oui")
		{
			supprimerData($nom,$num);
			header("Location:produit.php?id=".$id);
		}
}

function supprimerData($idNom, $id)
{
	global $connexion;
	
	try
		{
			$query = $connexion->prepare("delete from data where idProduit=".$id." and idNom=".$idNom);
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
		<select name='cat'>";
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
			<input type='submit' name='ok' value='Rechercher'></input>
			</form>
			<br/>";

	}
	else
	{
		header("Location:produit.php?idCat=".$_POST['cat']);
	}
}
?>	