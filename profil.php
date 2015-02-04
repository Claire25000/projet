<?php
require_once("inc/inc_top.php");
if(!estConnecte() || estAdmin()){ // si non connecté, on login en tant qu'admin
	header('Location: 404.php?err=1');
}
if(isset($_POST['nomClient'])){
	if(changerNomClient(idUtilisateurConnecte(),$_POST['nomClient'])){
		$message = "nom modifié";
	}
}
if(isset($_POST['prenomClient'])){
	if(changerPrenomClient(idUtilisateurConnecte(),$_POST['prenomClient'])){
		$message = "prenom modifie";
	}
}
if(isset($_POST['mail'])){
	if(changerEmailUtilisateur(idUtilisateurConnecte(),$_POST['mail'])){
		$message = "email modifié";
	}
}
if(isset($_POST['adresse'])){
	if(changerAdresseClient(idUtilisateurConnecte(),$_POST['adresse'])){
		$message = "adresse modifié";
	}
}
if(isset($_POST['ancienMdp']) && isset($_POST['nouveauMdp1']) && isset($_POST['nouveauMdp2'])){
	if($_POST['nouveauMdp1'] == $_POST['nouveauMdp2']){
		if(changerPasswordUtilisateur(idUtilisateurConnecte(),$_POST['ancienMdp'],$_POST['nouveauMdp1'])){
			$message = "mot de passe modifié";
		}else{
			$message = "L'ancien mot de passe saisi est incorect.";
		}
	}else{
		$message = "Les mot de passe saisi ne sont pas identiques";
	}
}
?>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	
	<body>
		<?php
			require_once('inc/inc_menu.php');
		?>
		<p> Gestion du profil client </p>

		<?php
			if(isset($message)){
				echo $message;
			}
			$client = retourneClient(idUtilisateurConnecte());
			$utilisateur = retourneUtilisateur(idUtilisateurConnecte());
		?>
		
		<form method="post" action="profil.php">
			<label for="nomClient">Nom </label>
			<input value="<?php echo $client->nomCli; ?>" type="text" name="nomClient" id="nomClient" />
			<input type="submit" value="Modifier" />
		</form>
		
		<form method="post" action="profil.php">
			<label for="prenomClient">Prénom </label>
			<input value="<?php echo $client->prenomCli; ?>" type="text" name="prenomClient" id="prenomClient" />
			<input type="submit" value="Modifier" />
		</form>
		
		<form method="post" action="profil.php">
			<label for="adresse">Adresse complète </label>
			<input value="<?php echo $client->adrCli; ?>" type="text" name="adresse" id="adresse" />
			<input type="submit" value="Modifier" />
		</form>
		
		<form method="post" action="profil.php">
			<label for="mail">Mail </label>
			<input value="<?php echo $utilisateur->email; ?>" type="text" name="mail" id="mail" />
			<input type="submit" value="Modifier" />
		</form>
		
		<form method="post" action="profil.php">
			<label for="ancienMdp">Ancien mot de passe </label>
			<input type="password" name="ancienMdp" id="ancienMdp" /><br/>
			
			<label for="nouveauMdp1">Nouveau mot de passe </label>
			<input type="password" name="nouveauMdp1" id="nouveauMdp1" /><br/>
			
			<label for="nouveauMdp2">Nouveau mot de passe (répéter)</label>
			<input type="password" name="nouveauMdp2" id="nouveauMdp2" /><br/>
			
			<input type="submit" value="Modifier" />
		</form>
		
		
	</body>
</html>
