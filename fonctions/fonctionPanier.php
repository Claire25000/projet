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

function supprimerArticle($id)
{
	unset($_SESSION['panier'][$id]); // Supprimer un article du panier
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