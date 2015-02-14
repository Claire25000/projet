<?php
// retourneUtilisateur(idUtilisateur)
// retourne un objet utilisateur
function retourneUtilisateur($idUtilisateur){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	$requete = $connexion->query("SELECT * FROM utilisateur where idUtilisateur=".$idUtilisateur."");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $requete->fetch();
	

	return $enregistrement; // on renvoie un objet utilisateur
}
// retourneListeUtilisateur()
// retourne la liste (array) des utilisateurs
function retourneListeUtilisateur(){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	$utilisateurs = array();
	$requete = $connexion->query("SELECT * FROM utilisateur");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	while($enregistrement = $requete->fetch()){
		$utilisateurs[] = $enregistrement; // on ajoute à $utilisateurs[n+1] un objet utilisateur
	}
	return $utilisateurs;
}
// connecteUtilisateur("nomUtilisateur","password")
// retourne true si connexion réussie, false sinon
function connecteUtilisateur($email,$password){
	global $connexion; // on définie la variables globale de connection dans la fonction
	//$requete = $connexion->query("SELECT * FROM utilisateur where email='".$email."' AND passwd='".$password."'"); // on ajoute un salt au md5 : &4à[5s
	$requete = $connexion->query("SELECT * FROM utilisateur where email='".$email."' AND passwd='".md5("&4à[5s".$password)."'"); // on ajoute un salt au md5 : &4à[5s
	//echo "recu -> (".$email.")(".$password.")".md5("&4à[5s".$password)." <-  ";

	$requete->setFetchMode(PDO::FETCH_OBJ);
 	if($requete->rowCount() != 1){ // si on a pas 1 résultat, l'authentification est un échec
		return false;
	}
	$enregistrement = $requete->fetch();
	$_SESSION['idUtilisateur '] = $enregistrement->idUtilisateur;	// on stock l'ID utilisateur en session
	$_SESSION['typeUtilisateur '] = $enregistrement->type;			// on stock le type de l'utilisateur en session
	return true;
}
// deconnecteUtilisateur()
// retourne true si la session est détruite (utilisateur déconnecté), false sinon
function deconnecteUtilisateur(){
	unset($_SESSION['idUtilisateur ']);
	unset($_SESSION['typeUtilisateur ']);
	return true;
}
// ajouterUtilisateur("nomUtilisateur","password","idTypeUtilisateur");
// retourne l'ID du l'utilisateur créé, retourne 0 en cas d'erreur
function ajouterUtilisateur($login,$password,$email,$type){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$password = md5("&4à[5s".$password); // on hash directement le password, et on y ajoute le salt : &4à[5s
	try {
		$requete = $connexion->exec("INSERT INTO `webuzzer54gs9`.`utilisateur` (`idUtilisateur` ,`login` ,`passwd`,`email` ,`type`)VALUES (NULL, '".mysql_real_escape_string($login)."', '".$password."', '".mysql_real_escape_string($email)."', '".$type."');");
		envoyeMail('Votre compte client a été créé sur '.retourneParametre('nomSite').'.','Bienvenue sur '.retourneParametre('nomSite').' ! <br/> Votre compte a été créer sur notre plateforme, vous avez désormais accès à toutes les fonctionalitées. <br/> Votre email de connexion est '.$email.'.',$email);
		return $connexion->lastInsertId(); 
	} catch ( Exception $e ) {
		return 0;
		echo "Une erreur est survenue";
	}
}
function changerPasswordUtilisateur($idUtilisateur,$passwordOld,$passwordNew){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SELECT * FROM `utilisateur`WHERE `idUtilisateur` =".$idUtilisateur."");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $requete->fetch();
	$enregistrement->passwd; // mot de passe crypté actuel de l'utilisateur
	
	
	
	if($enregistrement->passwd == md5("&4à[5s".$passwordOld)){ // si le password actuel de l'utilisateur est identique a celui qu'il a renseigné en vérification de l'ancien password
		$passwordNew = md5("&4à[5s".$passwordNew); // on hash directement le password, et on y ajoute le salt : &4à[5s
		try {
			$requete = $connexion->exec("UPDATE `webuzzer54gs9`.`utilisateur` SET `passwd` = '".$passwordNew."' WHERE `utilisateur`.`idUtilisateur` =".$idUtilisateur.";");
		} catch ( Exception $e ) {
			return false;
		}
		return true;
	}
	return false;
}
function changerEmailUtilisateur($idUtilisateur,$email){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	try {
		$requete = $connexion->exec("UPDATE `webuzzer54gs9`.`utilisateur` SET `email` = '".mysql_real_escape_string($email)."' WHERE `utilisateur`.`idUtilisateur` =".$idUtilisateur.";");
	} catch ( Exception $e ) {
		return false;
	}
	return true;
}
function supprimerUtilisateur($idUtilisateur){
	global $connexion; // on définie la variables globale de connection dans la fonction

	try {
		$requete = $connexion->exec("DELETE FROM `webuzzer54gs9`.`utilisateur` WHERE `utilisateur`.`idUtilisateur` = ".$idUtilisateur.";");		
	} catch ( Exception $e ) {
		echo "Une erreur est survenue";
		return false;
	}
	return true;
}
// estWebmaster()
// retourne true si le connecté est super admin (rang 1), sinon false
function estWebmaster(){
	//echo "fct apl";
	if(isset($_SESSION['typeUtilisateur ']) && $_SESSION['typeUtilisateur '] == 1){
		return true;
	}
	return false;
}
// estGestionaire()
// retourne true si le connecté est admin (rang 2), sinon false
function estGestionaire(){
	if(isset($_SESSION['typeUtilisateur ']) && $_SESSION['typeUtilisateur '] == 2){
		return true;
	}
	return false;
}
// estAdmin()
// retourne true si le connecté est admin (rang 1 ou 2)
function estAdmin(){
	if(estWebmaster() || estGestionaire()){
		return true;
	}
	return false;
}
// estConnecte()
// retourne true si l'utilisateur est correctement connecté (sessions présentes)
function estConnecte(){
	if(isset($_SESSION['idUtilisateur ']) && isset($_SESSION['typeUtilisateur '])){
		return true;
	}
	return false;
}
// retourneListeAdministrateur()
// retourne la liste (array) des administrateurs
function retourneListeAdministrateurs(){
	global $connexion; // on définie la variables globale de connection dans la fonction
	
	$utilisateurs = array();
	$requete = $connexion->query("SELECT * FROM utilisateur WHERE type='1' OR type='2'");
	$requete->setFetchMode(PDO::FETCH_OBJ);
	while($enregistrement = $requete->fetch()){
		$utilisateurs[] = $enregistrement; // on ajoute à $utilisateurs[n+1] un objet utilisateur
	}
	return $utilisateurs;
}
// nombreUtilisateur()
// retourne le nombre total d'utilisateur
function nombreUtilisateur(){
	global $connexion; // on définie la variables globale de connection dans la fonction

	$requete = $connexion->query("SELECT idUtilisateur FROM utilisateur");
	$requete->setFetchMode(PDO::FETCH_OBJ);
 	return $requete->rowCount();
}
?>