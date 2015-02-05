<?php
require_once('inc/inc_top.php');

require_once("../fonctions/fonctionComm.php");
require_once("../fonctions/fonctionProd.php");
require_once("../fonctions/fonctionImage.php");

//------------------------------------------------------------------------------------------------------------------------------ TRAITEMENT IMAGES 
$up = new Telechargement("./upload",'oka','photo');

/* paramétrage extensions autorisées */
$extensions = array('jpg','jpeg','png'); 
$up->Set_Extensions_accepte ($extensions);

/* redimensionnement (si nécessaire) en maximum 100x100 */
$up->Set_Redim ('250','250');

/* message simplifié en retour pour le visiteur (par exemple) */
$up->Set_Message_court(': téléchargement effectué');

// suffixe unique
$up->Set_Renomme_fichier(); 
//------------------------------------------------------------------------------------------------------------------------------ fin TRAITEMENT IMAGES 
if(isset($_POST['oka'])){ // AJOUT D'UN PRODUIT
		$up->Upload();/* Upload du fichier */ 
		$tab_result = $up->Get_Tab_upload(); /* Récupération du tableau des résultats d'upload */

		// ------------- on ajoute le produit dans la base de données ------------- //
		$prix = $_POST['prix'];
		//$prix  = number_format($_POST['prix'], 2, '.', ',');
		ajouterProduit($_POST['nom'],$_POST['desc'],$prix,$_POST['cat'],$tab_result['resultat']['0']["./upload"]['nom']); // image : tableau[premiere ligne][dossier desination][nom réel uploadé]
		$id = getIdProduit($_POST['nom']);
		
	header("Location:produit.php?modif&id=".$id);
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
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	
	<body>
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
		genererCategorieProduit();
		echo 'page de présentation des produits <br/>';
		if(isset($_GET['ajout'])) // On ajoute le produit
		{
			//formAjouterProduit($_GET['idCat']);
			?>
			<form enctype = "multipart/form-data" action='produit.php?ajout&idCat=<?php echo $_GET['idCat']; ?>' method='POST'>
					<label>Nom du produit : </label><input type='text' name='nom'></input><br/>
					<label>Description du produit : </label><br/><textarea name='desc' rows='10' cols='50'></textarea><br/>
					<label>Prix du produit : </label><input type='text' name='prix'></input><br/>
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

						Extensions autorisées ".implode(', ',$extensions)."
						<label for='photo'>Image </label><input name='photo' id='image' type='file' />
						<br/><br/>
						<input type='submit' name='oka' value='Ajouter le produit'></input>
						</form>";
						?>
			<?php

		}
		elseif(isset($_GET['modif']))
		{
			formModifierProduit($_GET['id']);
			if(isset($_GET['idNom']))
			{
			
				formAjouterCaracteristiquesValeur($_GET['idNom']);
			}else
			{
				$sql = $connexion->query("SET NAMES 'utf8'"); 
				$sql = $connexion->query("Select idCategorie from produit where idProduit = ".$_GET['id']);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$res = $sql->fetch();
				
				formAjouterCaracteristiquesNom($res->idCategorie);
			}
		}
		elseif(isset($_GET['id']))
		{
			afficherProduitDetails($_GET['id']);
		}
		elseif(isset($_GET['idCat']))
		{
			echo 'Catégorie : '.$_GET['idCat'].' <br/>
			<a href="produit.php?ajout&idCat='.$_GET['idCat'].'">Ajouter un produit</a><br/>';
			afficherProduitAdmin($_GET['idCat']);
		}
		else
		{
			echo '<a href="produit.php?ajout&idCat=null">Ajouter un produit</a><br/>';
		}
	?>
	</body>
</html>
