<?php 
//Si on travaille en utf-8 (recommandé)
header('Content-type: text/html; charset=UTF-8');

// Appel de la classe
require('../Classe_Upload.php');
 
require('../adresses_dossiers.php');

/* Doit être un fichier valide dans $dossier_photo_PF si vous voulez qu'il s'affiche pour le test */
$avatar_par_defaut = 'avatar_par_defaut.jpg';

/* Initialisation de la classe en dehors de toute condition $_POST (et $_FILES) */
$up = new Telechargement($dossier_photo,'form1','photo');

/* paramétrage extensions autorisées */
$extensions = array('jpg','jpeg'); 
$up->Set_Extensions_accepte ($extensions);

/* redimensionnement en maximum 200x200 (si nécessaire) */
$up->Set_Redim (200,200);

/* On défini le séparateur de dimension. Permet d'avoir un code générique pour trouver les dimensions des images dans le tableau des résultats */
$separateur_dimensions = 'x';
$up->Set_Separateur_dimImg($separateur_dimensions);

/* Utilisée sans argument cette fonction permet de n'afficher que les messages d'erreurs d'upload */
$up->Set_Message_court();

/* !******! Si on souhaite que l'upload d'un fichier soit obligatoire il suffira de décommenter la ligne ci-dessous (dans notre contexte) */
/*$up->Set_Message_champVide('Champ de téléchargement vide. Un fichier est requis !'); */

/* Note * : il faudrait compléter la configuration de la classe avec Set_Renomme_fichier() ou Set_Controle_fichier() si vous souhaitez éviter l'écrasement de fichiers déjà existants sur le serveur. */

/* Petite fonction pratique dans notre contexte pour exploiter le tableau des résultats de l'upload */
function Result_upload($upload_result,$dossier_destination, $index, $num = 0)
{
	return isset($upload_result['resultat'][$num][$dossier_destination][$index])? $upload_result['resultat'][$num][$dossier_destination][$index] : null;
}

/* Condition $_POST d'envoi du formulaire */
if (isset($_POST['form1']))
{
	$_SESSION['form1_inscr'] = null;
	/* Enregistrement des résultats en session pour la persistance des données après reload de la page */
	$tab_ses =& $_SESSION['form1_inscr'];
	
	$erreur_texte = array();

	$pseudo = $tab_ses['pseudo'] = trim($_POST['pseudo']);
	if ($pseudo == '') $erreur_texte[] = 'le pseudo doit être renseigné';/* il faudrait compléter le contrôle...*/
			
	$mail = $tab_ses['mail'] = trim($_POST['mail']);
	if ($mail == '') $erreur_texte[] = 'le mail doit être renseigné';/* il faudrait compléter le contrôle avec filter_validate_email pour tester le mail */
		
	$tab_ses['erreur_texte'] = $erreur_texte;

	if(count($erreur_texte) == 0)
	{
		$up->Upload();
		/* Tableau de résultat de l'upload (enregistré en session pour afficher la vignette d'upload) */
		$upload_result = $tab_ses['upload_result'] = $up->Get_Tab_upload();
			
		/* Tableau des messages d'information de l'upload = messages d'erreur puisque Set_Message_court est utilisé sans paramètre */
		$erreur_upload = $tab_ses['erreur_upload'] = $up->Get_Tab_message();
		
		if(count($erreur_upload) == 0)/* si pas d'erreur d'upload */
		{
			/* nom final du fichier d'upload après éventuelle correction automatique (à enregistrer en bdd). */
			$nom_final_fichier = Result_upload($upload_result,$dossier_destination,'nom');
			/* Si pas de fichier on met l'avatar */
			$nom_final_fichier = isset($nom_final_fichier) ? $nom_final_fichier : $avatar_par_defaut; 
			
			/* ici l'insertion de $pseudo, $mail et $nom_final_fichier en bdd ...  */
			/*... */
			
			/* Confirmation d'enregistrement */
			$tab_ses['succes'] = true; 
		}
	} 
	$up->Get_Reload_page();
}

/* On récupère les variables de session */
$tab_ses = isset($_SESSION['form1_inscr'])? $_SESSION['form1_inscr'] : null;
unset($_SESSION['form1_inscr']);

