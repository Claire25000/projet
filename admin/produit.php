<?php
require_once('inc/inc_head.php');
require_once('inc/inc_top.php');

require_once("../fonctions/fonctionComm.php");
require_once("../fonctions/fonctionProd.php");
require_once("../fonctions/fonctionImage.php");

if(isset($_GET['ok']))
{
	header("Location:produit.php?modif&idCat=".$_GET['idCat']."&id=".$_GET['id']);
}


if(isset($_POST['supp']))
{
	if(isset($_POST['rep'])){$rep = $_POST['rep'];}
	if(isset($_POST['no'])){$num = $_POST['no'];}
	if(isset($_POST['nom'])){$nom = $_POST['nom'];} 	
	if(isset($_POST['cat'])){$cat = $_POST['cat'];} 	
 
 if($rep == "oui")
	{
		supprimerData($nom,$num);
	}
	header("Location:produit.php?modif&idCat=".$cat."&id=".$num);
}

if(isset($_POST['suppP']))
	{
	if(isset($_POST['rep'])){$rep = $_POST['rep'];}
	if(isset($_POST['no'])){$num = $_POST['no'];} 
	if(isset($_POST['cat'])){$cat = $_POST['cat'];} 	

 
 if($rep == "oui")
	{
		supprimerProduit($num);
	}
	header("Location:produit.php?idCat=".$cat);
}

if(isset($_POST['oka'])){ // ---------------------AJOUT D'UN PRODUIT

		$up = new Telechargement("./upload",'oka','photo');
		$extensions = array('jpg','jpeg','png'); /* paramétrage extensions autorisées */
		$up->Set_Extensions_accepte ($extensions);
		$up->Set_Redim ('250','200');/* redimensionnement (si nécessaire) en maximum 100x100 */
		$up->Set_Message_court(': téléchargement effectué');/* message simplifié en retour pour le visiteur (par exemple) */
		$up->Set_Renomme_fichier(); // suffixe unique
		$up->Upload();/* Upload du fichier */ 
		$tab_result = $up->Get_Tab_upload(); /* Récupération du tableau des résultats d'upload */

		// ------------- on ajoute le produit dans la base de données ------------- //
		$prix = $_POST['prix'];
		//$prix  = number_format($_POST['prix'], 2, '.', ',');
		$nomPhoto = $tab_result['resultat']['0']["./upload"]['nom'];
		ajouterProduit($_POST['nom'],$_POST['desc'],$prix,$_POST['cat'],$nomPhoto,$_POST['stock']); // image : tableau[premiere ligne][dossier desination][nom réel uploadé]
		$id = getIdProduit($_POST['nom']);
		
	header("Location:produit.php?modif&id=".$id);
}

