<html>

<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="login.css">
</head>

<body style="background-image: url('../imagens/login_background.jpg');"> 
<?php

/* 
Coding style em .php :
    html start
    php
        funcoes
        main
    html end
*/

/*
|*******************|
|*      FUNCOES    *|
|*******************|
*/

// funcao erro q passa oq aconteceu por argumento
function erro($error_text) {
    printf("
    <div class='center'>
        <center>  
        <img src='../imagens/error.png' width=312 height=312> </img> 
        <h2> %s </h2> 
        <a href='formlogin.php'> <button class='button' type='button'> Voltar </button> </a>
        </center> 
    </div>
    </body> </html>
    ", $error_text);
    exit;
}

// guardar session com todos os dados basicamente
function guardar_dados_sessao($result) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $_SESSION["codprof"]        = $row["codprof"];
    $_SESSION["nome"]           = $row["nome"];
    $_SESSION["cc"]             = $row["cc"];
    $_SESSION["datanasc"]       = $row["datanasc"];
    $_SESSION["nacionalidade"]  = $row["nacionalidade"];
    $_SESSION["telemovel"]      = $row["telemovel"];
    $_SESSION["email"]          = $row["email"];
    $_SESSION["codcartao"]      = $row["codcartao"]; // mesma coisa que codprof!
    $_SESSION["status"]         = $row["status"];
    $_SESSION["saldo"]          = $row["saldo"];
    $_SESSION["img"]            = $row["img"];

}


/*
|****************|
|*      MAIN    *|
|****************|
*/

// verificar se temos sessao, se sim, bazar
session_start();
if (isset($_SESSION["codprof"])) {
    header("Location: ../index.php");
    exit;
}

// verificar se o post ja foi enviado 
if (!isset($_POST["login_post"]))
    erro("Oops! Algo de estranho aconteceu :(");

// conectar à base de dados
include("../connect_db.php");

// n processo e cartao cid
$np = $_POST["np"];
$cc = $_POST["cc"];

// nao falhar na query pls xd
$query = "SELECT * FROM professor INNER JOIN cartao ON codprof = codcartao WHERE cc = $cc AND codprof = $np";
//$query = "SELECT * FROM aluno INNER JOIN cartao ON codaluno = codcartao WHERE cc = $cc AND codaluno = $np";
$result = mysqli_query($con, $query);
$valido = mysqli_num_rows($result);

// se usuario nao existir
if (!$valido) {
    mysqli_close($con);
    erro("Dados incorretos!");
}

// guardar sessao
guardar_dados_sessao($result);
mysqli_free_result($result);

// se o usuario existir, damos welcome e abrimos-lhe outra pagina (welcome, gif, sleep, ...?)
// NAO ESQUECER, SE FOR ADMIN, MANDA-LO PARA A PAGINA DO ADMIN E NAO PARA A PAGINA DO STOR!
// OU SEJA, IR BUSCAR 'STATUS' DO CLIENT E VER SE É ADMIN OU PROF
// if (status == 'admin)
    // header("Location: ../admin_db/menu.php");
// else
header("Location: ../index.php");
exit;

?> 
</body>
</html>