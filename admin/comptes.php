<?php

if(isset($_GET['cas']))
  {
  $form="form_comptes.html";
  $titre="Gestion des comptes";

  switch($_GET['cas'])
    {
    case "ajouter_comptes":

    $action_form="ajouter_comptes";
    //si qq appuie sur le bouton ENVOYER du formulaire
    if(isset($_POST['submit']))
      {
      $message=array();
      if(empty($_POST['nom_compte']))
        {
        $message['nom_compte']="<label class=\"pas_ok\">Mets ton nom</label>";
        $color['nom_compte']="class=\"color_champ\"";
        }
      elseif(empty($_POST['prenom_compte']))
        {
        $message['prenom_compte']="<label class=\"pas_ok\">Mets ton prénom</label>";
        $color['prenom_compte']="class=\"color_champ\"";
        }
      elseif(empty($_POST['login_compte']))
        {
        $message['login_compte']="<label class=\"pas_ok\">Mets ton login</label>";
        $color['login_compte']="class=\"color_champ\"";
        }
      elseif(empty($_POST['pass_compte']))
        {
        $message['pass_compte']="<label class=\"pas_ok\">Mets ton pass</label>";
        $color['pass_compte']="class=\"color_champ\"";
        }
      else
        {
        //on teste si le meme login n'existe pas deja
        $requete="SELECT login_compte FROM comptes WHERE login_compte='".$_POST['login_compte']."'";
        $resultat=mysqli_query($connexion,$requete);
        $nb_login=mysqli_num_rows($resultat);
        if($nb_login==0)
          {
          //on va stocker le contenu du formulaire dans la table contacts
          $requete="INSERT INTO comptes SET nom_compte='".addslashes($_POST['nom_compte'])."',
                                            prenom_compte='".addslashes($_POST['prenom_compte'])."',
                                            login_compte='".addslashes($_POST['login_compte'])."',
                                            pass_compte=SHA1('".$_POST['pass_compte']."')";
          //on exécute la requete
          $resultat=mysqli_query($connexion,$requete);
          $validation="<p class=\"ok\">Le compte a bien été ajouté</p>";
          //on vide les champs du formulaire
          foreach($_POST AS $cle=>$valeur)
            {
            unset($_POST[$cle]);
            }
          }
        else
          {
          $validation="<p id=\"pas_ok\">Ce login existe déjà !</p>";
          }
        }
      }
    break;

    case "confirmer_suppression":
    $action_form="ajouter_comptes";
    if(isset($_GET['id_compte']))
      {
      $popup="<div id=\"popup\">
              <a href=\"admin.php?action=comptes&cas=supprimer_comptes&id_compte=" . $_GET['id_compte'] . "&i=".$_GET['i']."\">OUI</a>
              <a href=\"admin.php?action=comptes&cas=ajouter_comptes\">NON</a>
              </div>";
      }

    break;

    case "supprimer_comptes":
    $action_form="ajouter_comptes";
    if(isset($_GET['id_compte']))
      {
      //on teste si ce n'est pas le dernier compte
      $requete="SELECT id_compte FROM comptes";
      $resultat=mysqli_query($connexion, $requete);
      $nb_comptes=mysqli_num_rows($resultat);
      if($nb_comptes==1)
        {
        $validation="<p id=\"pas_ok\">Le dernier compte ne peut pas être supprimé</p>";
        }
      else
        {
        $requete2="DELETE FROM comptes WHERE id_compte='" . $_GET['id_compte'] . "'";
        //on exécute la requete
        $resultat2=mysqli_query($connexion, $requete2);
        $validation="<p class=\"ok\">Le compte ".$_GET['i']." a bien été supprimé</p>";
        }
      }

    break;

    case "confirmer_modification":

    if(isset($_GET['id_compte']))
      {
      $action_form="modifier_comptes&id_compte=" . $_GET['id_compte'];
      //on va selectionner la ligne de la table concernant ce compte
      $requete="SELECT * FROM comptes WHERE id_compte='".$_GET['id_compte']."'";
      $resultat=mysqli_query($connexion,$requete);
      $ligne=mysqli_fetch_object($resultat);

      //on recharge le formulaire
      $_POST['nom_compte']=$ligne->nom_compte;
      $_POST['prenom_compte']=$ligne->prenom_compte;
      $_POST['login_compte']=$ligne->login_compte;
      }

    break;

    case "modifier_comptes":

    $action_form="modifier_comptes&id_compte=" . $_GET['id_compte'];

    //si qq appuie sur le bouton ENVOYER du formulaire
    if(isset($_POST['submit']))
      {
      $message=array();
      if(empty($_POST['nom_compte']))
        {
        $message['nom_compte']="<label class=\"pas_ok\">Mets ton nom</label>";
        $color['nom_compte']="class=\"color_champ\"";
        }
      elseif(empty($_POST['prenom_compte']))
        {
        $message['prenom_compte']="<label class=\"pas_ok\">Mets ton prénom</label>";
        $color['prenom_compte']="class=\"color_champ\"";
        }
      elseif(empty($_POST['login_compte']))
        {
        $message['login_compte']="<label class=\"pas_ok\">Mets ton login</label>";
        $color['login_compte']="class=\"color_champ\"";
        }
      else
        {
        //on reinitialise l'action du formulaire afin qu'il soit pret a enregistrer
        //un nouveau compte
        $action_form="ajouter_comptes";



        //on vérifie si un login identique n'existe pas déja
        $requete="SELECT login_compte FROM comptes WHERE login_compte='".$_POST['login_compte']."' AND id_compte!='".$_GET['id_compte']."'";
        $resultat=mysqli_query($connexion,$requete);
        $nb_login=mysqli_num_rows($resultat);
        if($nb_login==0)
          {
          //on va modifier le contenu du formulaire dans la table comptes
          $requete="UPDATE comptes SET nom_compte='".addslashes($_POST['nom_compte'])."',
                                            prenom_compte='".addslashes($_POST['prenom_compte'])."',
                                            login_compte='".addslashes($_POST['login_compte'])."'";
          //si le champ pass a été modifié
          if(!empty($_POST['pass_compte']))
            {
            $requete.=",pass_compte=SHA1('".$_POST['pass_compte']."')";
            }
          $requete.=" WHERE id_compte='".$_GET['id_compte']."'";

          //on exécute la requete
          $resultat=mysqli_query($connexion,$requete);
          $validation="<p class=\"ok\">Le compte a bien été modifié</p>";
          //on vide les champs du formulaire
          foreach($_POST AS $cle=>$valeur)
            {
            unset($_POST[$cle]);
            }
          }
        else
          {
          $validation="<p id=\"pas_ok\">Le compte doit être unique</p>";
          }
        }
      }

    break;
    }
  }
$tableau=afficher_comptes();
?>
