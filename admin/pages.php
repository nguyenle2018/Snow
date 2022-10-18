<?php

if(isset($_GET['cas']))
  {
  $form="form_pages.html";
  $titre="Gestion des pages";
  $quality=100;
  $taille_m=800;
  $taille_s=60;

  switch($_GET['cas'])
    {
    case "ajouter_pages":

    //création de la liste déroulante dynamique des rubriques
    $requete="SELECT * FROM rubriques ORDER BY nom_rubrique";
    $resultat=mysqli_query($connexion,$requete);
    $liste_rubriques="";
    while($ligne=mysqli_fetch_object($resultat))
      {
      //permet de garder en mémoire la selection de l'utilisateur
      if(isset($_POST['id_rubrique']) && $_POST['id_rubrique']==$ligne->id_rubrique)
        {
        $select="selected";
        }
      else
        {
        $select="";
        }
      $liste_rubriques.="<option value=\"" . $ligne->id_rubrique . "\" " . $select . ">"
       . $ligne->nom_rubrique . "</option>\n";
      }

    $action_form="ajouter_pages";
    //si qq appuie sur le bouton ENVOYER du formulaire
    if(isset($_POST['submit']))
      {
      $message=array();
      if(empty($_POST['id_rubrique']))
        {
        $message['id_rubrique']="<label class=\"pas_ok\">Sélectionne une rubrique</label>";
        $color['id_rubrique']="class=\"color_champ\"";
        }
      elseif(empty($_POST['titre_page']))
        {
        $message['titre_page']="<label class=\"pas_ok\">Mets un titre</label>";
        $color['titre_page']="class=\"color_champ\"";
        }
      elseif(empty($_POST['contenu_page']))
        {
        $message['contenu_page']="<label class=\"pas_ok\">Mets ton contenu</label>";
        $color['contenu_page']="style=\"background-color:wheat\"";
        }
      else
        {
        //on va stocker le contenu du formulaire dans la table pages
        $requete="INSERT INTO pages SET id_rubrique='".$_POST['id_rubrique']."',
                                        id_compte='".$_SESSION['id_compte']."',
                                        titre_page='".addslashes($_POST['titre_page'])."',
                                        contenu_page='".addslashes($_POST['contenu_page'])."',
                                        date_page=NOW()";
        //on exécute la requete
        $resultat=mysqli_query($connexion,$requete);

        //on traite à présent le cas des fichiers uploadés
        //si le champ parcourir ne reste pas vide
        if(!empty($_FILES['fichier_page']['name']))
          {
          //on récupere le id_page qui vient d'être créé
          $id_cree=mysqli_insert_id($connexion);

          //on calcule l'extension du fichier uploadé
          $extension=fichier_type($_FILES['fichier_page']['name']);

          //on traite le cas du fichier uploadé : vérification de l'extension de type image
          if($extension=="jpg" || $extension=="png" || $extension=="gif")
            {
            //on calcule les 3 chemins pour les 3 images qui vont être generées
            $chemin_b="../medias/media" . $id_cree . "_b." . $extension;
            $chemin_m="../medias/media" . $id_cree . "_m." . $extension;
            $chemin_s="../medias/media" . $id_cree . "_s." . $extension;
            if(is_uploaded_file($_FILES['fichier_page']['tmp_name']))
            // tmp_name correspond au nom temporaire donné au fichier
            //lors de sa copie sur le serveur
              {
              if(copy($_FILES['fichier_page']['tmp_name'], $chemin_b))
                {
                //on calcule la taille de l'image d'origine uploadée
                $size=GetImageSize($chemin_b);
                $largeur=$size[0];
                $hauteur=$size[1];
                //on recherche le format de l'image (portrait / paysage / carré)
                $rapport=$largeur/$hauteur;

                if($largeur>$taille_m)
                  {
                  //nouvelle largeur
                  $x=$taille_m;
                  }
                else
                  {
                  //nouvelle largeur
                  $x=$largeur;
                  }
                //nouvelle hauteur
                $y=$x/$rapport;

                //on génère la miniature "m" du fichier d'origine
                redimage($chemin_b,$chemin_m,$x,$y,$quality);

                //on génère la miniature "s" du fichier d'origine
                $y=$taille_s/$rapport;
                redimage($chemin_b,$chemin_s,$taille_s,$y,$quality);

                //on met à jour l'enregistrement dans la table pages
                $requete2="UPDATE pages SET fichier_page='" . $chemin_b . "'
                        WHERE id_page='". $id_cree . "'";
                $resultat2=mysqli_query($connexion,$requete2);
                }
              }
            }
          else
            {
            $validation="<p class=\"pas_ok\">Seules les extensions jpg, png et gif sont autorisées</p>";
            }
          }
      $validation="<p class=\"ok\">La page a bien été créée</p>";
      //on vide les champs du formulaire
      foreach($_POST AS $cle=>$valeur)
        {
        unset($_POST[$cle]);
        }
      }
    }
    break;

    case "confirmer_suppression":
    $action_form="ajouter_pages";
    if(isset($_GET['id_page']))
      {
      $popup="<div id=\"popup\">
              <a href=\"admin.php?action=pages&cas=supprimer_pages&id_page=" . $_GET['id_page'] . "\">OUI</a>
              <a href=\"admin.php?action=pages&cas=ajouter_pages\">NON</a>
              </div>";
      }
    break;

    case "supprimer_pages":
    $action_form="ajouter_pages";
    if(isset($_GET['id_page']))
      {
      $requete="SELECT * FROM pages WHERE id_page='" . $_GET['id_page'] . "'";
      //on exécute la requete
      $resultat=mysqli_query($connexion, $requete);
      $ligne=mysqli_fetch_object($resultat);

      //si il ya un media associé à a page
      if(!empty($ligne->fichier_page))
      {
        //on supprime les fichiers du dossier medias
        $chemin=array();
        $chemin[0]=$ligne->fichier_page;
        $chemin[1]=str_replace("_b", "_m", $ligne->fichier_page);
        $chemin[2]=str_replace("_b", "_s", $ligne->fichier_page);
        //echo $chemin[0];
        // le @ permet de désactiver les warning sur la fonction unlink
        foreach($chemin AS $cle => $valeur)
          {
          @unlink($valeur);
          }
        }
      $requete2="DELETE FROM pages WHERE id_page='" . $_GET['id_page'] . "'";
      //on exécute la requete
      $resultat=mysqli_query($connexion, $requete2);

      //si il y a un fichier image

      $validation="<p class=\"ok\">La page a bien été supprimée</p>";
      }
    break;

    case "supprimer_image":
    $action_form="ajouter_pages";
    if(isset($_GET['id_page']))
      {
      //on met à jour la table
      $requete="UPDATE pages SET fichier_page='' WHERE id_page='" . $_GET['id_page'] . "'";
      //on exécute la requete
      $resultat=mysqli_query($connexion, $requete);

      //on supprime les fichiers du dossier medias
      $chemin=array();
      $chemin[0]="../medias/media" . $_GET['id_page'] . "_b." . $_GET['extension'];
      $chemin[1]="../medias/media" . $_GET['id_page'] . "_m." . $_GET['extension'];
      $chemin[2]="../medias/media" . $_GET['id_page'] . "_s." . $_GET['extension'];
      //echo $chemin[0];
      // le @ permet de désactiver les warning sur la fonction unlink
      foreach($chemin AS $cle => $valeur)
        {
        @unlink($valeur);
        }
      $validation="<p class=\"ok\">Le média a bien été supprimé</p>";
      }
    break;

    case "confirmer_modification":

    if(isset($_GET['id_page']))
      {
      $action_form="modifier_pages&id_page=" . $_GET['id_page'];
      //on va selectionner la ligne de la table concernant ce compte
      $requete="SELECT * FROM pages WHERE id_page='".$_GET['id_page']."'";
      $resultat=mysqli_query($connexion,$requete);
      $ligne=mysqli_fetch_object($resultat);

      //création de la liste déroulante dynamique des rubriques
      $requete2="SELECT * FROM rubriques ORDER BY nom_rubrique";
      $resultat2=mysqli_query($connexion,$requete2);
      $liste_rubriques="";
      while($ligne2=mysqli_fetch_object($resultat2))
        {
        if($ligne2->id_rubrique==$ligne->id_rubrique)
          {
          $select="selected";
          }
        else
          {
          $select="";
          }
        $liste_rubriques.="<option value=\"" . $ligne2->id_rubrique . "\" " . $select . ">" . $ligne2->nom_rubrique . "</option>\n";
        }

      //on recharge le formulaire
      $_POST['titre_page']=$ligne->titre_page;
      $_POST['contenu_page']=$ligne->contenu_page;
      if(!empty($ligne->fichier_page))
        {
          $apercu_media="<label><img src=\"".str_replace("_b", "_s", $ligne->fichier_page)."\" alt=\"\" /></label>";
        }
      else
        {
          $apercu_media="<label>aucun média</label>";
        }
      }
    break;

    case "modifier_pages":

    $action_form="modifier_pages&id_page=" . $_GET['id_page'];

    //création de la liste déroulante dynamique des rubriques
    $requete="SELECT * FROM rubriques ORDER BY nom_rubrique";
    $resultat=mysqli_query($connexion,$requete);
    $liste_rubriques="";
    while($ligne=mysqli_fetch_object($resultat))
      {
      //permet de garder en mémoire la selection de l'utilisateur
      if(isset($_POST['id_rubrique']) && $_POST['id_rubrique']==$ligne->id_rubrique)
        {
        $select="selected";
        }
      else
        {
        $select="";
        }
      $liste_rubriques.="<option value=\"" . $ligne->id_rubrique . "\" " . $select . ">"
       . $ligne->nom_rubrique . "</option>\n";
      }

    //si qq appuie sur le bouton ENVOYER du formulaire
    if(isset($_POST['submit']))
      {
      $message=array();
      if(empty($_POST['id_rubrique']))
        {
        $message['id_rubrique']="<label class=\"pas_ok\">Sélectionne ta rubrique</label>";
        $color['id_rubrique']="class=\"color_champ\"";
        }
      elseif(empty($_POST['titre_page']))
        {
        $message['titre_page']="<label class=\"pas_ok\">Mets ton titre</label>";
        $color['titre_page']="class=\"color_champ\"";
        }
      elseif(empty($_POST['contenu_page']))
        {
        $message['contenu_page']="<label class=\"pas_ok\">Mets ton contenu</label>";
        $color['contenu_page']="style=\"background:wheat\"";
        }
      else
        {
        //on reinitialise l'action du formulaire afin qu'il soit pret a enregistrer
        //une nouvelle page
        $action_form="ajouter_pages";

        $requete="UPDATE pages SET id_rubrique='".$_POST['id_rubrique']."',
                                        id_compte='".$_SESSION['id_compte']."',
                                        titre_page='".addslashes($_POST['titre_page'])."',
                                        contenu_page='".addslashes($_POST['contenu_page'])."',
                                        date_page='" . date("Y-m-d H:i:s") . "'
                                        WHERE id_page='" . $_GET['id_page'] . "'";
        //on exécute la requete
        $resultat=mysqli_query($connexion,$requete);

        //on gere le cas du media
        if(!empty($_FILES['fichier_page']['name']))
          {
          //on calcule l'extension du fichier uploadé
          $extension=fichier_type($_FILES['fichier_page']['name']);

          //on traite le cas du fichier uploadé : vérification de l'extension de type image
          if($extension=="jpg" || $extension=="png" || $extension=="gif")
            {
            //on calcule les 3 chemins pour les 3 images qui vont être generées
            $chemin_b="../medias/media" . $_GET['id_page'] . "_b." . $extension;
            $chemin_m="../medias/media" . $_GET['id_page'] . "_m." . $extension;
            $chemin_s="../medias/media" . $_GET['id_page'] . "_s." . $extension;
            if(is_uploaded_file($_FILES['fichier_page']['tmp_name']))
            // tmp_name correspond au nom temporaire donné au fichier
            //lors de sa copie sur le serveur
              {
              if(copy($_FILES['fichier_page']['tmp_name'], $chemin_b))
                {
                //on calcule la taille de l'image d'origine uploadée
                $size=GetImageSize($chemin_b);
                $largeur=$size[0];
                $hauteur=$size[1];
                //on recherche le format de l'image (portrait / paysage / carré)
                $rapport=$largeur/$hauteur;

                if($largeur>$taille_m)
                  {
                  //nouvelle largeur
                  $x=$taille_m;
                  }
                else
                  {
                  //nouvelle largeur
                  $x=$largeur;
                  }
                //nouvelle hauteur
                $y=$x/$rapport;

                //on génère la miniature "m" du fichier d'origine
                redimage($chemin_b,$chemin_m,$x,$y,$quality);

                //on génère la miniature "s" du fichier d'origine
                $y=$taille_s/$rapport;
                redimage($chemin_b,$chemin_s,$taille_s,$y,$quality);

                //on met à jour l'enregistrement dans la table pages
                $requete2="UPDATE pages SET fichier_page='" . $chemin_b . "'
                        WHERE id_page='". $_GET['id_page'] . "'";
                $resultat2=mysqli_query($connexion,$requete2);
                }
              }
            }
          else
            {
            $validation="<p class=\"pas_ok\">Seules les extensions jpg, png et gif sont autorisées</p>";
            }
          }


        $validation="<p class=\"ok\">La page ".$_GET['id_page']."a bien été modifiée</p>";
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
$tableau=afficher_pages();
?>
