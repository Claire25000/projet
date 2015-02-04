<?php



/*
function retourneFacture($idFacture){
	retourn un objet facture
}


function retourneListeFacture($idClient){

}
*/

function ajouterFacture($date,$statut,$modePaiement,$modeLivraison,$idClient){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`commande` (`idCommande`, `date`, `statut`, `modePaiement`, `modeLivraison`, `idClient`) VALUES (NULL, '".$date."', '".$statut."', '".$modePaiement."', '".$modeLivraison."','".$idClient."');");
		return true; 
	} catch ( Exception $e ) {
		return false;
	}
}
/*
function nombreFacture($idClient){

}

function changerStatutFacture($idFacture,$statut){

}

function changerModePaiementFacture($idFacture,$modePaiement){

}

function changerModeLivraisonFacture($idFacture,$modeLivraison){

}

*/
?>