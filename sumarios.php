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
    <?php include "navbar.php"; ?>

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
                printf("<option style='background-color: rgb(65,180,255)'> %d </option>", intval($row[0],10)+1);
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
        $query  = "SELECT MAX(licao) FROM sumarios WHERE codprof = $codprof AND coddisciplina = $coddisciplina;";
        $result = mysqli_query($con, $query);
        $row    = mysqli_fetch_row($result);
        if (intval($row[0],10)+1 == $licao) {
            // introduzir novo sumario, form, inputbox e botao 
            // ir buscar data de hoje php
            printf("
            <form method='post'>
                <fieldset class='fieldsum'>
                <legend> %s - %s - Lição %d - Data: %s </legend>
                    <textarea name='sumario_' value='' cols='50' rows='10' style='height:100%%;width:100%%'></textarea> <br><br> 
                    <input class='buta' name='novo_sumario_post' type='submit' value='Introduzir novo sumário'>
                    <input class='buta' type='reset' value='Apagar'>
                </fieldset>
            </form>
            ", $_SESSION["turma"], $_SESSION["nomedisciplina"], $licao, "oi", "test");
        } else {
            // licoes antigas, mostrar sumario numa fieldset + inputbox disabled
            $query = "SELECT sumario, hora, diasemana FROM sumarios WHERE codprof = $codprof AND codturma = $codturma 
            AND coddisciplina = $coddisciplina AND  licao = $licao";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            //printf("debug : query : %s", $query);

            // mostrar input box com o sumario e a sua data
            printf("
            <fieldset class='fieldsum'>
            <legend> %s - %s - Lição %d - Data: %s </legend>
            <textarea disabled type='text' id='sumario_' name='sumario_' cols='50' rows='10' style='height:100%%;width:100%%' > %s </textarea>  <br><br> 
            </fieldset>
            ", $_SESSION["turma"], $_SESSION["nomedisciplina"], $licao, $row["diasemana"], $row["sumario"]);
        }
    } else if (isset($_POST["novo_sumario_post"])) {
        // mostrar selects de novo para nao desaparecerem (bug estranho)
        select_disciplinas($con);
        select_licoes($con);

        // buscar sumario da input box
        $sumario = $_POST["sumario_"];

        // verificar se é valido mais ou menos xd
        if (strlen($sumario) < 3 || $sumario == "" || $sumario == " ") {
            printf("Erro, verifique o seu sumário e tente novamente");
            printf("<br> <a href='sumarios.php'> voltar </a>");
        }

        // buscar dados importantes para o insert
        $codprof    = $_SESSION["codprof"];
        $codturma   = codturma($con, $_SESSION["turma"][3], $_SESSION["turma"][0].$_SESSION["turma"][1]);
        $licao      = $_SESSION["licao"];
        $hora       = date("H:i");   // hora em q o sumario foi introduzido
        $dia        = date("Y-m-d"); // dia em q o sumario foi introduzido
        $coddisciplina  = coddisciplina($con, $_SESSION["nomedisciplina"], $codprof);

        // fazer query
        $query = "INSERT INTO `sumarios`(`codprof`, `codturma`, `licao`, `sumario`, `hora`, `diasemana`, `coddisciplina`) 
                        VALUES ($codprof,$codturma,$licao,'$sumario','$hora','$dia',$coddisciplina)";
    
        printf("queryzona: %s", $query);


        
        //fazr query ez pz
        $result = mysqli_query($con, $query);
        if(!$result)    
            printf("<br> Erro estranho, sumario nao introduzido: %s", mysqli_error($con));

  
            // sumario introduzido com sucesso!
        
        // reload à pagina?
    }

    printf("</div>");

    ?>
    
    <?php include "footer.php"; ?>
</body>

</html>