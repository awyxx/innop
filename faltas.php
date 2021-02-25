

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="paginas.css">

    <script>
    // vai buscar o sumario do stor à bd pela opc selecionada no select
    function buscar_sumario(element) {
        var id = parseInt(element.id)
        alert(id);
        alert(element.innerHTML);
    }

    </script>
</head>

<body> 

    <?php include "navbar.php";
    
    ?>

    <?php 

    /*
    objetivo:
        drop down com todas as turmas e disciplinas do stor
        fazer dropdown menu com todas as lições do stor ate agr e textbox ao lado 
        depois de selecionar a turma e a disciplina, escolher lição
        a opção default é a ultima lição +1 (ou seja a q ele vai escrever agr)
        se ele selecionar outra opção, aparece o sumario dessa na textbox (disabled para n mudar nada)
    */

    include("connect_db.php");

    // menu dropdown com todas as turmas 
    function select_turma($con) {
        printf("Turma: 
        <form method='post'>
            <select style='font-size:130%%;background-color:#17b;color:white' name='turma' onchange='this.form.submit()'>");
                //$codprof = $_SESSION["codprof"];
                $g_turma = $_SESSION["turma"];
                $query = "SELECT ano, sigla FROM turmas";
                $result = mysqli_query($con, $query);
                printf("<option selected disabled hidden> %s </option>", $g_turma); 
                while ($row = mysqli_fetch_row($result))
                    printf("<option style='background-color: rgb(65,180,255)'> %s %s </option>", $row[0], $row[1]);
        printf("</select> </form>");
    }

    // menu dropdown com as disciplinas do stor
    function select_disciplinas($con) {
        printf("Disciplina: 
        <form method='post'>
            <select style='font-size:130%%;background-color:#17b;color:white' name='disciplina' onchange='this.form.submit()'>");
                $codprof    = $_SESSION["codprof"];
                $disciplina = $_SESSION["nomedisciplina"];
                $codturma   = codturma($con, $_SESSION["turma"][3], $_SESSION["turma"][0].$_SESSION["turma"][1]);
                $query = "SELECT nome FROM disciplina WHERE codprof = $codprof";
                $result = mysqli_query($con, $query);
                printf("<option selected disabled hidden> %s </option>", $disciplina); 
                while ($row = mysqli_fetch_row($result))
                    printf("<option style='background-color: rgb(65,180,255)'> %s </option>", $row[0]);
        printf("</select> </form>");
    }

    // menu dropdown com os sumarios/licoes do stor
    function select_licoes($con) {
        printf("Lição:
        <form  id='formtest' method='post'>
            <select style='font-size:130%%;background-color:#17b;color:white' name='licao' onchange='this.form.submit()'>");
                $codprof        = $_SESSION["codprof"];
                $disciplina     = $_SESSION["nomedisciplina"];
                $licao          = $_SESSION["licao"];
                $codturma   = codturma($con, $_SESSION["turma"][3], $_SESSION["turma"][0].$_SESSION["turma"][1]);
                $coddisciplina  = coddisciplina($con, $disciplina, $codprof);
                $query = "SELECT licao FROM sumarios WHERE codprof = $codprof AND coddisciplina = $coddisciplina and codturma = $codturma;"; 
                $result = mysqli_query($con, $query);
                printf("<option selected disabled hidden> %s </option>", $licao);
                while ($row = mysqli_fetch_row($result))
                    printf("<option style='background-color: rgb(65,180,255)'> %s </option>", $row[0]);
                //adicionar option com a licao atual pa ser introduzida
                $query = "SELECT MAX(licao) FROM sumarios WHERE codprof = $codprof AND coddisciplina = $coddisciplina;";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_row($result);
        printf("</select> </form>");
    }
    // da return ao cod disciplina
    function coddisciplina($con, $nome, $codprof) {
        $query = "SELECT coddisciplina FROM disciplina WHERE nome = '$nome' AND codprof = $codprof";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_row($result);
        return $row[0];
    }

    // da return ao cod turma, introduzindo a sigla e o ano
    function codturma($con, $sigla, $ano) {
        $query = "SELECT codturma FROM turmas WHERE sigla = '$sigla' AND ano = $ano";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_row($result);
        return $row[0];
    }

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


    /*

        MAIN

    */
    // select com todas as turmas
    printf("<div class='uperleft'>");
    select_turma($con);

    if (isset($_POST["turma"])) {
        // salvar dados session e mostrar selects de novo para nao desaparecerem (bug estranho)
        $_SESSION["licao"] = "...";
        $_SESSION["nomedisciplina"] = "..."; 
        $_SESSION["turma"] = $_POST["turma"]; 
        select_disciplinas($con);
    }
    else if (isset($_POST["disciplina"])) {
        // salvar dados na session e mostrar selects de novo para nao desaparecerem (bug estranho)
        $_SESSION["licao"] = "...";
        $_SESSION["nomedisciplina"] = $_POST["disciplina"];
        select_disciplinas($con);
        select_licoes($con);
    }
    else if (isset($_POST["licao"])) {
        // salvar a licao escolhida na session e mostrar selects de novo para nao desaparecerem (bug estranho)
        $_SESSION["licao"] = $_POST["licao"];
        select_disciplinas($con);
        select_licoes($con);

        // buscar dados importantes para o select
        $codprof        = $_SESSION["codprof"];
        $codturma       = codturma($con, $_SESSION["turma"][3], $_SESSION["turma"][0].$_SESSION["turma"][1]);
        $coddisciplina  = coddisciplina($con, $_SESSION["nomedisciplina"], $codprof);
        $licao          = $_POST["licao"];

        // verificar o numero da licao, se for a nova licao (ultima + 1), aparecer form para introduzir novo sumario
       /* $query  = "SELECT MAX(licao) FROM sumarios WHERE codprof = $codprof AND coddisciplina = $coddisciplina;";
        $result = mysqli_query($con, $query);
        $row    = mysqli_fetch_row($result);*/

        $ncampos = array("aluno"=>num_campos_tabela("aluno", $con),          "cartao"=>num_campos_tabela("cartao", $con), 
                    "disciplina"=>num_campos_tabela("disciplina", $con), "dt"=>num_campos_tabela("dt", $con), 
                    "ee"=>num_campos_tabela("ee", $con),                 "faltas"=>num_campos_tabela("faltas", $con), 
                    "horarios"=>num_campos_tabela("horarios", $con),     "notas"=>num_campos_tabela("notas", $con), 
                    "professor"=>num_campos_tabela("professor", $con),   "sumarios"=>num_campos_tabela("sumarios", $con), 
                    "turma"=>num_campos_tabela("turma", $con),           "turmas"=>num_campos_tabela("turmas", $con));

     //   if (intval($row[0],10) == $licao) {
            
            $query = "SELECT turma.numaluno, aluno.nome, turma.codaluno, aluno.nacionalidade, aluno.telemovel, aluno.email from turma inner join aluno on turma.numaluno = aluno.codaluno where turma.codturma = $codturma order by turma.codaluno asc";
            $result = mysqli_query($con, $query);
            $x = 0;
            printf("<form method='post'>");
            printf("<table class='tablefaltas' >");
            printf("
            <tr>
            <th>Nº Processo</th> 
            <th>Nome</th> 
            <th>Numero</th> 
            <th>Nacionalidade</th> 
            <th>Telefone</th> 
            <th>E-mail</th> 
            <th>Falta</th>
            </tr>");
            
            while ($row1 = mysqli_fetch_row($result)) {
                printf("<tr>");
                printf("<td> %s </td> <td> %s <t/d> <td> %s <t/d> <td> %s <t/d> <td> %s </td> <td> %s </td> ", $row1[0], $row1[1], $row1[2], $row1[3], $row1[4], $row1[5]);
                //printf("<td> <input type='checkbox' id='%s' name='%s' value='%s'> </td> ",$row1[0],$row1[0],$row1[0]);
                printf("<td> <input type='checkbox' id='ck[]' name='ck[]' value='%s'> </td> ",$row1[0]);
                printf("</tr>");
                $x++;

            }
            printf("</table>");
            printf(" <center>  <input style='margin-top:1%%;margin-bottom: 10%%;width: 35%%;color:white;background-color:#17b;font-size:130%%' name='nova_falta' type='submit' value='Marcar Faltas'>  </center> ");
            printf("</form>");
    }
    else if (isset($_POST["nova_falta"]))
    {
        $codprof = $_SESSION["codprof"];
        $coddisci = coddisciplina($con, $_SESSION["nomedisciplina"], $codprof);
        foreach ( $_POST["ck"] as $valores)
        {
            $querie = "INSERT INTO faltas(`codfalta`, `codaluno`, `datafalta`, `diasemana`, `idxhora`, `coddisciplina`, `tipofalta`) VALUES (NULL, '$valores' , CURRENT_DATE() , DAYOFWEEK(CURRENT_DATE()), CURRENT_TIME(),'$coddisci' ,'Presença')";
            printf("<br>".$valores."<br>");
            printf("Query on the bankzzzz :  %s",$querie);
            $result = mysqli_query($con, $querie);
            if(!$result)    
            printf("<br> Erro estranho, falta nao introduzida: %s", mysqli_error($con));
        }
    }

    printf("</div>");

    ?>

    <?php

    include "footer.php"; 
    
    ?>
</body>

</html>