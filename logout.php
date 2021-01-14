<?php

// remover dados da sessao e bazar 
session_start();
session_unset();
session_destroy(); 
//session_abort();
header("Location: login/formlogin.php")

?>