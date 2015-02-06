<?php
require_once("inc/inc_top.php");
require_once('inc/inc_head.php');
require_once("../fonctions/fonctionCategorie.php");

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
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
			if(isset($message)){
			echo $message;
		}
		
		echo '<h3>Catégories</h3>';
		
		if(isset($_GET['modif']))
		{
			if(!isset($_POST['ok']))
			{
				echo "<form action='categorie.php?modif&id=".$_GET['id']."' method='POST'>
					<input type='hidden' name='id' value='".$_GET['id']."'></input>
					<label>Nom de la catégorie : </label><input type='text' name='nom' value='".retourneLibelle($_GET['id'])."'></input><br/>
					<input type='submit' name='ok' value='Modifier'></input>
					</form>";
			}
			else
			{
				modifierCategorie($_POST['id'],$_POST['nom']);
				header("Location:categorie.php");
			}
		}
		elseif(isset($_GET['supp']))
		{
			echo '
					<form name="frm" action="categorie.php?supp&id='.$_GET['id'].'" method="post">
								<h3>Etes-vous sûre de vouloir supprimer cette catégorie ?</h3>
								<br/>
								<input type="hidden" name="no" value="'.$_GET['id'].'">
								<input type="radio" name="rep" value="non" checked> Non
								<input type="radio" name="rep" value="oui" > Oui
								<br/><br/>
								<input type="submit" value="Valider">
					</form>
						 ';
					
					$rep = "non"; 
					if(isset($_POST['rep'])){$rep = $_POST['rep'];}
					if(isset($_POST['no'])){$num = $_POST['no'];} 

					 
					 if($rep == "oui")
						{
							supprimerCategorie($_GET['id']);
							header("Location:categorie.php");
						}
						
						if($rep == "non")
						{
							header("Location:categorie.php");
						}
		}
		else
		{
			if(!isset($_POST['ok']))
			{
				echo "<form action='categorie.php' method='POST'>
					<label>Nom de la catégorie : </label><input type='text' name='nom'></input><br/>
					<input type='submit' name='ok' value='Ajouter'></input>
					</form>
				<div class='panel panel-default'>
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
								<td><a href="?modif&id='.$element->idCategorie.'">X</a></td>
								<td><a href="?supp&id='.$element->idCategorie.'">X</a></td>
							</tr>';
					}
				echo '</table></div>';
			}
			else
			{
				ajouterCategorie($_POST['nom']);
				header("Location:categorie.php");
			}
			
			
		}
		
		?>
	</body>
</html>
