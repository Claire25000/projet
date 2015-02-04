<a href="/index.php"> Accueil site</a> - 
<a href="index.php"> Accueil admin </a> - 
<a href="utilisateur.php"> Gestion utilisateur </a> - 
<a href="produit.php"> Gestion des produits </a> - 
<a href="?deco"> Déconnexion </a> - 
<?php
echo '[user : '.$_SESSION['idUtilisateur '].']';
echo '[rang : '.$_SESSION['typeUtilisateur '].']';
?>
<br/><br/>
