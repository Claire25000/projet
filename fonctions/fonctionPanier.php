<?php
function creationPanier()
{
	if (! isset($_SESSION['panier']))  $_SESSION['panier'] = array();
}

function ajouterPanier($idProd,$qte)
{
    $_SESSION['panier'][$idProd]['qte']  = $qte; // Ajouter un nouvel article
	
	if(isset($_SESSION['panier'][$idProd])){ // si la session est définie a l'ID du produit
		return true;
	}
	return false;
}

function modifierQte($idProd,$qte)
{
	$_SESSION['panier'][$idProd]['qte'] = $qte;	// Modifier la quantité achetée
}

function supprimerArticle($id)
{
	unset($_SESSION['panier'][$id]); // Supprimer un article du panier
}

function viderPanier()
{
	unset($_SESSION['panier']); // Supprimer le panier
}

function retournePanier()
{
	$liste = array();
	if (isset($_SESSION['panier']))
	{
        foreach($_SESSION['panier'] as $id_article=>$article_acheté)
		{

			$liste[] = $article_acheté;
		}
	}
	
	return $liste;
}