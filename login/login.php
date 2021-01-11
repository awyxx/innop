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

// dados incorretos
function login_incorreto() {
    printf("
    <div class='center'>
        <center>  
        <img src='../imagens/error.png' width=312 height=312> </img> 
        <h2> Dados incorretos! </h2> 
        <a href='formlogin.php'> <button class='button' type='button'> Tentar novamente </button> </a>
        </center> 
    </div>
    </body> </html>
    ");
    exit;
}

// algo de estranho aconteceu
function estranho() {
    printf("
    <div class='center'>
        <center>  
        <img src='../imagens/error.png' width=312 height=312> </img> 
        <h2> Oops! Algo de estranho aconteceu :( </h2> 
        <a href='formlogin.php'> <button class='button' type='button'> Voltar </button> </a>
        </center> 
    </div>
    </body> </html>
    ");
    exit;
}

// guardar session com todos os dados basicamente
function guardar_dados($result) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    session_start();

    $_SESSION["codprof"]        = $row["codprof"];
    $_SESSION["nome"]           = $row["nome"];
    $_SESSION["cc"]             = $row["cc"];
    $_SESSION["datanasc"]       = $row["datanasc"];
    $_SESSION["nacionalidade"]  = $row["nacionalidade"];
    $_SESSION["telemovel"]      = $row["telemovel"];
    $_SESSION["email"]          = $row["email"];
}


/*
|****************|
|*      MAIN    *|
|****************|
*/

// verificar se o post ja foi enviado ou se temos sessao...
if (!isset($_POST["login_post"]) || !isset($_SESSION["codprof"]))
    estranho();

// conectar a base de dados
if (!($con = mysqli_connect("localhost", "root", "", "innoplus"))) {
    printf("Conexão falhada! %s : %s", mysqli_connect_errno(), mysqli_connect_error());
    estranho();
}

// n processo e cartao cid
$np = $_POST["np"];
$cc = $_POST["cc"];

// nao falhar na query pls xd
$query  = "SELECT * FROM professor WHERE cc = $cc AND codprof = $np"; 
$result = mysqli_query($con, $query);
$valido = mysqli_num_rows($result);

// se usuario nao existir
if (!$valido) {
    mysqli_close($con);
    login_incorreto();    
}

// guardar sessao
guardar_dados($result);
mysqli_free_result($result);
// se o usuario existir, damos welcome e abrimos-lhe outra pagina
// welcome
//header("Location: ../index.php");
exit;

?> 
</body>
</html>
