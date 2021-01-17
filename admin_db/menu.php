<html>

<head>
    <style>
    table, th, td {
        border: 1px solid black;
    }
    </style>
</head>

<body>

<?php


/********* */
/* FUNCOES */
/********* */ 

// tornar CON em funcao php a parte!
if (!($con = mysqli_connect("localhost", "root", "", "innoplus"))) {
    $erro_conexao = "Conexão falhada! <br> ".mysqli_connect_errno().": ".mysqli_connect_error();
    erro($erro_conexao);
}

// vai printar os campos (os 'headers') da tabela, formatados para a tabela html!
function tabela_campos($tabela) {
    $campos = array();
    if ($tabela == "aluno")           $campos = array("codaluno", "codee", "nome", "cc", "datanasc", "nacionalidade", "morada", "telemovel", "email" ); 
    else if ($tabela == "cartao")     $campos = array("codcartao", "status", "saldo");
    else if ($tabela == "disciplina") $campos = array("coddisciplina", "nome", "ano"); 
    else if ($tabela == "dt")         $campos = array("coddt", "codprof", "codturma", "numaluno"); 
    else if ($tabela == "ee")         $campos = array("codee", "nome", "parentesco", "morada", "telemovel", "email"); 
    else if ($tabela == "faltas")     $campos = array("codfalta", "codaluno", "datafalta", "diasemana", "idxhora", "coddisciplina", "tipofalta");
    else if ($tabela == "horarios")   $campos = array("codhorario", "hora", "seg", "ter", "qua", "qui", "sex");
    else if ($tabela == "notas")      $campos = array("codaluno", "coddisciplina", "nota", "periodo", "anoescolar");
    else if ($tabela == "professor")  $campos = array("codprof", "nome", "cc", "datanasc", "nacionalidade", "telemovel", "email");
    else if ($tabela == "sumarios")   $campos = array("codprof", "codturma", "licao", "sumario", "hora", "diasemana");
    else if ($tabela == "turma")      $campos = array("codaluno", "codhorario", "codturma", "numaluno", "cartaluno");
    else if ($tabela == "turmas")     $campos = array("codturma", "sigla", "ano", "curso", "coddt");

    printf("<tr>");
    for($i = 0; $i < sizeof($campos); $i++)
        printf("<th> %s </th>", $campos[$i]);
    printf("</tr>");
}

// vai printar os registos (as 'linhas') da tabela, formatados para a tabela html!
function tabela_registos($tabela, $con) {
    // numero de campos por tabela
    $ncampos = array("aluno"=>9, "cartao"=>3, "disciplina"=>3, "dt"=>4, "ee"=>6, "faltas"=>7, "horarios"=>7, "notas"=>5, 
               "professor"=>7, "sumarios"=>6, "turma"=>5, "turmas"=>5);

    // mostrar todos os registos da tabela, formatado pra tabela html
    $query = "SELECT * FROM ". $tabela;
    $result = mysqli_query($con, $query);
    while ($row1 = mysqli_fetch_row($result)) {
        printf("<tr>");
        for ($i = 0; $i < $ncampos[$tabela]; $i++)
            printf("<td> %s </td>", $row1[$i]);
        printf("</tr>");
    }
}


/****** */
/* MAIN */
/****** */ 

// Form + Select Menu
printf("<form method='post'>");
    printf("Tabela:");
    printf("<select name='tabela'>");    
        $query  = "SHOW TABLES FROM innoplus";
        $result = mysqli_query($con, $query);
        printf("<option selected disabled hidden> Escolher... </option>");
        while ($row = mysqli_fetch_row($result))
            printf("<option> %s </option>", $row[0]);

        mysqli_free_result($result);
    printf("</select>");    
    printf("<input name='insert_post' type='submit' value='Ver tabela'>");
printf("</form>");


/****** */
/* POST */
/****** */ 

if (isset($_POST["insert_post"])) {
    printf("<h1> Tabela %s </h1>", $_POST["tabela"]);

    printf("<table>");
        tabela_campos($_POST["tabela"]);
        tabela_registos($_POST["tabela"], $con);
    printf("</table>");
}

?>

</body>

</html>