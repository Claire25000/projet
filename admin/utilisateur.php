<?php
require_once("inc/inc_top.php");
require_once("../fonctions/fonctionsClient.php");
require_once("../fonctions/fonctionsType.php");


if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['type']) ){
	if(ajouterUtilisateur($_POST['login'],$_POST['password'],$_POST['email'],$_POST['type'])){
		$message = '<div class="alert alert-success" role="alert">L\'utilisateur <b>'.$_POST["login"].'</b> a été ajouté avec succès.</div>';
	}else{
		$message = '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout de l\'utilisateur.</div>';
	}
}
if(isset($_GET['suprUtil'])){ // si une supression d'utilisateur est demandée
	if(!isset($_GET['conf'])){ // si l'action n'est pas confirmée
		$message = '<div class="alert alert-warning" role="alert"> Confirmer la supression de l\'utilisateur : <a href="'.$_SERVER['REQUEST_URI'].'&conf"> Oui </a> <a href="utilisateur.php"> Non </a> </div>';
	}else{
		if(supprimerUtilisateur($_GET['suprUtil'])){
		$message = '<div class="alert alert-success" role="alert">Supression effectuée avec succès</div>';
	}else{
		$message = '<div class="alert alert-danger" role="alert">Echec de la supression</div>';
	}
	}

if(isset($_POST['login'])){
echo $_POST['login'];
}
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Gestion des utilisateurs</title>
  </head>
  <body>
   <div class="container">
		<?php
	if(estConnecte() && estWebmaster()){
		
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
	
		echo '<h3>Statistiques</h3>
		<p>
			Nombre de client : '.nombreClient().'<br/>
			Nombre d\'utilisateur : '.nombreUtilisateur().'<br/>
		</p>';
		?>
		<form method="POST" action="utilisateur.php" class="form-horizontal">
			<fieldset>
			<!-- Form Name -->
			<legend>Ajouter un utilisateur</legend>
			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="email">Email</label>  
			  <div class="col-md-4">
			  <input id="email" name="email" placeholder="" class="form-control input-md" required="" type="text">
			  </div>
			</div>

			<!-- Password input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="password">Password</label>
			  <div class="col-md-4">
				<input id="password" name="password" placeholder="" class="form-control input-md" required="" type="password">
				
			  </div>
			</div>

			<!-- Select Basic -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="type">Type</label>
			  <div class="col-md-4">
				<select id="type" name="type" class="form-control">
				  <?php 
				 foreach(retourneListeType() as $element) // retourne un array des types
					{
						if($element->libelleType != "Client"){ // Tous sauf les clients
							echo '<option value="'.$element->idType.'">'.$element->libelleType.'</option>';
						}
					}
				?>
				</select>
			  </div>
			</div>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="login">Nom d'utilisateur</label>  
			  <div class="col-md-4">
			  <input id="login" name="login" placeholder="" class="form-control input-md" required="" type="text">
			  </div>
			</div>

			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="singlebutton"></label>
			  <div class="col-md-4">
				<button id="singlebutton" name="singlebutton" class="btn btn-primary" type="submit">Ajouter un utilisateur</button>
			  </div>
			</div>
			</fieldset>
			</form>
		<?php
		echo '
		<br/>
		<h3>Liste des administrateurs</h3>';


		echo '<div class="panel panel-default">
		  <div class="panel-heading">Administrateurs</div>
		  <table class="table" border="1">';
		echo '<tr>
				<th>Pseudo</th>
				<th>Email</th>
				<th>Rang</th>
				<th>Supprimer</th>
			</tr>';
		foreach(retourneListeAdministrateurs() as $element) // retourne un array d'utilisateurs
			{
				echo '<tr>
						<td>'.$element->login .'</td>
						<td>'.$element->email .'</td>
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
	</div>
	</div>
	</body>
</html>