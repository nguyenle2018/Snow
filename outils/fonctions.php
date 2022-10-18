<?php
//===============================
// la fonction connecter() permet de choisir une
// base de données et de s'y connecter.

function connexion()
	{
	require_once("connect.php");
	//si numéro de port
	//$connexion = mysqli_connect(SERVEUR,LOGIN,PASSE,BASE,PORT) or die("Error " . mysqli_error($connexion));
	//si pas de numéro de port
	$connexion = mysqli_connect(SERVEUR,LOGIN,PASSE,BASE) or die("Error " . mysqli_error($connexion));

	return $connexion;
	}

//================================
function security($chaine){
	$connexion=connexion();
	$security=addcslashes(mysqli_real_escape_string($connexion,$chaine), "%_");
	mysqli_close($connexion);
	return $security;
}

//=================================
function login($login,$pass)
	{
	$connexion=connexion();
	//on va voir dans la table comptes si le pelerin existe
	$requete="SELECT * FROM comptes WHERE login_compte='".$login."' AND pass_compte=SHA1('".$pass."')";
	$resultat=mysqli_query($connexion, $requete);
	$ligne=mysqli_fetch_object($resultat);
	$nb_ligne=mysqli_num_rows($resultat);
	if($nb_ligne>0)
		{
		//on a l'autorisation
		//on stocke de façon permanente les données relatives à la personne qui se connecte
		$_SESSION['id_compte']=$ligne->id_compte;
		$_SESSION['nom_compte']=$ligne->nom_compte;
		$_SESSION['prenom_compte']=$ligne->prenom_compte;
		header("location:../admin/admin.php");
		}
	else
		{
		//pas d'autorisation
		header("location:../log/login.php");
		}

	mysqli_close($connexion);
	}

// ====détecter l'extension du fichier================
function fichier_type($uploadedFile)
	{
	$tabType = explode(".", $uploadedFile);
	$nb=sizeof($tabType)-1;
	$typeFichier=$tabType[$nb];
	 if($typeFichier == "jpeg")
	   {
	   $typeFichier = "jpg";
	   }
	$extension=strtolower($typeFichier);
	return $extension;
	}

