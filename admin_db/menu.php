<?php session_start(); ?>

<html>

<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="menu.css">
    <style>
    table, th, td {
        border: 1px solid black;
    }
    </style>
    <script>

    /* java script q vai buscar os valores à linha da tabela que queremos modificar ou remover
    e mandar para input boxes + submit button pra modificar ou remover e depois receber 
    por outro post onde fazemos a query mysql */
    function buscar_valores_linha(element) {
        var id = parseInt(element.id);
        var rows = document.getElementsByTagName("table")[0].rows;
        var last = rows[id]; // linha anterior
        var first = rows[0]; // primeira linha

        // contar campos
        var n = 0;
        for (var i = 0, col; col = last.cells[i]; i++)  
            n++;

        // ir buscar o campo principal e os campos no geral
        var campos = [n-1];
        var campo_principal = first.cells[0].innerHTML;
        for (var i = 0, col; col = first.cells[i]; i++) {
            if (i == n-1)   break; 
            campos[i] = col.innerHTML
        }

        // mudar para a 2 linha (onde começa os valores pq a linha 1 é os campos)
        last = rows[id+1];
        // buscar valores da linha
        var valores = [n-1];
        for (var i = 0, col; col = last.cells[i]; i++) {
            if (i == n-1)   break; 
            valores[i] = col.innerHTML;
        }
        
        document.write("<form id='formprincipal' method='post'>");
        document.body.style.backgroundImage = "url('../imagens/login_background.jpg')";
        document.body.style.font
        document.write("<center>");
        document.write("<img style='height:20%' src='../imagens/innoplus_icon_branco.png'>");
        document.write("<br><br>");
        document.write("<fieldset style=' background: rgba(20, 55, 55, .5); width:40%; text-align:justify; font-family: Roboto, sans-serif;'>");
            document.write("<input name='oldcodval' readonly type='hidden' value='",campo_principal,"'>");
            document.write("<input name='oldcod' readonly type='hidden' value='", valores[0],"'>");
            // modificar
            if (element.value == "Modificar") {
                document.write("<img src='../imagens/lapiz.png' width=50% style='float:right'>");
                document.write("<table style='color:white; font-size:130%;'>");
                for (var i = 0; i < n-1; i++)
                {
                    document.write("<tr>");
                    document.write("<td>",campos[i],"</td> <td><input style='font-size:80%' name='tb",i,"' type='text' value='",valores[i],"'> </td>");
                    document.write("</tr>");
                }

                document.write("<tr> <td colspan='2'> <hr style='border:10px solid rgba(20, 55, 55, .0)'> </td> </tr>");

                document.write("<tr>");
                document.write("<td colspan='2'> <input style='height:100%;width:100%;background-color:rgb(255,255,102);font-size:90%' name='concluido_post' type='submit' value='Modificar'> </td>");
                document.write("</tr>");
                document.write("<tr>");
                document.write("<td colspan='2'> <a href='menu.php'> <input style='height:100%;width:100%;background-color:White;font-size:90%' name='Insert_Ad' type='button' value='Voltar'> </a> </td> "); // transformar em botao
                document.write("</tr>");
            }
                                                                                             
            // apagar
            else if (element.value == "Remover") {
                document.write("<img src='../imagens/apagar.png' width=50% style='float:right'>");
                document.write("<table style='color:white; font-size:130%;'>");
                for (var i = 0; i < n-1; i++)
                {
                    document.write("<tr>");
                    document.write("<td>",campos[i], "</td> <td> <input disabled type='text' value='",valores[i],"'> </td>");
                    document.write("</tr>");
                }

                document.write("<tr> <td colspan='2'> <hr style='border:10px solid rgba(20, 55, 55, .0)'> </td> </tr>");

                document.write("<tr>");
                document.write("<td colspan='2'> <input style='height:100%;width:100%;background-color:rgb(255,102,102);font-size:90%' name='concluido_post' type='submit' value='Apagar'> </td>");
                document.write("</tr>");
                document.write("<tr>");
                document.write("<td colspan='2'> <a href='menu.php'> <input style='height:100%;width:100%;background-color:White;font-size:90%' name='Insert_Ad' type='button' value='Voltar'> </a> </td> "); // transformar em botao
                document.write("</tr>");
            }
        document.write("</table>");
        document.write("</fieldset>")
        document.write("</center>");
        document.write("</form>");
        
    }

    </script>
</head>

<body>

    <center>
        <img class="logo" src="../imagens/innoplus_icon_branco.png">
    </center>


