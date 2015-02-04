<?php
/*	echo '<a href="/index.php"> Accueil </a> - ';
	
	
if(!estConnecte()){
	echo '
	<form style="color: red; margin-left: 70%;" method="post" action="">
		<input value="admin@admin.fr" type="text" name="email" id="email" />
		<input type="password" name="password" id="password" />
		<input type="submit" value="Ok" />
	</form> ';
	echo '<a href="/inscription.php"> inscription </a> - ';
}else{
	if(estAdmin()){ // si admin on ajoute un lien vers le panel admin
		echo '<a href="/admin"> administration </a> - ';
	}else{ // sinon on fait un lien veres la page de profil client
		echo '<a href="/profil.php"> profil </a> - ';
		echo '<a href="/commande.php"> commande </a> - ';
	}
	echo '<p style="color: red; margin-left: 70%;">';
	echo '[user : '.$_SESSION['idUtilisateur '].']';
	echo '[rang : '.$_SESSION['typeUtilisateur '].']';
	echo '<a href="?deco"> [deco] </a></p>';
}*/
?>
<?php
	$menu = '<ul style="margin-top: -20px;" id="nav">';

	$requete = $connexion->query("SELECT * FROM categorie");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	while($enregistrement = $requete->fetch()){
	
		if(isset($_GET['titleCat'])){
			if($_GET['titleCat'] == $enregistrement->libelleCategorie){
				$activ = 'class="active"';
			}
			else{
				$activ = '';
			}
			$menu .= '<li '.$activ.'><a href="categorie.php?id='.$enregistrement->idCategorie.'&titleCat='.$enregistrement->libelleCategorie.'">'.$enregistrement->libelleCategorie.'</a></li>';
		}
		
	}
	$menu .= '  </ul>
			  ';
	echo $menu;
?>
<!--<div class="container">
		<ul id="nav">
			<li><a href="#">Home</a></li>
			  <li class="active"><a href="#s2">Menu 2</a>
				<span id="s2"></span>
				<ul class="subs">
					<li><a href="#">Header c</a>
						<ul>
							<li><a href="#">Submenu x</a></li>
							<li><a href="#">Submenu y</a></li>
							<li><a href="#">Submenu z</a></li>
						</ul>
					</li>
					<li><a href="#">Header d</a>
						<ul>
							<li><a href="#">Submenu x</a></li>
							<li><a href="#">Submenu y</a></li>
							<li><a href="#">Submenu z</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<li><a href="#">Menu 3</a></li>
		</ul>
    </div>-->