<?php
function creationPanier()
{
	if (! isset($_SESSION['panier']))  $_SESSION['panier'] = array();
}

function ajouterPanier($idProd,$qte)
{
    $_SESSION['panier'][$idProd]['qte']  = $qte; // Ajouter un nouvel article
}

function modifierQte($idProd,$qte)
{
	$_SESSION['panier'][$idProd]['qte'] = $qte;	// Modifier la quantité achetée
}

function SupprimerArticle($id)
{
	unset($_SESSION['panier'][$id]); // Supprimer un article du panier
}