<?php


/********* */
/* FUNCOES */
/********* */ 

// vai printar os campos (os 'headers') da tabela, formatados para a tabela html!
function tabela_campos($tabela, $con) {
    // array com os campos! (titulos)
    $campos = campos_tabela($tabela, $con);
    printf("<tr>");
        for($i = 0; $i < sizeof($campos); $i++)
            printf("<th> %s </th>", $campos[$i]);
        printf("<th> Ação </th>");
    printf("</tr>");
}

function contar_campos($tabela) {
    $query = "SHOW COLUMNS FROM $tabela";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    printf("YA BRO ROWS : %d", $rows);
}

// vai printar os registos (as 'linhas') da tabela, formatados para a tabela html!
function tabela_registos($tabela, $db) {
    // numero de campos por tabela (pa sabermos quantas colunas vamos ter !)
    $ncampos = array("aluno"=>num_campos_tabela("aluno", $db),          "cartao"=>num_campos_tabela("cartao", $db), 
                    "disciplina"=>num_campos_tabela("disciplina", $db), "dt"=>num_campos_tabela("dt", $db), 
                    "ee"=>num_campos_tabela("ee", $db),                 "faltas"=>num_campos_tabela("faltas", $db), 
                    "horarios"=>num_campos_tabela("horarios", $db),     "notas"=>num_campos_tabela("notas", $db), 
                    "professor"=>num_campos_tabela("professor", $db),   "sumarios"=>num_campos_tabela("sumarios", $db), 
                    "turma"=>num_campos_tabela("turma", $db),           "turmas"=>num_campos_tabela("turmas", $db));

    // mostrar todos os registos da tabela, formatado pra tabela html
    $query = "SELECT * FROM ". $tabela;
    $result = mysqli_query($db, $query);
    $x = 0;
    while ($row1 = mysqli_fetch_row($result)) {
        printf("<tr>");
        for ($i = 0; $i < $ncampos[$tabela]; $i++)
            printf("<td> %s </td> ", $row1[$i]);
        menu_acao($x);
        $x++;
        printf("</tr>");
    }
}