//=========redimension des images uploadées===================================
function redimage($img_src,$img_dest,$dst_w,$dst_h,$quality)
	{
	if(!isset($quality))
		{
		$quality=100;
		}
	   $extension=fichier_type($img_src);

	   // Lit les dimensions de l'image
	   $size = @GetImageSize($img_src);
	   $src_w = $size[0];
	   $src_h = $size[1];
	   // Crée une image vierge aux bonnes dimensions   truecolor
	   $dst_im = @ImageCreatetruecolor($dst_w,$dst_h);
	   imagealphablending($dst_im, false);
	   imagesavealpha($dst_im, true);

	   // Copie dedans l'image initiale redimensionnée

	   if($extension=="jpg")
	     {
	     $src_im = @ImageCreateFromJpeg($img_src);
	     imagecopyresampled($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

	     // Sauve la nouvelle image
	     @ImageJpeg($dst_im,$img_dest,$quality);
	     }
	   if($extension=="png")
	     {
	     $src_im = @ImageCreateFromPng($img_src);
	     imagecopyresampled($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

	     // Sauve la nouvelle image
	     @ImagePng($dst_im,$img_dest,0);
	     }
	   if($extension=="gif")
	     {
	     $src_im = @ImageCreateFromGif($img_src);
	     imagecopyresampled($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);

	     // Sauve la nouvelle image
	     @ImagePng($dst_im,$img_dest,0);
	     }

	   // Détruis les tampons
	   @ImageDestroy($dst_im);
	   @ImageDestroy($src_im);
	}

//===========================
function afficher_contacts()
	{
	$connexion=connexion();

	//on formule la requete de selection
	$requete="SELECT * FROM contacts ORDER BY date_contact DESC";
	//on exécute la requete
	$resultat=mysqli_query($connexion, $requete);
	$nb_ligne=mysqli_num_rows($resultat);
	$tableau="<h2>Il y a " . $nb_ligne . " contact(s)</h2>";
	$tableau.="<table class=\"tab_result\">\n";
	while($ligne=mysqli_fetch_object($resultat))
		{
		$tableau.="<tr>\n";
		$tableau.="<td>".$ligne->date_contact."</td>\n";
		$tableau.="<td>".strtoupper($ligne->nom_contact)." ".$ligne->prenom_contact."</td>\n";
		$tableau.="<td><a href=\"mailto:".$ligne->email_contact."\">".$ligne->email_contact."</a></td>\n";
		$tableau.="<td><a href=\"admin.php?action=contacts&cas=confirmer_suppression&id_contact=".$ligne->id_contact."\"><span class=\"dashicons dashicons-trash\"></span></a></td>\n";
		$tableau.="</tr>\n";

		$tableau.="<tr>\n";
		$tableau.="<td colspan=\"4\">";
		$tableau.="<details>";
		$tableau.="<summary>Lire le message</summary>";
		$tableau.=$ligne->message_contact;
		$tableau.="</details>";
		$tableau.="</td>\n";
		$tableau.="</tr>\n";
		}
	$tableau.="</table>\n";

	mysqli_close($connexion);

	//permet de renvoyer la variable calculée
	return $tableau;
	}

//==================================================
function afficher_comptes()
	{
	$connexion=connexion();

	//on formule la requete de selection
	$requete="SELECT * FROM comptes ORDER BY nom_compte ASC";
	//on exécute la requete
	$resultat=mysqli_query($connexion, $requete);
	$nb_ligne=mysqli_num_rows($resultat);
	$tableau="<h2>Il y a " . $nb_ligne . " comptes(s)</h2>";
	$tableau.="<table class=\"tab_result\">\n";
	$i=1;
	while($ligne=mysqli_fetch_object($resultat))
		{
		$tableau.="<tr>\n";
		$tableau.="<td>".$i."</td>\n";
		$tableau.="<td>".strtoupper($ligne->nom_compte)."</td>\n";
		$tableau.="<td>".$ligne->prenom_compte."</td>\n";
		$tableau.="<td>".$ligne->login_compte."</td>\n";
		$tableau.="<td>";
		$tableau.="<a href=\"admin.php?action=comptes&cas=confirmer_modification&id_compte=".$ligne->id_compte."&i=".$i."\">
		<span class=\"dashicons dashicons-edit\"></span></a>";
		$tableau.="&nbsp;&nbsp;&nbsp;";
		$tableau.="<a href=\"admin.php?action=comptes&cas=confirmer_suppression&id_compte=".$ligne->id_compte."&i=".$i."\">
		<span class=\"dashicons dashicons-trash\"></span></a>";
		$tableau.="</td>\n";
		$tableau.="</tr>\n";
		$i++;
		}
	$tableau.="</table>\n";

	mysqli_close($connexion);

	//permet de renvoyer la variable calculée
	return $tableau;
	}
//====================================================
function afficher_pages()
	{
	$connexion=connexion();

	//on formule la requete de selection
	//méthode 1
	//$requete="SELECT p.*, r.* FROM pages AS p, rubriques AS r
	//					WHERE p.id_rubrique=r.id_rubrique ORDER BY p.date_page DESC";

	//méthode 2 (new)
	$requete="SELECT p.*, r.*, c.* FROM pages AS p
						INNER JOIN rubriques AS r ON p.id_rubrique=r.id_rubrique
						INNER JOIN comptes AS c ON p.id_compte=c.id_compte
						ORDER BY r.nom_rubrique, p.date_page DESC";

	//on exécute la requete
	$resultat=mysqli_query($connexion, $requete);
	$nb_ligne=mysqli_num_rows($resultat);
	$tableau="<h2>Il y a " . $nb_ligne . " page(s)</h2>";
	$tableau.="<table class=\"tab_result\">\n";
	$i=0;

	$nom_rubrique=array();
	while($ligne=mysqli_fetch_object($resultat))
		{
		$nom_rubrique[$i]=$ligne->id_rubrique;
		//si premier tour de boucle
		if($i==0 || ($i>0 && $nom_rubrique[$i]!=$nom_rubrique[$i-1]))
			{
			$tableau.="<tr>\n";
			$tableau.="<th colspan=\"5\">".$ligne->nom_rubrique."</th>\n";
			$tableau.="</tr>\n";
			}

		$tableau.="<tr>\n";
		$tableau.="<td>".$ligne->date_page."</td>\n";
		$tableau.="<td>".stripslashes($ligne->titre_page)."</td>\n";
		//si il y a une image associée à la page
		if(!empty($ligne->fichier_page))
			{
			$apercu="<a href=\"" . $ligne->fichier_page . "\" target=\"_blank\"><img class=\"apercu\" src=\"" . str_replace("_b","_s",$ligne->fichier_page) ."\" alt=\"" . $ligne->titre_page . "\" /></a>";
			$apercu.="<a href=\"admin.php?action=pages&cas=supprimer_image&id_page=".$ligne->id_page."&extension=". fichier_type($ligne->fichier_page) . "\"><span class=\"dashicons dashicons-no-alt\"></span></a>";
			}
		else
			{
			$apercu="";
			}
		$tableau.="<td class=\"center\">" . $apercu . "</td>\n";
		$tableau.="<td>".$ligne->prenom_compte . " " . $ligne->nom_compte ."</td>\n";
		$tableau.="<td class=\"center\">";
		$tableau.="<a href=\"admin.php?action=pages&cas=confirmer_modification&id_page=".$ligne->id_page."\">
		<span class=\"dashicons dashicons-edit\"></span></a>";
		$tableau.="&nbsp;&nbsp;&nbsp;";
		$tableau.="<a href=\"admin.php?action=pages&cas=confirmer_suppression&id_page=".$ligne->id_page."\">
		<span class=\"dashicons dashicons-trash\"></span></a>";
		$tableau.="</td>\n";
		$tableau.="</tr>\n";
		$i++;
		}
	$tableau.="</table>\n";

	mysqli_close($connexion);

	//permet de renvoyer la variable calculée
	return $tableau;
	}

	//==================================================
	function afficher_rubriques()
		{
		$connexion=connexion();

		//on formule la requete de selection
		$requete="SELECT * FROM rubriques ORDER BY nom_rubrique ASC";
		//on exécute la requete
		$resultat=mysqli_query($connexion, $requete);
		$nb_ligne=mysqli_num_rows($resultat);
		$tableau="<h2>Il y a " . $nb_ligne . " rubrique(s)</h2>";
		$tableau.="<table class=\"tab_result\">\n";
		while($ligne=mysqli_fetch_object($resultat))
			{
			$tableau.="<tr>\n";
			$tableau.="<td>".$ligne->nom_rubrique."</td>\n";
			$tableau.="<td><a href=\"" . $ligne->lien_rubrique . "\">".$ligne->lien_rubrique."</a></td>\n";
			$tableau.="<td>";
			$tableau.="<a href=\"admin.php?action=rubriques&cas=confirmer_modification&id_rubrique=".$ligne->id_rubrique."\">
			<span class=\"dashicons dashicons-edit\"></span></a>";
			$tableau.="&nbsp;&nbsp;&nbsp;";
			$tableau.="<a href=\"admin.php?action=rubriques&cas=confirmer_suppression&id_rubrique=".$ligne->id_rubrique."\">
			<span class=\"dashicons dashicons-trash\"></span></a>";
			$tableau.="</td>\n";
			$tableau.="</tr>\n";
			}
		$tableau.="</table>\n";

		mysqli_close($connexion);

		//permet de renvoyer la variable calculée
		return $tableau;
		}


?>
