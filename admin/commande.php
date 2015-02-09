<?php
require_once('inc/inc_head.php');
require_once("inc/inc_top.php");

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}
require_once("../fonctions/fonctionsCommande.php");
require_once("../fonctions/fonctionsClient.php");
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Gestion des commandes</title>
  </head>
  <body>
   <div class="container">
		<?php
		require_once('inc/inc_menu.php');
		if(isset($message)){
			echo $message;
		}
		echo ' <div class="jumbotron">';
		?><p> Liste des commandes </p>

		<?php
		if(isset($_GET['id']))
		{
			echo '<div class="panel panel-default">
		  <div class="panel-heading">Commande n°'.$_GET['id'].'</div>
		  <table class="table" border="1">
					<tr>
						<th>Identifiant Commande</th>
						<th>Date</th>
						<th>Statut</th>
						<th>Mode de livraison</th>
						<th>Mode de paiement</th>
						<th>Nom Client</th>
					</tr>';
				
				$com = retourneCommande($_GET['id']);
						echo '<tr>
								<td>Commande n°'.$com->idCommande.'</td>
								<td>'.$com->date.'</td>
								<td>'.retourneStatut($com->statut).'</td>
								<td>'.retourneLivraison($com->modeLivraison).'</td>
								<td>'.retournePaiement($com->modePaiement).'</td>
								<td>'.retourneClient($com->idClient)->nomCli.'</td>								
							</tr>';				
				echo '</table></div><br/>
				<a href="commande.php?modif&statut&id='.$com->idCommande.'">Modifier le statut de la commande</a><br/>
				<a href="commande.php?modif&paiement&id='.$com->idCommande.'">Modifier le mode de paiement de la commande</a><br/>
				<a href="commande.php?modif&livraison&id='.$com->idCommande.'">Modifier le mode de livraison de la commande</a><br/><br/>
				<a href="commande.php?supp&id='.$com->idCommande.'">Supprimer la commande</a>';
				
			if(isset($_GET['supp']))
			{
				echo '
					<form name="frm" action="commande.php?supp&id='.$_GET['id'].'" method="post">
								<h3>Etes-vous sûre de vouloir supprimer cette commande ?</h3>
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
							supprimerCommande($num);
							header("Location:commande.php");
						}
			}
			elseif(isset($_GET['modif']))
			{
				if(isset($_GET['statut']))
				{
					if(!isset($_POST['ok']))
					{
						echo "<form action='commande.php?modif&statut&id=".$_GET['id']."' method='POST'>
							<label>Statut : </label><select name='statut'>
						<option value=null> </option>";
								
						$statut = $connexion->query("Select * from statut");
						$statut->setFetchMode(PDO::FETCH_OBJ);

						while($res = $statut->fetch())
						{
							echo "<option value='".$res->idStatut."'>".$res->libelleStatut."</option>";
						}
						echo "
						</select>
						<input type='submit' name='ok' value='Modifier'></input>";
					}
					else
					{
						changerStatutCommande($_GET['id'],$_POST['statut']);
						header("Location:commande.php?id=".$_GET['id']);
					}
				}
				elseif(isset($_GET['paiement']))
				{
					if(!isset($_POST['ok']))
					{
						echo "<form action='commande.php?modif&paiement&id=".$_GET['id']."' method='POST'>
							<label>Mode de paiement : </label><select name='paiement'>
						<option value=null> </option>";
								
						$paiement = $connexion->query("Select * from modePaiement");
						$paiement->setFetchMode(PDO::FETCH_OBJ);

						while($res = $paiement->fetch())
						{
							echo "<option value='".$res->idModePaiement."'>".$res->libelleModePaiement."</option>";
						}
						echo "
						</select>
						<input type='submit' name='ok' value='Modifier'></input>";
					}
					else
					{
						changerModePaiementCommande($_GET['id'],$_POST['paiement']);
						header("Location:commande.php?id=".$_GET['id']);
					}
				}
				elseif(isset($_GET['livraison']))
				{
					if(!isset($_POST['ok']))
					{
						echo "<form action='commande.php?modif&livraison&id=".$_GET['id']."' method='POST'>
							<label>Mode de livraison : </label><select name='livraison'>
						<option value=null> </option>";
								
						$livraison = $connexion->query("Select * from modeLivraison");
						$livraison->setFetchMode(PDO::FETCH_OBJ);

						while($res = $livraison->fetch())
						{
							echo "<option value='".$res->idModeLivraison."'>".$res->libelleModeLivraison."</option>";
						}
						echo "
						</select>
						<input type='submit' name='ok' value='Modifier'></input>";
					}
					else
					{
						changerModeLivraisonCommande($_GET['id'],$_POST['livraison']);
						header("Location:commande.php?id=".$_GET['id']);
					}
				}
			}
		}
		else
		{?>
		<div class="panel panel-default">
		  <div class="panel-heading">Commandes</div>
			<table class="table" border="1">
					<tr>
						<th>Identifiant Commande</th>
						<th>Date</th>
						<th>Statut</th>
						<th>Mode de livraison</th>
						<th>Mode de paiement</th>
						<th>Nom Client</th>
					</tr>
				<?php
				foreach(listeCommande() as $element) // retourne un array de commande
					{
						echo '<tr>
								<td><a href="commande.php?modif&id='.$element->idCommande.'">Commande n°'.$element->idCommande.'</a></td>
								<td>'.$element->date.'</td>
								<td>'.retourneStatut($element->statut).'</td>
								<td>'.retourneLivraison($element->modeLivraison).'</td>
								<td>'.retournePaiement($element->modePaiement).'</td>
								<td>'.retourneClient($element->idClient)->nomCli.'</td>								
							</tr>';
					}
				?>
			</table></div>
		<?php } ?>
	</div>
	</div>
	</body>
</html>
