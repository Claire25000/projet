<?php
require_once("inc/inc_top.php");

if(isset($_GET['deco'])){
	if(deconnecteUtilisateur()){ // si la fonction de déconnexion retourne true : utilisateur déconnecté
		header('Location: index.php');
	}
}
require_once("../fonctions/fonctionsCommande.php");
require_once("../fonctions/fonctionsClient.php");
require_once("../fonctions/fonctionProd.php");
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
		?><legend>Commandes</legend>
			<p></p>

		<?php
		if(isset($_GET['id']))
		{
			$total = 0;
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
				<div class="panel panel-default">
				<div class="panel-heading">Contenu de la commande</div>
				<table class="table" border="1">
					<tr>
						<th>Indentifiant du Produit</th>
						<th>Nom du Produit</th>
						<th>Quantité</th>
						<th>Prix</th>
					</tr>';
				foreach(retourneLigneCommande($com->idCommande) as $element) // retourne un array de commande
				{
					$req = $connexion->query("SET NAMES 'utf8'");
					$req = $connexion->query("Select * from produit where idProduit = ".$element->idProduit);
					$req->setFetchMode(PDO::FETCH_OBJ);
					
					$res = $req->fetch();
					
					$total += $element->nombre*$res->prixProduit;
					
					echo '<tr>
								<td>'.$element->idProduit.'</td>
								<td>'.$res->nomProduit.'</td>
								<td>'.$element->nombre.'</td>
								<td>'.number_format(($element->nombre*$res->prixProduit), 2, ',', ' ').' €</td>						
							</tr>';
				}
				echo '</table></div></br>';
				if($com->modeLivraison == 2)
				{
					$res = retourneFrais($com->modeLivraison);
					$total += $res->frais;
				}
				echo'
				<h3 style="text-align:right">Prix total : '.number_format($total, 2, ',', ' ').' €</h3>
				<a href="commande.php?modif&statut&id='.$com->idCommande.'">Modifier le statut de la commande</a><br/>
				<a href="commande.php?modif&paiement&id='.$com->idCommande.'">Modifier le mode de paiement de la commande</a><br/>
				<a href="commande.php?modif&livraison&id='.$com->idCommande.'">Modifier le mode de livraison de la commande</a><br/><br/>
				<a href="commande.php?supp&id='.$com->idCommande.'">Supprimer la commande</a></br>';
				
			if(isset($_GET['supp']))
			{
				echo '
					<form name="frm" action="commande.php?supp&id='.$_GET['id'].'" method="post">
				<fieldset>
						<h3>Êtes-vous sûre de vouloir supprimer cette commande ?</h3>
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
								$liste = retourneLigneCommande($num);
								foreach($liste as $element)
								{
									augmenterStock($element->idProduit,$element->nombre);
									supprimerLigneCommande($num,$element->idProduit);
								}
								supprimerCommande($num);
								header("Location:commande.php");
							}
							else
							{
								header("Location:commande.php?id=".$num);
							}
					}
			}
			elseif(isset($_GET['modif']))
			{
				if(isset($_GET['statut']))
				{
					if(!isset($_POST['ok']))
					{
						echo "</br><form action='commande.php?modif&statut&id=".$_GET['id']."' method='POST' class='form-horizontal'>
						 <fieldset>
						  <legend>Modification de statut</legend>
							<p></p>
							<div class='form-group'>
							<label class='col-md-1 control-label'>Statut :</label>  
							<div class='col-lg-3 input-group'> 
							<select name='statut' class='form-control'>
						<option value=null> </option>";
								
						$statut = $connexion->query("Select * from statut");
						$statut->setFetchMode(PDO::FETCH_OBJ);

						while($res = $statut->fetch())
						{
							echo "<option value='".$res->idStatut."'>".$res->libelleStatut."</option>";
						}
						echo "
						</select>
						</div></div>
						<div class='form-group'>
					  <label class='col-md-1 control-label'> </label>
					  <div class='col-md-4'>
						<input type='submit' name='ok' value='Modifier' class='btn btn-primary'/>
						</div></div>
						</fieldset></form>";
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
						echo "</br><form action='commande.php?modif&paiement&id=".$_GET['id']."' method='POST' class='form-horizontal'>
							<fieldset>
						  <legend>Modification de mode de paiement</legend>
							<p></p>
							<div class='form-group'>
							<label class='col-md-1 control-label'>Paiement</label>  
							<div class='col-lg-3 input-group'> 
							<select name='paiement' class='form-control'>
						<option value=null> </option>";
								
						$paiement = $connexion->query("Select * from modePaiement");
						$paiement->setFetchMode(PDO::FETCH_OBJ);

						while($res = $paiement->fetch())
						{
							echo "<option value='".$res->idModePaiement."'>".$res->libelleModePaiement."</option>";
						}
						echo "
						</select>
						</div></div>
						<div class='form-group'>
						<label class='col-md-1 control-label'> </label>
						<div class='col-md-4'>
						<input type='submit' name='ok' value='Modifier' class='btn btn-primary'/>
						</div></div>
						</fieldset></form>";
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
						echo "</br><form action='commande.php?modif&livraison&id=".$_GET['id']."' method='POST' class='form-horizontal'>
							<fieldset>
						  <legend>Modification de mode de livraison</legend>
							<p></p>
							<div class='form-group'>
							<label class='col-md-1 control-label'>Livraison</label>  
							<div class='col-lg-3 input-group'> 
							<select name='livraison' class='form-control'>
						<option value=null> </option>";
								
						$livraison = $connexion->query("Select * from modeLivraison");
						$livraison->setFetchMode(PDO::FETCH_OBJ);

						while($res = $livraison->fetch())
						{
							echo "<option value='".$res->idModeLivraison."'>".$res->libelleModeLivraison."</option>";
						}
						echo "
						</select>
						</div></div>
						<div class='form-group'>
						<label class='col-md-1 control-label'> </label>
						<div class='col-md-4'>
						<input type='submit' name='ok' value='Modifier' class='btn btn-primary'/>
						</div></div>
						</fieldset></form>";
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
								<td><a href="commande.php?id='.$element->idCommande.'">Commande n°'.$element->idCommande.'</a></td>
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
