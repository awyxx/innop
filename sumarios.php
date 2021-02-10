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
    }

    </script>
</head>

<body> 
    <?php include "navbar.php"; ?>

    <?php 

    /*
    Idea:
        fazer dropdown menu com todas as lições do stor ate agr e textbox ao lado 
        a opção default é a ultima lição +1 (ou seja a q ele vai escrever agr)
        se ele selecionar outra opção, aparece o sumario dessa na textbox
    */

    include("connect_db.php");

    // menu dropdown com os sumarios/licoes do stor
    function select_licoes($con) {
        printf("
        <form method='post'>
            <select name='licoes' onchange='this.form.submit()'>");
                $codprof = $_SESSION["codprof"];
                $query = "SELECT licao FROM sumarios WHERE codprof = $codprof"; /* query pa ir buscar as lições todas do codprof session !*/
                $result = mysqli_query($con, $query);
                printf("<option selected disabled hidden> Lição </option>"); // LAST LIÇÃO + 1!! OU SEJA LICAO ATUAL
                while ($row = mysqli_fetch_row($result))
                    printf("<option> %s </option>", $row[0]);
        printf("</select> </form>");
    }

    function select_disciplinas($con) {
        printf("
        <form method='post'>
            <select name='disciplina' onchange='this.form.submit()'>");
                $codprof = $_SESSION["codprof"];
                $query = "SELECT nome FROM disciplina";/* WHERE codprof = $codprof";*/
                $result = mysqli_query($con, $query);
                printf("<option selected disabled hidden> Escolher ... </option>"); 
                while ($row = mysqli_fetch_row($result))
                    printf("<option> %s </option>", $row[0]);
        printf("</select> </form>");
    }

    select_disciplinas($con);
    //select_licoes($con);

    if (isset($_POST["disciplina"])) {
        $g_disciplina = $_POST["disciplina"];
        printf("Disciplina: %s", $g_disciplina);
    } else {
        printf("nepi");
    }

    ?>
    
    <?php include "footer.php"; ?>
</body>

</html>