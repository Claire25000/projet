<?php
require_once("inc/inc_top.php");
require_once("../fonctions/fonctionCategorie.php");

if(!estWebmaster()){header('Location: ../404.php?err=1');} // on redirige les non-webmasteur

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}

if(isset($_POST['ok']))
{
	modifierCategorie($_POST['id'],$_POST['nom']);
	header("Location:categorie.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Gestion des catégories</title>
  </head>
  <body>
   <div class="container">
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
		echo ' <div class="jumbotron">';
		
		echo '<legend>Catégories</legend>
			<p></p>';

		if(isset($_GET['supp']))
		{
			echo '<form name="frm" action="categorie.php?supp&id='.$_GET['id'].'" method="post">
			<fieldset>
					<h3>Êtes-vous sûre de vouloir supprimer cette catégorie ?</h3>
					<input type="hidden" name="no" value="'.$_GET['id'].'">
					<div class="col-lg-1">
						<div class="input-group">
							<span class="input-group-addon">
								<input type="radio" name="rep" value="non" checked> Non
							</span>
							<span class="input-group-addon">
								<input type="radio" name="rep" value="oui" > Oui
							</span>
						</div>
					</div>
					<br/><br/>
					<div class="form-group">
					  <label class="col-md-0 control-label"> </label>
					  <div class="col-md-4">
					<input type="submit" name="supp" value="Valider" class="btn btn-primary" >
					</div></div>
					</fieldset>
				</form>';
						
			$rep = "non"; 
			
			if(isset($_POST['supp']))
			{
				if(isset($_POST['rep'])){$rep = $_POST['rep'];}
				if(isset($_POST['no'])){$num = $_POST['no'];} 
					 
				if($rep == "oui")
				{
					supprimerCategorie($_GET['id']);
				}
				header("Location:categorie.php");
			}
		}
		elseif(isset($_GET['modif']))
		{
			echo "<form action='categorie.php?modif&id=".$_GET['id']."' method='POST' class='form-horizontal'>
			<fieldset>
				<input type='hidden' name='id' value='".$_GET['id']."'></input>
				<div class='form-group'>
				  <label class='col-md-4 control-label'>Nom de la catégorie</label>  
				  <div class='col-md-4'>
				  <input type='text' name='nom' value='".retourneLibelle($_GET['id'])."' placeholder='placeholder' class='form-control input-md' required=''/>
				  </div>
				</div>
				<div class='form-group'>
				  <label class='col-md-4 control-label'> </label>
				  <div class='col-md-4'>
					<input type='submit' name='ok' class='btn btn-primary' value='Modifier'/>
				  </div>
				</div>
				</fieldset>
				</form>";	
		}
		else
		{
			echo "<form action='categorie.php' method='POST' class='form-horizontal'>
			<fieldset>
					<div class='form-group'>
				  <label class='col-md-4 control-label'>Nom de la catégorie</label>  
				  <div class='col-md-4'>
				 <input type='text' name='nom' class='form-control input-md' required=''/>
				  </div>
				</div>
				<div class='form-group'>
				  <label class='col-md-4 control-label'> </label>
				  <div class='col-md-4'>
					<input type='submit' name='ok' class='btn btn-primary' value='Ajouter'/>
				  </div>
				</div>
				</fieldset>
				</form>";
					
			if(isset($_POST['ok']))
			{
				ajouterCategorie($_POST['nom']);
				header("Location:categorie.php");
			}
		}
			echo  "<div class='panel panel-default'>
				<div class='panel-heading'>Catégories</div>
			  <table class='table' border='1'>
					<tr>
						<th>Identifiant Catégorie</th>
						<th>Libellé Catégorie</th>
						<th>Modifier</th>
						<th>Supprimer</th>
					</tr>";
				foreach(listeCategorie() as $element) // retourne un array de categorie
					{
						echo '<tr>
								<td>'.$element->idCategorie.'</td>
								<td><a href="produit.php?idCat='.$element->idCategorie.'">'.$element->libelleCategorie.'</a></td>
								<td><a href="?modif&id='.$element->idCategorie.'"><img src="design/pencil.png"/></a></td>
								<td><a href="?supp&id='.$element->idCategorie.'"><img src="design/delete.png"/></a></td>
							</tr>';
					}
				echo '</table></div>';
		?>
	</div>
	</div>
	</body>
</html>
