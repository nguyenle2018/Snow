<?php
session_start();

if(isset($_SESSION['id_compte']))
  {
  $back="<div id=\"back\"><a href=\"../admin/admin.php\"><span class=\"dashicons dashicons-arrow-left-alt\"></span></a></div>";
  }

//on relie la librairie de fonction
require_once("../outils/fonctions.php");

//on établi la connexion à la base
$connexion=connexion();


//calcul du menu menu_haut========================================
//$items=array("SHOP","TEAMS","EVENTS","EXPERIENCE","CONTACT");
$requete="SELECT * FROM rubriques ORDER BY id_rubrique";
//on exécute la requete
$resultat=mysqli_query($connexion,$requete);
$menu_haut="<ul>\n";
while($ligne=mysqli_fetch_object($resultat))
  {
  if(!empty($ligne->lien_rubrique))
    {
    //lien externe au site
    $menu_haut.="<li><a href=\"".$ligne->lien_rubrique."\">".$ligne->nom_rubrique."</a></li>";
    }
  else
    {
    //lien interne
    $menu_haut.="<li><a href=\"front.php?p=".strtolower($ligne->nom_rubrique)."#".strtolower($ligne->nom_rubrique)."\">".$ligne->nom_rubrique."</a></li>";
    }
  }
$menu_haut.="</ul>\n";

//===============================================================
//calcul des pages à afficher
if(isset($_GET['p']))
  {
  //qq a cliqué sur un item
  $contenu=$_GET['p'] . ".html";
  $visible="class=\"visible\"";
  }
else
  {
  //personne n'a encore cliqué sur un item du menu
  $contenu="snow.html";
  }

//==================================================
//gestion du formulaire de contact
//si qq appuie sur le bouton ENVOYER du formulaire

if(isset($_POST['submit']))
  {
  $message=array();
  if(empty($_POST['nom']))
    {
    $message['nom']="<label class=\"pas_ok\">Mets ton nom</label>";
    $color['nom']="class=\"color_champ\"";
    }
  elseif(empty($_POST['prenom']))
    {
    $message['prenom']="<label class=\"pas_ok\">Mets ton prénom</label>";
    $color['prenom']="class=\"color_champ\"";
    }
  elseif(empty($_POST['mel']))
    {
    $message['mel']="<label class=\"pas_ok\">Mets ton email</label>";
    $color['mel']="class=\"color_champ\"";
    }
  else
    {
    //on va stocker le contenu du formulaire dans la table contacts
    $requete="INSERT INTO contacts SET nom_contact='".security($_POST['nom'])."',
                                      prenom_contact='".security($_POST['prenom'])."',
                                      email_contact='".security($_POST['mel'])."',
                                      message_contact='".security($_POST['message'])."',
                                      date_contact='".date("Y-m-d H:i:s")."'";

    //on exécute la requete
    $resultat=mysqli_query($connexion,$requete);

    $visible="class=\"hidden\"";
    $merci="<h2 class=\"ok\">Merci</h2>";
    }
  }


//on ferme la connexion à la base
mysqli_close($connexion);

//permet de relier le fichier index.php avec le fichier index.html
include("front.html");
?>
