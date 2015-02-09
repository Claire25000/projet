<?php
require_once('inc/inc_head.php');
require_once('inc/inc_top.php');

require_once("../fonctions/fonctionComm.php");
require_once("../fonctions/fonctionProd.php");
require_once("../fonctions/fonctionImage.php");

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
		modifierProduit2($_POST['no'],$_POST['nom'],$_POST['desc'],$_POST['prix'],$_POST['cat']);
	}
			
			header("Location:produit.php?modif&id=".$_GET['id']."&idCat=".$_GET['idCat']);
			echo '-> '.$nomPhoto;
			
	//}else{ // si la photo n'a pas été modifié
	//	modifierProduit($_POST['no'],$_POST['nom'],$_POST['desc'],$_POST['prix'],$_POST['cat'],"");
	//	header("Location:produit.php?modif&id=".$_GET['id']);
	//}
}

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}
if(isset($_GET['supp'])) // on supprime le produit
		{
			formSupprimerProduit($_GET['id'],$_GET['idCat']);
		}
		
/*if(isset($_POST['oka'])){ // si form envoyé on ajoute le produit
	$prix = $_POST['prix'];
	//$prix  = number_format($_POST['prix'], 2, '.', ',');
	ajouterProduit($_POST['nom'],$_POST['desc'],$prix,$_POST['cat'],$_POST['img']);
	$id = getIdProduit($_POST['nom']);

	header("Location:produit.php?modif&id=".$id);
}*/


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
		echo 'page de présentation des produits <br/>';
		if(isset($_GET['ajout'])) // --------------------------------------------------- AJOUT PRODUIT ---------------------------------- //
		{
			//formAjouterProduit($_GET['idCat']);
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
				echo '	<label>Produit en stock : </label><div class="onoffswitch">
							<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
							<label class="onoffswitch-label" for="myonoffswitch">
								<span class="onoffswitch-inner"></span>
								<span class="onoffswitch-switch"></span>
							</label>
						</div><br/>';
					echo "<input type='submit' name='oka' value='Ajouter le produit'></input>
			</form>";
		}
		elseif(isset($_GET['modif'])) // ------------------------------------------------------ MODIFICATION PRODUIT -------------------------//
		{
			//formModifierProduit($_GET['id']);
			if(!isset($_POST['okm']))
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
					
					//afficherModifCaracteristique($id);
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
								<td><center><a href='produit.php?modif&dm&id=".$id."&idName=".$resultat->idNom."'>X</a></center></td>
								<td><center><a href='produit.php?modif&ds&id=".$id."&idNom=".$resultat->idNom."'>X</a></center></td>
							 </tr>";
					}
					echo "</table><br/>
					<label>Ajouter de nouvelles caractéristiques : </label>";
					if(isset($_GET['dm']))
					{
						echo "<form action='produit.php?modif&dm&id=".$_GET['id']."&idName=".$_GET['idName']."' method='POST'><label>Valeur : </label><select name='carValeur'>
							<option value=null> </option>";
								
						$val = $connexion->query("Select * from data_valeur");
						$val->setFetchMode(PDO::FETCH_OBJ);
						
						$valeur = genererValeurNom($_GET['idName']);
						
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
								header("Location:produit.php?modif&id=".$_GET['id']."&idCat=".$_GET['idCat']);
							}
							
							if(isset($_POST['okm']))
							{
								modifierData($_GET['id'],$_GET['idName'],$_POST['carVal']);
								//header("Location:produit.php?modif&dm&id=".$_GET['id']."&idName=".$_GET['idName']);
							}
						}
					}	
					elseif(isset($_GET['ds']))
					{
						$idNom = $_GET['idNom'];
						echo '<form name="frm" action="produit.php?ds&id='.$id.'&idNom='.$idNom.'" method="post">
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
					elseif(isset($_GET['idNom']))
					{
						formAjouterCaracteristiquesValeur($_GET['idNom']); //!!!!!!!!!!!!!!!!!!!!!!!!! A FAIRE
					}
			}
			else
			{
				$sql = $connexion->query("SET NAMES 'utf8'"); 
				$sql = $connexion->query("Select idCategorie from produit where idProduit = ".$_GET['id']);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$res = $sql->fetch();
				
				formAjouterCaracteristiquesNom($res->idCategorie); //!!!!!!!!!!!!!!!!!!!!!!!!! A FAIRE
			}
		}
		elseif(isset($_GET['id']))
		{
			afficherProduitDetails($_GET['id']); //!!!!!!!!!!!!!!!!!!!!!!!!! A FAIRE??
		}
		elseif(isset($_GET['idCat']))
		{
			echo 'Catégorie : '.$_GET['idCat'].' <br/>
			<a href="produit.php?ajout&idCat='.$_GET['idCat'].'">Ajouter un produit</a><br/>';
			afficherProduitAdmin($_GET['idCat']); //!!!!!!!!!!!!!!!!!!!!!!!!! A FAIRE??
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
