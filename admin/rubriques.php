<?php

if(isset($_GET['cas']))
  {
  $form="form_rubriques.html";
  $titre="Gestion des rubriques";

  switch($_GET['cas'])
    {
    case "ajouter_rubriques":

    $action_form="ajouter_rubriques";
    //si qq appuie sur le bouton ENVOYER du formulaire
    if(isset($_POST['submit']))
      {
      $message=array();
      if(empty($_POST['nom_rubrique']))
        {
        $message['nom_rubrique']="<label class=\"pas_ok\">Mets une rubrique</label>";
        $color['nom_rubrique']="class=\"color_champ\"";
        }
      else
        {
        //on va stocker le contenu du formulaire dans la table pages
        $requete="INSERT INTO rubriques SET nom_rubrique='".addslashes($_POST['nom_rubrique'])."',
                                        lien_rubrique='".addslashes($_POST['lien_rubrique'])."'";
        //on exécute la requete
        $resultat=mysqli_query($connexion,$requete);
        $validation="<p class=\"ok\">La rubrique a bien été créée</p>";
        //on vide les champs du formulaire
        foreach($_POST AS $cle=>$valeur)
          {
          unset($_POST[$cle]);
          }
        }
      }
    break;

    case "confirmer_suppression":
    $action_form="ajouter_rubriques";
    if(isset($_GET['id_rubrique']))
      {
      $popup="<div id=\"popup\">
              <a href=\"admin.php?action=rubriques&cas=supprimer_rubriques&id_rubrique=" . $_GET['id_rubrique'] . "\">OUI</a>
              <a href=\"admin.php?action=rubriques&cas=ajouter_rubriques\">NON</a>
              </div>";
      }

    break;

    case "supprimer_rubriques":
    $action_form="ajouter_rubriques";
    if(isset($_GET['id_rubrique']))
      {
      //on vérifie si il y a des pages liées à cette rubrique
      $requete="SELECT * FROM pages WHERE id_rubrique='".$_GET['id_rubrique']."'";
      $resultat=mysqli_query($connexion, $requete);
      $nb_pages=mysqli_num_rows($resultat);
      if($nb_pages==0)
        {
        $requete="DELETE FROM rubriques WHERE id_rubrique='" . $_GET['id_rubrique'] . "'";
        //on exécute la requete
        $resultat=mysqli_query($connexion, $requete);
        $validation="<p class=\"ok\">La rubrique a bien été supprimée</p>";
        }
      else
        {
        $validation="<p class=\"pas_ok\">Attention, il y a " . $nb_pages . " page(s) liée(s) à cette rubrique</p>";
        }
      }

    break;

    case "confirmer_modification":

    if(isset($_GET['id_rubrique']))
      {
      $action_form="modifier_rubriques&id_rubrique=" . $_GET['id_rubrique'];
      //on va selectionner la ligne de la table concernant ce compte
      $requete="SELECT * FROM rubriques WHERE id_rubrique='".$_GET['id_rubrique']."'";
      $resultat=mysqli_query($connexion,$requete);
      $ligne=mysqli_fetch_object($resultat);

      //on recharge le formulaire

      $_POST['nom_rubrique']=$ligne->nom_rubrique;
      $_POST['lien_rubrique']=$ligne->lien_rubrique;
      }

    break;

    case "modifier_rubriques":

    $action_form="modifier_rubriques&id_rubrique=" . $_GET['id_rubrique'];

    //si qq appuie sur le bouton ENVOYER du formulaire
    if(isset($_POST['submit']))
      {
      $message=array();

      if(empty($_POST['nom_rubrique']))
        {
        $message['nom_rubrique']="<label class=\"pas_ok\">Mets ta rubrique</label>";
        $color['nom_rubrique']="class=\"color_champ\"";
        }
      else
        {
        //on reinitialise l'action du formulaire afin qu'il soit pret a enregistrer
        //une nouvelle page
        $action_form="ajouter_rubriques";

        $requete="UPDATE rubriques SET nom_rubrique='".addslashes($_POST['nom_rubrique'])."',
                                        lien_rubrique='".addslashes($_POST['lien_rubrique'])."'
                                        WHERE id_rubrique='" . $_GET['id_rubrique'] . "'";

        //on exécute la requete
        $resultat=mysqli_query($connexion,$requete);
        $validation="<p class=\"ok\">La rubrique a bien été modifiée</p>";
        //on vide les champs du formulaire
        foreach($_POST AS $cle=>$valeur)
          {
          unset($_POST[$cle]);
          }
        }
      }

    break;
    }
  }
$tableau=afficher_rubriques();
?>
