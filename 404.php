<?php
require_once('inc/inc_top.php');
if(!isset($_GET['err'])){ // si aucun code d'erreur fourni
	$codeErreur = '0';
}else{
	$codeErreur = ''.$_GET['err'].'';
}
?>
<html>
	<head>
		<title>404</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	
	<body>
		<?php
			require_once('inc/inc_menu.php');
		?>
		<p>
			<?php echo retourneErreur($codeErreur); ?><br/>
		</p>
	</body>
</html>
