<?php
require_once('inc/inc_top.php');

require_once("../fonctions/fonctionComm.php");
require_once("../fonctions/fonctionProd.php");
require_once("../fonctions/fonctionImage.php");

//------------------------------------------------------------------------------------------------------------------------------ TRAITEMENT IMAGES 
$adresse_racine = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/';

$adresse_dossier_test = substr(dirname(__FILE__),strlen($adresse_racine));

// pour compatibilité window
$adresse_dossier_test = str_replace('\\','/',$adresse_dossier_test);

//echo $adresse_dossier_test;

$dossier_photo = $adresse_dossier_test.'/PHOTO';
$dossier_photo_GF = $adresse_dossier_test.'/PHOTO_GF';
$dossier_photo_PF = $adresse_dossier_test.'/PHOTO_PF';
$dossier_pdf = $adresse_dossier_test.'/PDF';

$up = new Telechargement($dossier_photo,'form1','photo');

/* paramétrage extensions autorisées */
$extensions = array('jpg','jpeg','png');
$up->Set_Extensions_accepte ($extensions);

// redimensionnements
$up->Set_Redim ('200','150', array('_min'));

$up->Set_Redim ('1000','800',array('_max'));

// Renommage incrémentiel en cas de doublons (le contrôle se fera sur nomdufichier_min.jpg)
$up->Set_Renomme_fichier('incr');


$up->Upload('reload');


$messages_upload = $up->Get_Tab_message();
$messages_upload_html = null;
foreach ($messages_upload as $num) foreach ($num as $value) $messages_upload_html .= '<p>- '.htmlspecialchars($value).'</p>';

$tableau_resultat = $up->Get_Tab_result();
//------------------------------------------------------------------------------------------------------------------------------ fin TRAITEMENT IMAGES 
if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['oka'])){ // si form envoyé on ajoute le produit
	$prix = $_POST['prix'];
	//$prix  = number_format($_POST['prix'], 2, '.', ',');
	ajouterProduit($_POST['nom'],$_POST['desc'],$prix,$_POST['cat'],$_POST['img']);
	$id = getIdProduit($_POST['nom']);

	header("Location:produit.php?modif&id=".$id);
}

if(isset($_GET['supp'])) // on supprime le produit
		{
			formSupprimerProduit($_GET['id'],$_GET['idCat']);
		}
?>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript">
		<!--
		function Verif_attente(id_attente)
		{              
			var id_attente = document.getElementById(id_attente);
			
			if (id_attente)
			{
				id_attente.innerHTML = 'Patientez...';  
			
				id_attente.style.fontWeight="bold";
				id_attente.style.fontSize="1.5em";         
			}
		}
		-->
		</script>
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
			<form enctype='multipart/form-data' action='produit.php?ajout&idCat=<?php echo $_GET['idCat']; ?>' method='POST'>
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
						<label>Image du produit : </label><input type='text' name='img'></input><br/>	
						<!--<input name = 'photo[]' type = 'file' multiple = 'multiple' size = '70' /><br /></br>-->
						<input type='submit' name='oka' value='Ajouter'></input>
						</form>";
						?>
			<!--<form enctype = "multipart/form-data" action = "#" method = "post" onsubmit = "Verif_attente('message_tele')">		-->
			<!--<input name = "photo[]" type = "file" multiple = "multiple" size = "70" /><br />-->	 
			<!--<input  type="submit" value="Envoyer les images" id="envoyer" name = "form1"><br />
			</form>-->
		 
			 <div id = "message_tele" style="margin-top:20px;">
			<?= $messages_upload_html; ?>
			</div>
		   
			
			<div style="margin-top:50px">
			<?php if(!empty($tableau_resultat))
			{
				echo 'tableau des résultats :';
				echo '<pre>';
				print_r($tableau_resultat);
				echo '</pre>';
			}
			?>
			</div>
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
