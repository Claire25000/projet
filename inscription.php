<?php
require_once('inc/inc_top.php');

if(isset($_POST['login']) && isset($_POST['pass1']) && isset($_POST['pass2']) && isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['adresse'])){
	if($_POST['pass1'] == $_POST['pass2']){
		if(ajouterClient($_POST['login'],$_POST['pass1'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['email'])){
			$message = "Inscription effectuée avec succès";
		}
	}
	
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Inscription</title>
	</head>
	<body>
		<?php
			require_once('inc/inc_menu.php');
		
			if(isset($message)){
				echo $message;
			}		
		?>
		<form method="post" action="inscription.php">
			<p>
				<label for="login">Nom d'utilisateur</label>
				<input type="text" name="login" id="login" /><br/>
				
				<label for="pass1">Mot de passe</label>
				<input type="password" name="pass1" id="pass1" /><br/>
				
				<label for="pass2">Répéter le mot de passe</label>
				<input type="password" name="pass2" id="pass2" /><br/>
				
				<label for="prenom">Prénom</label>
				<input type="text" name="prenom" id="prenom" /><br/>
				
				<label for="nom">Nom</label>
				<input type="text" name="nom" id="nom" /><br/>
				
				<label for="email">Email</label>
				<input type="text" name="email" id="email" /><br/>
				   
				<label for="adresse">Adresse complète</label>
				<input type="text" name="adresse" id="adresse" /><br/><br/>
				
				<input type="submit" value="S'inscrire" />
			</p>
		</form>
	</body>
</html
