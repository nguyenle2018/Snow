<?php

if(isset($_GET['cas']))
  {
  switch($_GET['cas'])
    {
    case "afficher_contacts":

    $titre="Gestion des contacts";

    break;

    case "confirmer_suppression":
    if(isset($_GET['id_contact']))
      {
      $popup="<div id=\"popup\">
              <a href=\"admin.php?action=contacts&cas=supprimer_contacts&id_contact=" . $_GET['id_contact'] . "\">OUI</a>
              <a href=\"admin.php?action=contacts&cas=afficher_contacts\">NON</a>
              </div>";
      }

    break;

    case "supprimer_contacts":
    if(isset($_GET['id_contact']))
      {
      $requete="DELETE FROM contacts WHERE id_contact='" . $_GET['id_contact'] . "'";
      //on exécute la requete
      $resultat=mysqli_query($connexion, $requete);
      }
    break;
    }
  }
$tableau=afficher_contacts();
?>
