<?php
//redirection 301 au lieu de 302
header("status:301 Moved Permanently",false,301);

header("Location:content/front.php");


?>
