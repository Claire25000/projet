<?php
require_once('inc/inc_top.php');
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionPanier.php");

// Voici les données externes utilisées par le panier
$id_article = null;
$qte_article = 1;

if(isset($_GET['id']))
{
	$id_article = $_GET['id'];
	$qte_article = $_GET['qte'];
}
elseif(isset($_POST['id']))
{
	$id_article = $_POST['id'];
	$qte_article = $_POST['qte'];
}

// Voici les traitements du panier
if ($id_article == null) echo 'Veuillez sélectionner un article pour le mettre dans le panier!'; // Message si pas d'acticle sélectionné
else
if (isset($_GET['ajouter']))
{
	ajouterPanier($id_article,$qte_article);
}
elseif(isset($_POST['modifier']))  
{
	modifierQte($id_article,$qte_article);
} 
elseif(isset($_POST['supprimer']))
{
	supprimerArticle($_POST['id']);
}

// Voici l'affichage du panier
echo '<h2>Contenu de votre panier</h2><ul>';
if (isset($_SESSION['panier']) && count($_SESSION['panier'])>0){
        $total_panier = 0;
        foreach($_SESSION['panier'] as $id_article=>$article_acheté){
                // On affiche chaque ligne du panier : nom, prix et quantité modifiable + 2 boutons : modifier la qté et supprimer l'article
				
				$produit = retourneProduit($id_article);		
				
                if (isset($article_acheté['qte'])){
                        echo '<li><form action="panier.php" method="POST">', $_SESSION['panier'][$id_article]['qte'],' ',$produit->nomProduit, ' (', number_format($produit->prixProduit, 2, ',', ' '), ' €) ',
                         '<input type="hidden" name="id" value='.$id_article.' />
                          <br />Qté: <select name="qte">
								<option selected="selected" value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
							</select>
							<input type="text" name="" value=""></input>
                          <input type="submit" name="modifier" value="Modifier la quantité" />
                          <input type="submit" name="supprimer" value="Supprimer" />
                        </form>
                        </li>';
						
                        // Calcule le prix total du panier 
                        $total_panier += $produit->prixProduit * $article_acheté['qte'];
                }
        }
        echo '<hr><h3>Total: ', number_format($total_panier, 2, ',', ' '), ' €'; // Affiche le total du panier
}
else { echo 'Votre panier est vide'; } // Message si le panier est vide
echo "</ul>";
?>