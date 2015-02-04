<?php
require_once('inc/inc_top.php');
require_once('inc/inc_head.php');
require_once("fonctions/fonctionProd.php");
require_once("fonctions/fonctionPanier.php");

if(isset($_SESSION['panier']))
{
	$nb = count($_SESSION['panier']); 

			echo '<li class="dropdown"><!-- panier -->
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAEwSURBVEiJtZYxTsNAEEXfgJRESgVVDgA3iPYmKD2XiXIPego3SClc0FNFtOQACIIQFYah8DhZx5F3bdYjfckaz/4/Hu3ftagqQ8bZoOxdBERkIiJrwyRaQVWjAKwANayi10WSO6AAPgwF4JIIAGPg2Tq/NajlxikElkaYebnMcst/CQBzG8crMPPyM8sVwLyXADACNtbp4sT7hb3bAKNWAeDB2yEp8FvttErgPbGAArmq7o2Wt7ulV+RwcPJ6AIGS00Z0RfiTHykN5+y5rXYHnNd2EfASWOS8Wheova9q/cMu5ZgOXF5XN4GuuozoumE04BL4CSyMwdY32n5EqvoGPCUdD80LJ0sgUOc4Ol+mlAbpM5pv4O74LBIjroWIXFDeA13iS1U/G1ynBFLG4H8Vf/hXPMQ3HehcAAAAAElFTkSuQmCCd47bef6222a6338574288a0548844d88"/>
						<span class="badge">'.$nb.'</span>
					</a>
					<ul style="padding: 15px;min-width: 280px;" class="dropdown-menu">';
					if($nb == 0)
					{
						echo '<li  style="margin-top:1px">
							<div class="btn-group-sm" role="group" aria-label="...">
							Votre panier est vide.
							</div>
						</li>';
					}
					foreach($_SESSION['panier'] as $id_article=>$article_acheté)
					{
						$res = retourneProduit($id_article);
						echo '<li  style="margin-top:1px">
							<div class="btn-group-sm" role="group" aria-label="...">
							<button method="?test3" style="border: 0 none;margin-left:1%" type="button" class="btn btn-default" onClick="supp('.$res->idProduit.');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
							<button method="?test3" type="button" style="border: 0 none;width:83%" class="btn btn-default" onClick="redirection('.$res->idProduit.');">'.$res->nomProduit.'</button>
							</div>
						</li>';
					}
						
					echo '</ul>
				</li>';
}
else
{
	echo '<li class="dropdown"><!-- panier -->
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAEwSURBVEiJtZYxTsNAEEXfgJRESgVVDgA3iPYmKD2XiXIPego3SClc0FNFtOQACIIQFYah8DhZx5F3bdYjfckaz/4/Hu3ftagqQ8bZoOxdBERkIiJrwyRaQVWjAKwANayi10WSO6AAPgwF4JIIAGPg2Tq/NajlxikElkaYebnMcst/CQBzG8crMPPyM8sVwLyXADACNtbp4sT7hb3bAKNWAeDB2yEp8FvttErgPbGAArmq7o2Wt7ulV+RwcPJ6AIGS00Z0RfiTHykN5+y5rXYHnNd2EfASWOS8Wheova9q/cMu5ZgOXF5XN4GuuozoumE04BL4CSyMwdY32n5EqvoGPCUdD80LJ0sgUOc4Ol+mlAbpM5pv4O74LBIjroWIXFDeA13iS1U/G1ynBFLG4H8Vf/hXPMQ3HehcAAAAAElFTkSuQmCCd47bef6222a6338574288a0548844d88"/>
						<span class="badge">0</span>
					</a>
					<ul style="padding: 15px;min-width: 280px;" class="dropdown-menu">
					<li  style="margin-top:1px">
							<div class="btn-group-sm" role="group" aria-label="...">
							Votre panier est vide.
							</div>
						</li>';
}