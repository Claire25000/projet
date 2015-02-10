<?php
require_once("inc/inc_head.php");
require_once('inc/inc_top.php');
if(!estConnecte() || estAdmin()){ // si non connecté, on login en tant qu'admin
	header('Location: 404.php?err=1');
}
require_once("fonctions/fonctionsCommande.php");

if(isset($_GET['id']))
{
	changerStatutCommande($_GET['id'],2);
	$message = '<div class="alert alert-success" role="alert">Commande payée.</div>';
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Mes commandes</title>
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
		
			$client = retourneClient(idUtilisateurConnecte());
		?>
		<ol class="breadcrumb">
		  <li><a href="commande.php">Commandes en cours</a></li>
		  <li><a href="commande.php?histo">Historique des commandes</a></li>
		</ol>
		<?php
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
								<td>'.$element->idCommande.'</td>
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
								<td>'.$element->idCommande.'</td>
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
		?>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
