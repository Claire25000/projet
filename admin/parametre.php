<?php
require_once("inc/inc_top.php");
require_once('inc/inc_head.php');

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
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
			require_once('inc/inc_menu.php');
			if(isset($message))
			{
				echo $message;
			}
		?>
		Gestion des parametres
		<form class="form-horizontal">
			<fieldset>

			<!-- Form Name -->
			<legend>Form Name</legend>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="param_">Text Input</label>  
			  <div class="col-md-6">
			  <input id="param_" name="param_" placeholder="placeholder" class="form-control input-md" required="" type="text"/>
			  </div>
			</div>

			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="singlebutton"> </label>
			  <div class="col-md-4">
				<button id="singlebutton" name="singlebutton" class="btn btn-primary">Button</button>
			  </div>
			</div>

			</fieldset>
		</form>

		
	</body>
</html>