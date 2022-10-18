<?php
//permet l'usage des variables de SESSION (mémorise)
session_start();

if(!isset($_SESSION['id_compte']))
  {
  //on n'a rien a foutre ici donc retour à la case départ
  header("Location:../index.php");
  }
else
  {
  $titre="Bienvenue " . $_SESSION['prenom_compte'] . " " . strtoupper($_SESSION['nom_compte']);
  //on relie la librairie de fonction
  require_once("../outils/fonctions.php");

  //on établi la connexion à la base
  $connexion=connexion();

  if(isset($_GET['action']))
    {
    switch($_GET['action'])
        {
        case "deconnect":
        //on détruit toutes les variables de session
        session_destroy();
        header("Location:../log/login.php");
        break;

        case "contacts":
        include("contacts.php");
        break;

        case "comptes":
        include("comptes.php");
        break;

        case "rubriques":
        include("rubriques.php");
        break;

        case "pages":
        include("pages.php");
        break;
        }
    }


  //on ferme la connexion à la base
  mysqli_close($connexion);

  include("admin.html");
}

?>
