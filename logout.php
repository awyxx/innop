<?php

// remover dados da sessao e bazar 
session_start();
session_unset();
session_destroy(); 

//header("Location: login/formlogin.php")

?>