/* Si erreur "post_max_size" du serveur lors d'un téléchargement, on ne passe pas dans la condition post car $_POST est vide. On récupère donc cette erreur ici avec  $up->Get_Tab_message(); */
$erreur_post_max_size = $up->Get_Tab_message();
$erreur_upload = isset($tab_ses['erreur_upload']) ? $tab_ses['erreur_upload'] : $erreur_post_max_size;

/* Récupération des messages d'erreur des champs textes */
$erreur_texte = isset($tab_ses['erreur_texte'])? $tab_ses['erreur_texte']:array();

/* Récupération de la confirmation de l'enregistrement du formulaire */
$succes = isset($tab_ses['succes'])? $tab_ses['succes'] : false;

/* Pour la persistance des données du formulaire si erreur dans les champs ou erreur d'upload (si $succes = false) */
$pseudo = isset($tab_ses['pseudo']) && !$succes ? htmlspecialchars($tab_ses['pseudo']) : null;
$mail = isset($tab_ses['mail']) && !$succes ? htmlspecialchars($tab_ses['mail']) : null;


/* ECRITURE DES MESSGES D'INFORMATION en php pour avoir un HTML propre */
$message_titre = !empty($erreur_upload)? 'Un problème est survenu lors du téléchargement du fichier' : null;
$message_titre = !empty($erreur_texte)? 'Erreur dans les champs du formulaire' : $message_titre;
/* Titre de message affiché */
$message_titre = $succes ? 'Formulaire enregistré !' : $message_titre;

$message = null;
if (isset($message_titre)) $message .= '<p class="message_titre">'.htmlspecialchars($message_titre).'</p>'."\n";

foreach ($erreur_texte as $value) $message .= '<p>- '.htmlspecialchars($value).'</p>'."\n";

foreach ($erreur_upload as $num) foreach ($num as $value) $message .= '<p>- '.htmlspecialchars($value).'</p>'."\n";

$upload_result = isset($tab_ses['upload_result']) ? $tab_ses['upload_result'] : array();
/* nom du fichier original (ou par défaut) pour écriture du message */
$avatar = Result_upload($upload_result,$dossier_photo,'nom_ini');
$avatar = isset($avatar) ? $avatar : $avatar_par_defaut;

if($succes) $message .= '<p>Pseudo "'.htmlspecialchars($tab_ses['pseudo']).'", mail "'.htmlspecialchars($tab_ses['mail']).'", avatar "'.htmlspecialchars($avatar).'"</p>'."\n";

/* Code pour afficher la photo téléchargée ou l'avatar par défaut */
$vignette_html = null;

/* Pour permettre l'affichage aussi bien en local que sur un serveur distant */
$http = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' :  'http://';			
$adresse_serveur = $http.$_SERVER['SERVER_NAME'].'/';

$nom_final_fichier = Result_upload($upload_result,$dossier_photo,'nom');
$dimensions = Result_upload($upload_result,$dossier_photo,'dim');

