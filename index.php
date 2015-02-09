<?php
require_once('inc/inc_top.php');

envoyeMail("ntm","ntm","dododu25@hotmail.fr");


?>
<!DOCTYPE html>
<html lang="fr">
  <head>
	<?php require_once("inc/inc_head.php");?>
    <title>Accueil - </title>
  </head>
  
  <body>
	<!-- navbar -->
	<?php require_once("inc/inc_navbar.php");?>
	
    <div class="container">
      <div class="jumbotron">
		<?php
		require_once('inc/inc_menu.php');
		?>
        <h1><?php echo retourneParametre('nomSite'); ?></h1>
        <p> <?php echo retourneParametre('nomSite'); ?> <br/><br/>
		ADMIN : admin@admin.fr / admin<br/>
		GESTIONAIRE : gestionaire@mail.ru / pass <br/>
		CLIENT : mail@mail.fr / user1<br/>
		</p>
		<p>To see the difference between static and fixed top navbars, just scroll.</p>
        <p>
          <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
        </p>
      </div>
    </div><!-- /container -->
  <?php require_once("inc/inc_footer.php"); ?>
  </body>
</html>
