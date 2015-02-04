<?php
function afficherCommentaire($idUtil,$idProd)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	if(!isset($_POST['ok']))
	{
		$req = $connexion->query("Select idProduit, nomProduit from produit where idProduit = ".$idProd);
		$req->setFetchMode(PDO::FETCH_OBJ);
		$res = $req->fetch(); //on récupère le produit voulu
	
		echo "<form action='produit.php' method='POST'> 
		<label>Veuillez saisir votre commentaire sur le produit ".$res->nomProduit." : </label><br/>
				<textarea name='comm' rows='10' cols='50'>Saisir un commentaire ici.</textarea><br/><br/>
				<input type='submit' name='ok' value='Envoyer'></input>
		</form>";
	}
	else
	{
		ajouterCommentaire($idUtil,$idProd,$_POST['comm']);
	}		
}

function ajouterCommentaire($idUtil,$idProd,$comm)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->prepare("INSERT INTO `commentaire` values (".$idProd.",".$idUtil.",'".$_POST['comm']."',NOW())"); //on insère le commentaire dans la base
			//$requete->debugDumpParams();
			$requete->execute();
			echo'Insertion effectuée avec succès';
			
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
		}
}


?>