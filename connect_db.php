<?php

// conectar a base de dados
if (!($con = mysqli_connect("localhost", "root", "", "innoplus"))) {
    $erro_conexao = "Conexão falhada! <br> ".mysqli_connect_errno().": ".mysqli_connect_error();
    erro($erro_conexao);
}

// resolver problemas com caracteres estranhos (?)
mysqli_set_charset($con, 'utf8');

?>