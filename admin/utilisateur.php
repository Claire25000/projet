<?php
require_once("inc/inc_top.php");
require_once('inc/inc_head.php');
require_once("../fonctions/fonctionsClient.php");
require_once("../fonctions/fonctionsType.php");


if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['type']) ){
	if(ajouterUtilisateur($_POST['login'],$_POST['password'],$_POST['email'],$_POST['type'])){
		$message = "L'utilisateur ".$_POST['login']." a été ajouté avec succès.";
	}
}
if(isset($_GET['suprUtil'])){ // si une supression d'utilisateur est demandée
	if(!isset($_GET['conf'])){ // si l'action n'est pas confirmée
		$message = 'Confirmer la supression de l\'utilisateur : <a href="'.$_SERVER['REQUEST_URI'].'&conf"> Oui </a> <a href="utilisateur.php"> Non </a>';
	}else{
	echo 'ok';
		if(supprimerUtilisateur($_GET['suprUtil'])){
		$message = 'Supression effectuée avec succès';
	}else{
		$message = 'Echec de la supression';
	}
	}

}
?>
<html>
	<head>
		<?php require_once('inc/inc_head.php');?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title></title>
	</head>
	<body>
	<?php
	//echo estConnecte();
	//echo estWebmaster();
	//echo "<pre>".print_r($_SESSION)."</pre>";
	if(estConnecte() && estWebmaster()){
		
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
	
		echo '
		
		
		<h3>Statistiques</h3>
		<p>
			Nombre de client : '.nombreClient().'<br/>
			Nombre d\'utilisateur : '.nombreUtilisateur().'<br/>
		</p>
		<h3>Ajouter un utilisateur</h3>
		<form method="post" action="utilisateur.php">
			<p>
				<label for="login">Nom d\'utilisateur</label>
				<input type="text" name="login" id="login" /><br/>
				   
				<label for="password">Mot de passe</label>
				<input type="password" name="password" id="password" /><br/>
				
				<label for="email">Email</label>
				<input type="text" name="email" id="email" /><br/>
				
				<label for="type">Type</label>
				<select name="type"> ';
				foreach(retourneListeType() as $element) // retourne un array des types
				{
					if($element->libelleType != "Client"){ // Tous sauf les clients
						echo '<option value="'.$element->idType.'">'.$element->libelleType.'</option>';
					}
				}
				echo '</select><br/>';
				echo '<input type="submit" value="Ajouter un utilisateur" />
			</p>
		</form> 
		<br/>
		<h3>Liste des administrateurs</h3>';


		echo '<div class="panel panel-default">
		  <div class="panel-heading">Administrateurs</div>
		  <table class="table" border="1">';
		echo '<tr>
				<th>Pseudo</th>
				<th>Rang</th>
				<th>Supprimer</th>
			</tr>';
		foreach(retourneListeAdministrateurs() as $element) // retourne un array d'utilisateurs
			{
				echo '<tr>
						<td>'.$element->login .'</td>
						<td>'.retourneLibelleType($element->type).'</td>
						<td><a href="?suprUtil='.$element->idUtilisateur.'"> [x]</a></td>
					</tr>';
			}
		echo '</table></div>';
	}else{
	echo '<p>
			Vous devez être connecté en tant que Webmaster
		 </p>';
	}
	?>
	</body>
</html>