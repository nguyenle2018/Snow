<?php
//permet d'autoriser l'usage des variables de session
session_start();

require_once("../outils/fonctions.php");

//si qq appuie sur le bouton ENTRER du formulaire de connexion
if(isset($_POST['submit']))
	{
	if(!empty($_POST['captcha']) && !empty($_POST['login_compte']) && !empty($_POST['pass_compte']))
		{
		if(isset($_SESSION['captcha']) && $_SESSION['captcha']==$_POST['captcha'])
			{
			login($_POST['login_compte'],$_POST['pass_compte']);
			}
		}
	}

include('login.html');
?>
