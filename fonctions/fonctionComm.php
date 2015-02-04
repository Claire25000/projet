<?php
require_once("fonctionsSysteme.php");

function afficherCommentaire($idProd)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	if(!isset($_POST['btnSubmit']))
	{
		$req = $connexion->query("Select idProduit, nomProduit from produit where idProduit = ".$idProd);
		$req->setFetchMode(PDO::FETCH_OBJ);
		$res = $req->fetch(); //on récupère le produit voulu
	
		echo '
		Veuillez saisir votre commentaire sur le produit '.$res->nomProduit.'
		<div class="container">
			<form action="produit.php?id='.$idProd.'" method="POST">
				<div>        
					<br style="clear:both">
						<div class="form-group col-md-4 ">                                
							<label id="messageLabel" for="message">Message </label>
							<textarea class="form-control input-sm " type="textarea" id="message" name="message" placeholder="Message" maxlength="250" rows="7"></textarea>
								<span class="help-block"><p id="characterLeft" class="help-block ">You have reached the limit</p></span>                    
						</div>
					<br style="clear:both">
					<div class="form-group col-md-2">
					<button class="form-control input-sm btn btn-success disabled" id="btnSubmit" name="btnSubmit" type="submit" style="height:35px"> Send</button>    
				</div>
			</form>
		</div>';
	}
	else
	{
		ajouterCommentaire($idProd,$_POST['message']);
	}		
}

function ajouterCommentaire($idProd,$comm)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->prepare("INSERT INTO `webuzzer54gs9`.`commentaire` values (".$idProd.",".idUtilisateurConnecte().",'".$comm."',NOW())"); //on insère le commentaire dans la base
			$requete->execute();
			echo'Commentaire inseré avec succès';
			
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
		}
}

function retourneListeCommentaire($idProd)
{
	global $connexion;
	
	$liste = array();
	$req = $connexion->query("SET NAMES 'utf8'");
	$req = $connexion->query("Select commentaire.* from commentaire where idProduit = ".$idProd);
	$req->setFetchMode(PDO::FETCH_OBJ);
	
	while($res = $req->fetch())
	{
		$liste[] = $res;
	}
	
	return $liste;
}

function supprimerCommentaire($idProd)
{
	global $connexion; // on définie la variable globale de connection dans la fonction
	
	try
		{
			$requete = $connexion->prepare("delete from commentaire where idUtilisateur = ".idUtilisateurConnecte()." and idProduit = ".$idProd);
			$requete->execute();	
		}
		catch(Exception $e)
		{
				die('Erreur : '.$e->getMessage());
		}
}
?>