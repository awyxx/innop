<html>

<head>
    <meta charset='UTF-8'>
    <style>
    table, th, td {
        border: 1px solid black;
    }
    </style>
    <script>

    /* valores para buscar a linha da tabela que queremos modifica
    e mandar para input boxes + submit button pra depois receber pelo post */
    function buscar_valores_linha(element) {
        var id = parseInt(element.id) + 1;
        var rows = document.getElementsByTagName("table")[0].rows;
        var last = rows[id];

        var n = 0;
        for (var i = 0, col; col = last.cells[i]; i++)  n++;

        // buscar valores da linha
        var linha_str = [n-1];
        for (var i = 0, col; col = last.cells[i]; i++) {
            if (i == n-1)   break; 
            linha_str[i] = col.innerHTML;
        }

        // modificar
        if (element.value == "Modificar") {
            document.write("<form method='post'>");
            for (var i = 0; i < n-1; i++)
                document.write("<input type='text' value='",linha_str[i],"'> <br>");
            document.write("<input name='concluido_post' type='submit' value='Concluido'>");
            document.write("</form>");
        }
        // apagar
        else if (element.value == "Remover") {
            document.write("<form method='post'>");
            for (var i = 0; i < n-1; i++)
                document.write("<input disabled type='text' value='",linha_str[i],"'> <br>");
            document.write("<input name='concluido_post' type='submit' value='Apagar'>");
            document.write("</form>");
        }
    }

    </script>
</head>

<body>

<?php


/********* */
/* FUNCOES */
/********* */ 

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
    printf("<th> Ação </th>");
    printf("</tr>");
}

// vai printar os registos (as 'linhas') da tabela, formatados para a tabela html!
function tabela_registos($tabela, $db) {
    // numero de campos por tabela
    $ncampos = array("aluno"=>9, "cartao"=>3, "disciplina"=>3, "dt"=>4, "ee"=>6, "faltas"=>7, "horarios"=>7, 
                     "notas"=>5, "professor"=>7, "sumarios"=>6, "turma"=>5, "turmas"=>5);

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
    printf("<td>");
    printf("
    <form method='post'>
        <select id='%d' onchange='buscar_valores_linha(this)'>
        <option disabled hidden selected>  </option> 
        <option> Modificar </option> 
        <option> Remover </option> 
        </select>
    </form> 
    ", $x);
    printf("</td>");
}
/****** */
/* MAIN */
/****** */ 

include("../connect_db.php");

// form + select menu com as tabelas!
printf("<form method='post'> Tabela: ");
    printf("<select name='tabela' onchange='this.form.submit()'>");
        $query  = "SHOW TABLES FROM innoplus";
        $result = mysqli_query($con, $query);
        printf("<option selected disabled hidden> Escolher... </option>");
        while ($row = mysqli_fetch_row($result))
            printf("<option> %s </option>", $row[0]);
        mysqli_free_result($result);
    printf("</select>");    
printf("</form>");


/******* */
/* POSTS */
/******* */ 

if (isset($_POST["tabela"])) {

    if ($_POST["tabela"] == "") {
        printf("Erro!");
        exit;
    }


    printf("<h1> Tabela %s </h1>", $_POST["tabela"]);

    printf("<table name='table1'>");
        tabela_campos($_POST["tabela"]);
        tabela_registos($_POST["tabela"], $con);
    printf("</table>");
}

if (isset($_POST["concluido_post"])) {
    echo "<h1> olasdfsadfs </h1>";
}
?>

</body>
</html>