if(isset($_POST['okm'])) //-------------------- MODIFICATION D'UN PRODUIT
{
	$up = new Telechargement("./upload",'okm','photo');
	$extensions = array('jpg','jpeg','png'); /* paramétrage extensions autorisées */
	$up->Set_Extensions_accepte ($extensions);
	$up->Set_Redim ('250','200');/* redimensionnement (si nécessaire) en maximum 100x100 */
	$up->Set_Message_court(': téléchargement effectué');/* message simplifié en retour pour le visiteur (par exemple) */
	$up->Set_Renomme_fichier(); // suffixe unique
	$up->Upload();/* Upload du fichier */ 
	$tab_result = $up->Get_Tab_upload(); /* Récupération du tableau des résultats d'upload */
	$nomPhoto = '';
	$nomPhoto = $tab_result['resultat']['0']["./upload"]['nom'];
			
	if($nomPhoto != ''){
		modifierProduit($_POST['no'],$_POST['nom'],$_POST['desc'],$_POST['prix'],$_POST['cat'],$nomPhoto,$_POST['stock']);
	}else{
		modifierProduit2($_POST['no'],$_POST['nom'],$_POST['desc'],$_POST['prix'],$_POST['cat'],$_POST['stock']);
	}
			
			header("Location:produit.php?modif&id=".$_GET['id']."&idCat=".$_GET['idCat']);
			echo '-> '.$nomPhoto;
}

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Gestion des produits</title>
	<script src="ckeditor/ckeditor.js"></script>
  </head>
  <body>
   <div class="container">
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
		
		echo ' <div class="jumbotron">';
	
	echo'<body>';

		genererCategorieProduit();
		if(isset($_GET['ajout'])) // --------------------------------------------------- AJOUT PRODUIT ---------------------------------- //
		{
			?>
			<form enctype = "multipart/form-data" action='produit.php?ajout&idCat=<?php echo $_GET['idCat']; ?>' method='POST'>
					<label>Nom du produit : </label><input type='text' name='nom'></input><br/>
					<label>Description du produit : </label><br/><textarea name='desc' rows='10' class="ckeditor" cols='50'></textarea><br/>
					<label>Prix du produit : </label><input type='text' name='prix'></input><br/>
					<label>Stock du produit : </label><input type='text' name='stock'></input><br/>
					<label>Catégorie du produit : </label>
						<select name='cat'>";
						<?php			
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
						echo "</select><br/>

					
					<label for='photo'>Image (Extensions autorisées ".implode(', ',$extensions).")</label><input name='photo' id='image' type='file' />
					<br/>";
				echo "
						<input type='submit' name='oka' value='Ajouter le produit'></input>
			</form>";
		}
		elseif(isset($_GET['supp'])) // on supprime le produit
		{
			echo '
				<form name="frm" action="produit.php?supp&id='.$_GET['id'].'&idCat='.$_GET['idCat'].'" method="post">
							<h3>Etes-vous sûre de vouloir supprimer ce produit ?</h3>
							<br/>
							<input type="hidden" name="no" value="'.$_GET['id'].'">
							<input type="hidden" name="cat" value="'.$_GET['idCat'].'">
							<input type="radio" name="rep" value="non" checked> Non
							<input type="radio" name="rep" value="oui" > Oui
							<br/><br/>
							<input type="submit" name="suppP" value="Valider">
				</form>';
				
				$rep = "non"; 
		}
		elseif(isset($_GET['modif'])) // ------------------------------------------------------ MODIFICATION PRODUIT -------------------------//
		{				
				$id = $_GET['id'];
				$sql = $connexion->query("SET NAMES 'utf8'");
				$sql = $connexion->query("select * FROM produit where idProduit=".$id);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$res = $sql->fetch();
				
				echo '<form enctype="multipart/form-data" action="produit.php?modif&id='.$id.'&idCat='.$_GET['idCat'].'" method="POST">
						<input type="hidden"  name="no" value="'.$id.'" ></input>
						
						<label>Nom : </label><input type="text" name="nom" value="'.$res->nomProduit.'"></input><br/>
						<label>Description du produit : </label><br/><textarea name="desc" class="ckeditor" rows="10" cols="50">'.$res->descriptionProduit.'</textarea><br/>
						<label>Prix : </label><input type="text" name="prix" value="'.$res->prixProduit.'"></input><br/>
						<label>Stock du produit : </label><input type="text" name="stock" value="'.$res->stockProduit.'"></input><br/>
						<label>Catégorie : </label>
							<select name="cat">';
								$req = $connexion->query("SET NAMES 'utf8'");
								$req = $connexion->query("select * FROM categorie");
								$req->setFetchMode(PDO::FETCH_OBJ);
								
								while($resultat = $req->fetch()){
									if($res->idCategorie == $resultat->idCategorie)
									{
										echo "<option selected value='".$resultat->idCategorie."'>".$resultat->libelleCategorie."</option>";
									}
									else
									{
										echo '<option value="'.$resultat->idCategorie.'">'.$resultat->libelleCategorie.'</option>';
									}
								}
								echo "
							</select><br/>
						<label for='photo'>Image (Extensions autorisées ".implode(', ',$extensions).")</label><div><img src='../upload/".$res->image."' alt='[Aucune image]'/></div><input name='photo' id='image' type='file' />
						<br/><br/>
						<input type='submit' name='okm' value='Modifier'></input>
					 </form>";
					
					// ------------------------------------------------------ MODIFICATION CARACTERISTIQUES ----------------------------------//
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
								<td><center><a href='produit.php?modif&idCat=".$_GET['idCat']."&dm&id=".$id."&idNom=".$resultat->idNom."'>X</a></center></td>
								<td><center><a href='produit.php?modif&idCat=".$_GET['idCat']."&ds&id=".$id."&idNom=".$resultat->idNom."'>X</a></center></td>
							 </tr>";
					}
					echo "</table><br/>";
					if(isset($_GET['dm']))
					{
						echo "<form action='produit.php?modif&idCat=".$_GET['idCat']."&dm&id=".$_GET['id']."&idNom=".$_GET['idNom']."&ok' method='POST'><label>Valeur : </label><select name='carValeur'>
							<option value=null> </option>";
								
						$val = $connexion->query("Select * from data_valeur");
						$val->setFetchMode(PDO::FETCH_OBJ);
						
						$valeur = genererValeurNom($_GET['idNom']);
						
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
						<input type='submit' name='okmo' value='Modifier'></input>
						</form>";
						
						if(isset($_POST['okmo']))
						{
							if($_POST['carValeur'] == 'null')
							{				
								$e = ifValeurExist($_POST['val']);
								if($e == 0)
								{
									ajouterValeur($_POST['val']);
								}
								
								$idValeur = getIdValeur($_POST['val']);
							}
							else
							{
								$idValeur = $_POST['carValeur'];
							}
							modifierData($_GET['id'],$_GET['idNom'],$idValeur);
						}
					}	
					elseif(isset($_GET['ds']))
					{
						$idNom = $_GET['idNom'];
						echo '<form name="frm" action="produit.php?modif&ds&id='.$id.'&idNom='.$idNom.'&idCat='.$_GET['id'].'" method="post">
						<h3>Etes-vous sûre de vouloir supprimer cette caractéristique ?</h3>
						<br/>
						<input type="hidden" name="no" value="'.$id.'">
						<input type="hidden" name="nom" value="'.$idNom.'">
						<input type="hidden" name="cat" value="'.$_GET['id'].'">
						<input type="radio" name="rep" value="non" checked> Non
						<input type="radio" name="rep" value="oui" > Oui
						<br/><br/>
						<input type="submit" name="supp" value="Valider">
						</form>
							 ';
						
						$rep = "non"; 
					}
				elseif(!isset($_POST['okc']))
				{
					echo "<label>Ajouter de nouvelles caractéristiques : </label>";
					echo "<br/><form action='produit.php?modif&idCat=".$_GET['idCat']."&id=".$_GET['id']."' method='POST'><label>Nom : </label><select name='carNom'>
						<option value=null> </option>";
							
					$nom = $connexion->query("Select * from data_nom");
					$nom->setFetchMode(PDO::FETCH_OBJ);
				
					$noms = genererNomCategorie($_GET['idCat']);
										
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
					}
					else
					{
						$idNom = $_POST['carNom'];
					}
					
					echo "<br/><form action='produit.php?modif&idCat=".$_GET['idCat']."&id=".$_GET['id']."&ok' method='POST'><label>Valeur : </label>
					<input type='hidden' name='nom' value='".$idNom."'></input><select name='carVal'>
						<option value=null> </option>";
							
					$val = $connexion->query("Select * from data_valeur");
					$val->setFetchMode(PDO::FETCH_OBJ);
				
					
					$valeur = genererValeurNom($idNom);
					
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
				}
				if(isset($_POST['okc2']))
				{
					if($_POST['carVal'] == 'null')
						{				
							$e = ifValeurExist($_POST['val']);
							if($e == 0)
							{
								ajouterValeur($_POST['val']);
							}
							
							$idValeur = getIdValeur($_POST['val']);
						}
						else
						{
							$idValeur = $_POST['carVal'];
						}
						ajouterData($_GET['id'],$_POST['nom'],$idValeur);
				}
		}
		elseif(isset($_GET['id']))
		{
			$req = $connexion->query("SET NAMES 'utf8'");	
			$req = $connexion->query("Select produit.*, categorie.libelleCategorie from produit, categorie where produit.idCategorie = categorie.idCategorie and idProduit=".$_GET['id']);
			$req->setFetchMode(PDO::FETCH_OBJ);
			$res = $req->fetch();
			 //on récupère le produit voulu
			 
			 echo"
			 <br/>";
			 
			if(estConnecte() == true)
			{
				if(estAdmin(idUtilisateurConnecte()) == true)
				{
					echo "<a href='produit.php?modif&id=".$_GET['id']."&idCat=".$_GET['idCat']."'>Modifier le produit</a>";
				}
			}
			
			$sql = $connexion->query("SET NAMES 'utf8'"); 
			$sql = $connexion->query("Select nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$_GET['id']);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			echo "<div class='panel panel-default'>
					<div class='panel-heading'>Produit ".$res->nomProduit."</div>
			<table class='table' border='1'>
				<tr>
					<th>Numéro</th>
					<th>Nom</th>
					<th>Description</th>
					<th>Prix</th>
					<th>Stock</th>
					<th>Catégorie</th></tr>";
				
				echo "<tr>
					<td>".$_GET['id']."</td>
					<td>".$res->nomProduit."</td>
					<td>".$res->descriptionProduit."</td>
					<td>".$res->prixProduit."</td>
					<td>".$res->stockProduit."</td>
					<td>".$res->libelleCategorie."</td></tr>
					</table><br/>";
					
					$sql = $connexion->query("SET NAMES 'utf8'"); 
					$sql = $connexion->query("Select data.idNom, nom, valeur from data, data_nom, data_valeur where data.idNom = data_nom.idNom and data.idValeur = data_valeur.idValeur and idProduit = ".$_GET['id']);
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
		elseif(isset($_GET['idCat']))
		{
			echo '<a href="produit.php?ajout&idCat='.$_GET['idCat'].'">Ajouter un produit</a><br/>';
			$req = $connexion->query("SET NAMES 'utf8'");	
			$req = $connexion->query("Select * from produit where idCategorie=".$_GET['idCat']."");
			$req->setFetchMode(PDO::FETCH_OBJ);
			 //on récupère les produits voulus
			 
			echo"
			<div class='panel panel-default'>
					<div class='panel-heading'>Produits</div>
			<table class='table' border='1'>
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
					<td><a href='produit.php?id=".$res->idProduit."&idCat=".$_GET['idCat']."'>".$res->nomProduit."</a></td>
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
						<td><a href='produit.php?modif&id=".$res->idProduit."&idCat=".$_GET['idCat']."'>X</a></td>
						<td><a href='produit.php?supp&id=".$res->idProduit."&idCat=".$_GET['idCat']."'>X</a></td>";
					}
				echo "</tr>";
					echo "</table></div>";
		}
		else
		{
			echo '<a href="produit.php?ajout&idCat=null">Ajouter un produit</a><br/>';
		}
	?>
	</div>
	</div>
	</body>
</html>