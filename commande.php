<?php
require_once('inc/inc_top.php');
if(!estConnecte() || estAdmin()){ // si non connecté, on login en tant qu'admin
	header('Location: 404.php?err=1');
}
require_once("fonctions/fonctionsCommande.php");
require_once("fonctions/fonctionProd.php");
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

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Mes commandes</title>
	<?php require_once("inc/inc_head.php"); ?>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron" style="min-height:700px">
		<?php
			require_once('inc/inc_menu.php');
			if(isset($message))
			{
				echo $message;
			}
			?>
			<ol class="breadcrumb">
			  <li><a href="commande.php">Commandes en cours</a></li>
			  <li><a href="commande.php?histo">Historique des commandes</a></li>
			</ol>
			<?php 
		
			$client = retourneClient(idUtilisateurConnecte());
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
				}
			}
			else
			{
				if(!isset($_GET['histo']))
				{
				?>
					<p> Liste des commandes </p>
					<label>Vous avez actuellement <?php echo nombreCommandeEnCours($client->idUtilisateur);?> commande(s) en cours</label>
					
					<div class="panel panel-default">
					<div class="panel-heading">Commandes en cours</div>

					<table class="table">
							<tr>
								<th>Identifiant Commande</th>
								<th>Date</th>
								<th>Statut</th>
								<th>Mode de livraison</th>
								<th>Mode de paiement</th>
							</tr>
						<?php
						foreach(retourneListeCommandeEnCours($client->idUtilisateur) as $element) // retourne un array de commande pour un client
							{
								echo '<tr>
										<td><a href="commande.php?id='.$element->idCommande.'">Commande n°'.$element->idCommande.'</a></td>
										<td>'.$element->date.'</td>
										<td>'.retourneStatut($element->statut).'</td>
										<td>'.retourneLivraison($element->modeLivraison).'</td>
										<td>'.retournePaiement($element->modePaiement).'</td>';
									echo '</tr>';
							}
						?>
						</table>
					</div>
				  </div>
				<?php
				}else
				{
					?>
					<p> Liste des commandes </p>
					<label>Vous avez actuellement <?php echo nombreCommandeHistorique($client->idUtilisateur);?> commande(s) dans votre historique</label>
					
					<div class="panel panel-default">
					<div class="panel-heading">Historique des commandes</div>

					<table class="table">
							<tr>
								<th>Identifiant Commande</th>
								<th>Date</th>
								<th>Statut</th>
								<th>Mode de livraison</th>
								<th>Mode de paiement</th>
							</tr>
						<?php
						foreach(retourneHistoriqueCommande($client->idUtilisateur) as $element) // retourne un array de commande pour un client
							{
								echo '<tr>
										<td><a href="commande.php?id='.$element->idCommande.'">Commande n°'.$element->idCommande.'</a></td>
										<td>'.$element->date.'</td>
										<td>'.retourneStatut($element->statut).'</td>
										<td>'.retourneLivraison($element->modeLivraison).'</td>
										<td>'.retournePaiement($element->modePaiement).'</td>						
									</tr>';
							}
						?>
						</table>
					</div>
				  </div>
				<?php
				}
			}
		?>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
