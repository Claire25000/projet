<?php
require_once("inc/inc_top.php");

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['email']) && isset($_POST['password'])){
	if(connecteUtilisateur($_POST['email'],$_POST['password']))
		{
			if(estAdmin()){
				$message = "Connexion réussie<br/>";
			}else{
				header('Location: ../404.php?err=1'); // // utilisateur connecté mais pas admin : on redirige
			}
		}
		else{
			$message = "Echec de l'authentification.<br/>";
		}
}
?>
<html>
	<head>
		<?php require_once('inc/inc_head.php');?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">
		<link rel="stylesheet" type="text/css" href="design/style.css" />
		<title>Static Top Navbar Example for Bootstrap</title>

		<!-- Bootstrap core CSS -->
		<!--<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
		<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="../bootstrap/css/theme.css" rel="stylesheet">
	</head>
	<body>
	<div class="container">
		<?php
		if(estConnecte() && estWebmaster()){
			require_once('inc/inc_menu.php');
		}
		
		if(isset($message)){
			echo $message;
		}
		?>
		
		<?php if(!estConnecte()){ // si l'utilisateur n'est pas connecté on affiche le formulaire?>
		<div class="row" style="margin-top:20px">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<form method="post" role="form">
					<fieldset>
						<h2>Veuillez vous enregistrer</h2>
						<hr class="colorgraph">
						<div class="form-group">
							<input value="admin@admin.fr" type="email" name="email" id="email" class="form-control input-lg" placeholder="Addresse email">
						</div>
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Mot de passe">
						</div>

						<hr class="colorgraph">
						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6">
								<input type="submit" class="btn btn-lg btn-success btn-block" value="Se connecter">
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6">
								<a href="../" class="btn btn-lg btn-primary btn-block">
									<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Retour au site
								</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<?php }else{ // sinon, si il est connecté
			echo 'Vous êtes connecté';
		}
		?>
	</div>
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="bootstrap/js/theme.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>