// coluna acao com select menu modificar e remover 
function menu_acao($x) {
    printf("<td class='centrar'>");
    printf("
    <form class='formmod' method='post'>
        <select class='modselect_css' id='%d' onchange='buscar_valores_linha(this)'>
            <option disabled hidden selected>  </option> 
            <option> Modificar </option> 
            <option> Remover </option> 
        </select>
    </form> 
    ", $x);
    printf("</td>");
}

// da return ao numero de campos de uma tabela 
function num_campos_tabela($tabela, $con) {
    $query = "SHOW COLUMNS FROM $tabela";
    $result = mysqli_query($con, $query);
    if (!$result)    printf("%s", mysqli_error($con));
    else {
        $x = 0;
        while ($linha = mysqli_fetch_row($result)) {
            if ($linha[0] == "img")    continue;
            $x++;
        }
        return $x;
    }
}

// da return de um array com os campos
function campos_tabela($tabela, $con) {
    $query = "SHOW COLUMNS FROM $tabela";
    $result = mysqli_query($con, $query);
    if (!$result)    printf("%s", mysqli_error($con));
    else {
        $campos = array();
        $x = 0;
        while ($linha = mysqli_fetch_row($result)) {
            if ($linha[0] == "img")    continue;
            $campos[$x] = $linha[0];
            $x++;
        }
    }
    return $campos;
}

function exec_query($con, $query) {
    $result = mysqli_query($con, $query);
    if (!$result) {
        printf("<center>");
        printf("<h1> Registo Não Inserido! </h1>");
        printf("<img src='../imagens/cc.png' height='30%%'>");
        printf("<p style='color:white'> Erro: ( %s )", mysqli_error($con),"<p> <br>");
        printf("</center>");
        return 0;
    } else {
        return 1;
    }
}

function inserir_dados($con, $nometabela, $dados) {

    // numero da tabela no vetor query
    $tab = array("aluno"=>0, "cartao"=>1, "disciplina"=>2, "dt"=>3, "ee"=>4, "faltas"=>5, 
                    "horarios"=>6, "notas"=>7, "professor"=>8, "sumarios"=>9, "turma"=>10, "turmas"=>11);

    $ntabela = $tab[$nometabela];

    $query = array(
        "INSERT INTO `aluno`(`codaluno`, `codee`, `nome`, `cc`, `datanasc`, `nacionalidade`, `morada`, `telemovel`, `email`) VALUES ($dados[0],$dados[1],'$dados[2]',$dados[3],'$dados[4]','$dados[5]','$dados[6]',$dados[7],'$dados[8]');",
        "INSERT INTO `cartao`(`codcartao`,`status`,`saldo`,`img`) VALUES ($dados[0],'$dados[1]','$dados[2]',null);",
        "INSERT INTO `disciplina`(`coddisciplina`, `nome`, `ano`, `codprof`) VALUES ($dados[0],'$dados[1]',$dados[2],$dados[3]);",
        "INSERT INTO `dt`(`coddt`, `codprof`, `codturma`, `numaluno`) VALUES ($dados[0],$dados[1],$dados[2],$dados[3]);",
        "INSERT INTO `ee`(`codee`, `nome`, `parentesco`, `morada`, `telemovel`, `email`) VALUES ($dados[0],'$dados[1]','$dados[2]','$dados[3]',$dados[4],'$dados[5]');",
        "INSERT INTO `faltas`(`codfalta`, `codaluno`, `datafalta`, `diasemana`, `idxhora`, `coddisciplina`, `tipofalta`) VALUES ($dados[0],$dados[1],$dados[2],$dados[3],$dados[4],$dados[5],'$dados[6]');",
        "INSERT INTO `horarios`(`codhorario`, `hora`, `seg`, `ter`, `qua`, `qui`, `sex`) VALUES ($dados[0],$dados[1],'$dados[2]','$dados[3]','$dados[4]','$dados[5]','$dados[6]');",
        "INSERT INTO `notas`(`codaluno`, `coddisciplina`, `nota`, `periodo`, `anoescolar`) VALUES ($dados[0],$dados[1],$dados[2],$dados[3], $dados[4]);",
        "INSERT INTO `professor`(`codprof`,`codhorario`, `nome`, `cc`, `datanasc`, `nacionalidade`, `telemovel`, `email`) VALUES ($dados[0],'$dados[1]',$dados[2],'$dados[3]','$dados[4]',$dados[5],'$dados[6]',$dados[7]);",
        "INSERT INTO `sumarios`(`codprof`, `codturma`, `licao`, `sumario`, `hora`, `diasemana`, `coddisciplina`) VALUES ($dados[0],$dados[1],$dados[2],'$dados[3]',$dados[4],'$dados[5]','$dados[6]');",
        "INSERT INTO `turma`(`codaluno`, `codhorario`, `codturma`, `numaluno`, `cartaluno`) VALUES ($dados[0],$dados[1],$dados[2],$dados[3],$dados[4]);",
        "INSERT INTO `turmas`(`codturma`, `sigla`, `ano`, `curso`, `coddt`) VALUES ($dados[0],'$dados[1]',$dados[2],'$dados[3]', $dados[4]);",
    );

    if (exec_query($con, $query[$ntabela])) {
        return 1;
    } else {
        return 0;
    }
}

/****** */
/* MAIN */
/****** */ 

include("../connect_db.php");

// form + select menu com as tabelas!
printf("<center>");                                                             // tag center
printf("<br>");
printf('<a style="margin-bottom:20%%" href="../logout.php"><img src="../imagens/logout.png" width="3%%" height="5%%" legend="Encerrar Sessão"></a>');
printf("<br>");
printf("<br>");
printf("<form class='form_css' method='post'>");
    printf("<select class='select_css' name='tabela' onchange='this.form.submit()'>");
        $query  = "SHOW TABLES FROM innoplus";
        $result = mysqli_query($con, $query);
        printf("<option selected disabled hidden> Escolher... </option>");
        while ($row = mysqli_fetch_row($result))
            printf("<option> %s </option>", $row[0]);
    printf("</select>");    
printf("</form>");
printf("</center>");                                                            // tag center

/******* */
/* POSTS */
/******* */ 

// ao selecionar uma tabela no select menu
if (isset($_POST["tabela"])) {
    if ($_POST["tabela"] == "") {
        printf("Erro!");
        exit;
    }

    $tabela_ativa = $_POST["tabela"];
    $_SESSION["tabela"] = $tabela_ativa;
    printf("<h1> Tabela %s </h1>", $tabela_ativa);

    printf("<table class='tabela' name='table1'>");
        tabela_campos($tabela_ativa, $con);
        tabela_registos($tabela_ativa, $con);
        printf("<tr class='limite'> <td colspan='10'> <form method='post'> 
        <input class='inseriri_but' name='inserir_post' type='submit' value='Inserir Registo'>
                </form> </td> </tr>");
    printf("</table>");
}

// modificar ou alterar
if (isset($_POST["concluido_post"])) {
    $cod        = $_POST["oldcod"];
    $campo      = $_POST["oldcodval"];
    $tabela_    = $_SESSION["tabela"];

    // apagar
    if ($_POST["concluido_post"] == "Apagar") {
        if (exec_query($con, "DELETE FROM $tabela_ WHERE $campo = $cod")) {
            printf("<center>");
            printf("<h1> Registo Apagado com sucesso! </h1>");
            printf("<img src='../imagens/ss.png' height='30%%'>");
            printf("</center>");
        }
    } 
    // modificar
    else if ($_POST["concluido_post"] == "Modificar") {
        $tabela     = $_SESSION["tabela"];
        $cod        = $_POST["oldcod"];
        $campo      = $_POST["oldcodval"];

        // array com os dados
        $dados = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");

        $erro = 0;
        for ($x = 0; $x < num_campos_tabela($tabela, $con); $x++) {
            $dados[$x] = $_POST["tb".$x];
            if ($dados[$x] == "" || $dados[$x] == " ") {
                $erro = 1;
                break;
            }
        }

        if ($erro) {
            printf("<center>");
            printf("<img src='../imagens/cc.png' height='30%%'>");
            printf("<p style='color:white'> Erro! Valores nulos. <p>");
            printf("<a href='menu.php'> <button class='button' type='button'> Voltar </button> </a>");
            printf("</center>");
            exit;
        }

        // update ta a dar erro, dar fix assim:
        // damos remove e depois inserimos dnv
        // mas em tabelas q a chave primaria se repete n funciona :(
        if ($tabela != "horarios" && $tabela != "turma" && $tabela != "sumarios" && $tabela != "notas") {
            if (exec_query($con, "DELETE FROM $tabela WHERE $campo = $cod")) {
                // inserir dados
                if (inserir_dados($con, $tabela, $dados)) {
                    printf("<center>");
                    printf("<h1> Registo Modificado com sucesso! </h1>");
                    printf("<img src='../imagens/ss.png' height='30%%'>");
                    printf("</center>");
                }
            }
        } else {
            // fazer o update (optimizar??? how)
            if ($tabela == "aluno")
                $query_campos =  "`codaluno`=$dados[0],`codee`=$dados[1],`nome`='$dados[2]',`cc`=$dados[3],`datanasc`='$dados[4]',`nacionalidade`='$dados[5]',`morada`='$dados[6]',`telemovel`=$dados[7],`email`='$dados[8]'";
            else if ($tabela == "cartao")
                $query_campos = "`codcartao`=$dados[0],`status`='$dados[1]',`saldo`=$dados[2]"; 
            else if ($tabela == "disciplina")
                $query_campos = "`coddisciplina`=$dados[0],`nome`='$dados[1]',`ano`=$dados[2],`codprof`='$dados[3]'";
            else if ($tabela == "dt")
                $query_campos = "`coddt`=$dados[0],`codprof`=$dados[1]";
            else if ($tabela == "ee")
                $query_campos = "`codee`=$dados[0],`nome`='$dados[1]',`parentesco`='$dados[2]',`morada`='$dados[3]',`telemovel`=$dados[4],`email`='$dados[5]'" ;
            else if ($tabela == "faltas")
                $query_campos = "`codfalta`=$dados[0],`codaluno`=$dados[1],`datafalta`='$dados[2],`diasemana`='$dados[3]',`idxhora`=$dados[4],`coddisciplina`=$dados[5],`tipofalta`='$dados[6]";
            else if ($tabela == "horarios")
                $query_campos = "`codhorario`=$dados[0],`hora`=$dados[1],`seg`='$dados[2]',`ter`='$dados[3]',`qua`='$dados[4]',`qui`='$dados[5]',`sex`='$dados[6]'";
            else if ($tabela == "notas")
                $query_campos = "`codaluno`=$dados[0],`coddisciplina`=$dados[1],`nota`=$dados[2],`periodo`=$dados[3],`anoescolar`=$dados[4]";
            else if ($tabela == "professor")
                $query_campos = "`codprof`=$dados[0],`nome`='$dados[1]',`cc`=$dados[2],`datanasc`='$dados[3]',`nacionalidade`='$dados[4]',`telemovel`=$dados[5],`email`='$dados[6]'";
            else if ($tabela == "sumarios")
                $query_campos = "`codprof`=$dados[0],`codturma`=$dados[1],`licao`=$dados[2],`sumario`='$dados[3]',`hora`=$dados[4],`diasemana`='$dados[5]',`coddisciplina`=$dados[6]";
            else if ($tabela == "turma")
                $query_campos = "`codaluno`=$dados[0],`codhorario`=$dados[1],`codturma`=$dados[2],`numaluno`=$dados[3],`cartaluno`=$dados[4]";
            else if ($tabela == "turmas")
                $query_campos = "`codturma`=$dados[0],`sigla`='$dados[1]',`ano`=$dados[2],`curso`='$dados[3]',`coddt`=$dados[4]";

            $query = "UPDATE $tabela SET $query_campos WHERE $campo = $cod ;";
            //$query = "REPLACE INTO $tabela SET $query_campos"; 
            $result = mysqli_query($con, $query);
            if (!$result) {
                printf("<center> <h1> ERRO MYSQL: %s </h1> </center>", mysqli_error($con));
            }
            else {
                printf("<center>");
                printf("<h1> Registo Modificado com sucesso! </h1>");
                printf("<img src='../imagens/ss.png' height='30%%'>");
                printf("</center>");
            }
        }
    }
    printf("<center>");
    printf("<a href='menu.php'> <button class='button' type='button'> Voltar </button> </a>");
    printf("</center>");
}

// pedir campos da tabela para depois mandarmos pa outro post pa inserir
if (isset($_POST["inserir_post"])) {
    $query  = "SHOW COLUMNS FROM ".$_SESSION["tabela"];
    $result = mysqli_query($con, $query);
    $x = 0;
    printf("<h1> Inserir dados na tabela '%s' </h1>", $_SESSION["tabela"]);

        // Erros em printf? ..... (arranja se quiseres) - cortei o php

    
    printf("<form id='forminserir' method='post'>");                         // form inserir

    ?> 
    <center>
    <fieldset  style='background: rgba(20, 55, 55, .5); width:40%; text-align:justify; font-family: Roboto, sans-serif;'> 
    <img src='../imagens/inserir.png' width=50% style='float:right'>
    <table style='color:white; font-size:130%;border: 0px;'>
    <?php

        while ($row1 = mysqli_fetch_row($result)) {
            if ($row1[0] == 'img')  continue;
            {
                printf("<tr>");
                printf("<td style='border: 0px;'> %s: </td> <td style='border: 0px;'> <input name='tb%d' type='text'> </td>", $row1[0], $x);
                printf("</tr>");
            }
            $x++;
        }
    
    ?>
    
    <tr> <td colspan='2' style='border: 0px;'> <hr class='separador' style='border:10px solid rgba(20, 55, 55, .0)'> </td> </tr>

    <tr>
    <td colspan='2' style='border: 0px;'> <input name='ins_post_mysql' type='submit' value='Inserir' style='height:100%;width:100%;background-color:rgb(102,255,102);font-size:90%';> </td>
    </tr>
    <tr>
    <td colspan='2' style='border: 0px;'> <a href='menu.php'> <input style='height:100%;width:100%;background-color:White;font-size:90%' name='Insert_Ad' type='button' value='Voltar'> </a> </td>
    </tr>


    <?php
    printf("</table>");
    printf("</form>");
    printf("</fieldset>");
    printf("</center>");
}

// post recebe dados das input boxes e manda pa base de dados
if (isset($_POST["ins_post_mysql"])) {

    // array com os dados
    $dados = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");

    $erro = 0;
    for ($x = 0; $x < num_campos_tabela($_SESSION["tabela"], $con); $x++) {
        $dados[$x] = $_POST["tb".$x];
        if ($dados[$x] == "" || $dados[$x] == " ") {
            $erro = 1;
            break;
        }
    }

    if ($erro) {
        printf("<center>");
        printf("<img src='../imagens/cc.png' height='30%%'>");
        printf("<p style='color:white'> Erro! Valores nulos. </p> <br>");
        printf("<a href='menu.php'> <button class='button' type='button'> Voltar </button> </a>");
        printf("</center>");
        exit;
    }

    inserir_dados($con, $_SESSION["tabela"], $dados);
    printf("<center>");
    printf("<h1> Registo introduzido com sucesso! </h1>");
    printf("<img src='../imagens/ss.png' height='30%%'>");
    printf("<a href='menu.php'> <button class='button' type='button'> Voltar </button> </a>");
    printf("</center>");
}

?>

</body>
</html>