if (isset($nom_final_fichier,$dimensions))
{	
	$largeur_max = 200; /* largeur maximum de la vignette affichée (sert à rien ici puisque j'ai choisi les même dimensions que le redimensionnemt mais c'est pour l'exemple)*/
	$hauteur_max = 200; /* hauteur maximum de la vignette affichée */

	$img_size = explode($separateur_dimensions,$dimensions);
	/* on utilise la fonction "Dim_Prop_max" pour limiter la taille d'affichage de la vignette */
	$dim_vignette = $up->Dim_Prop_max($img_size[0],$img_size[1],$largeur_max,$hauteur_max);

	$adresse_html = $adresse_serveur.$dossier_photo.'/'.$nom_final_fichier;

	$vignette_html .= '<img alt="'.htmlspecialchars($nom_final_fichier).'" src="'.$adresse_html.'" width="'.$dim_vignette[0].'" height="'.$dim_vignette[1].'" />';
}
/* Pour afficher l'avatar par défaut (sans redimensionnement) */
else if($succes && empty($erreur_upload) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.$dossier_photo_PF.'/'.$avatar_par_defaut))
{ 
	$adresse_avatar = $adresse_serveur.$dossier_photo_PF.'/'.$avatar_par_defaut;	
	$vignette_html .= '<img alt="'.$avatar_par_defaut.'" src="'.$adresse_avatar.'" />';
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Formulaire avec upload de fichier, affichage des résultats/erreurs et vignette d'upload</title>
<style type="text/css">
body {font-family:Arial, Helvetica, sans-serif; font-size:12px;}
#form1 form{width:370px; left:20px; border:1px solid black; padding:0 20px 20px 20px; background-color:#F4F4F4;}
#form1 p {margin:0; padding:20px 0 0 0;}
#form1 p label{display:inline-block;width:60px;text-align:right;}
#form1 p input{margin-left:15px;}
#form1 #photo{width:270px;}
#form1 #pseudo{width:100px;}
#form1 #mail{width:170px;}
#form1 #extensions{margin-left:75px}
#form1 #file{padding-top:10px}
#form1 #envoyer_form1{text-align:right;}
.message_titre {font-size:15px}
</style>
</head>
<body> 
<!-- FORMULAIRE --> 
<div id="form1">
<form enctype = "multipart/form-data" action = "#" method = "post">	  
<p><label for="pseudo">pseudo *</label><input name="pseudo" id="pseudo" type="text" value="<?= $pseudo?>" /></p>
<p><label for="mail">mail *</label><input name="mail" id="mail" type="text" value="<?= $mail?>" /></p>
<p id="extensions">Extensions autorisées <?= implode(', ',$extensions)?></p>
<p id="file"><label for="photo">avatar</label><input name="photo" id="photo" type="file" /></p>
<p id="envoyer_form1"><input type="submit" name="form1" value="Envoyez" /></p>
</form>
</div>
<!-- AFFICHE LES MESSAGES DE RETOUR DE FORMULAIRE -->
<div>
<?= $message ?>
</div>
<!-- AFFICHE LES VIGNETTES DES IMAGES TELECHARGEES-->
<div>
<?= $vignette_html ?>
</div>
<div style="margin-top:50px">
Ici nous conditionnons l'absence d'erreur d'upload à l'enregistrement des données du formulaire. Une solution pratique est d'utiliser la fonction «Set_Message_court» sans paramètre car elle permet dans ce cas de retourner uniquement les messages d'erreur avec Get_Tab_message(). Il suffit donc de compter ce tableau pour déclencher ou non l'action d'enregistrement.<br><br>

- On utilisera une deuxième fois Get_Tab_message() en dehors de la condition post pour pouvoir gérer l'éventuelle erreur de dépassement «post_max_size».<br>
<br>

- Et on se servira du tableau des résultats pour afficher un message et une vignette de la photo dans le html en cas d'upload réussi, sinon l'avatar par défaut.<br>
<br>


J'en profite pour ajouter un second champ texte pour donner un exemple plus générique et gérer la persistance des champs textes dans le formulaire si un problème survient lors de l'upload ou si un champ texte n'est pas correctement renseigné.<br>
<br>

Notes : <br>
Si l'on souhaite ensuite que l'upload d'une image valide soit indispensable pour l'enregistrement des données, on pourrait bien sûr modifier les conditions d'enregistrement en testant la variable $nom_final_fichier et en ajoutant un message d'erreur correspondant, mais le plus rapide sera d'ajouter la fonction de configuration  Set_Message_champVide() :<br>


// cf note !******! dans le code ci-dessus
$up->Set_Message_champVide('Champ de téléchargement vide. Un fichier est requis !');<br><br>


En effet si le champ d'upload est vide, cette fonction ajoutera une ligne d'erreur dans Get_Tab_message(). Et puisque dans notre contexte Get_Tab_message() ne retourne que les messages d'erreur d'upload (du fait de la fonction Set_Message_court()  appelée sans paramètre) et que l'enregistrement est conditionné à l'absence de messages d'erreur d'upload, le script fonctionnera comme attendu sans aucune autre modification ;)<br><br>


Note * : il faudrait compléter la configuration de la classe avec Set_Renomme_fichier() ou Set_Controle_fichier() si vous souhaitez éviter l'écrasement de fichiers déjà existants sur le serveur.
</div>
</body>
</html>