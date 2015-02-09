<?php
function creationPanier()
{
	if (! isset($_SESSION['panier']))  $_SESSION['panier'] = array();
}

function ajouterPanier($idProd,$qte)
{
	if(isset($_SESSION['panier'][$idProd])){
		$_SESSION['panier'][$idProd]['qte'] = $_SESSION['panier'][$idProd]['qte']+1;
	}else{
		$_SESSION['panier'][$idProd]['qte']  = $qte;
	}
	
	if(isset($_SESSION['panier'][$idProd])){ // si la session est définie a l'ID du produit
		return $connexion->lastInsertId(); 
	}
	return false;
}

function modifierQte($idProd,$qte)
{
	$_SESSION['panier'][$idProd]['qte'] = $qte;	// Modifier la quantité achetée
}

// retourne la quantité de produit qui se trouve dans le panier 
function getQteProduit($idProduit){
	if(isset($_SESSION['panier'][$idProduit]['qte'])){
		return $_SESSION['panier'][$idProduit]['qte'];
	}
	return 0;
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