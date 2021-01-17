<html>

<head>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="index.css">

    
</head>

<body>

<?php include "navbar.php";
session_start();
if (!isset($_SESSION["codprof"])) { 
    header("Location: login/formlogin.php"); 
    exit; 
}
?>

<div class="corpo">
 
<br><br>

        <div class="esqcdireito">
            <div class="tabcdireito">
                Informações
            </div>
            <div class="desc">
                
                <div class="infocontainer">

                    <?php

                    printf("
                    <b> &emsp; Email: </b> &emsp;   %s 
                    ",
                    
                    $_SESSION['email']);
                    
                    ?>

                </div>
                
                <div class="infocontainer">
                <?php

                    printf("
                    <b> &emsp; Telemovel: </b> &emsp;   %s 
                    ",

                    $_SESSION['telemovel']);

                    ?>

                </div>
                
                <div class="infocontainer">

                    <?php
                    include "funcoes.php";

                    printf("<b> &emsp; Nacionalidade: </b> &emsp;" ); nacionalidade($_SESSION['nacionalidade']);
                    //echo $_SESSION["nacionalidade"];
                    ?>

                </div>





            </div>
        </div>
        <div class="horario">
        <div class="tabhorario">
                Horário
        </div>
            <div class="descdireito">
                teste
            </div>
        </div>
        <div class="esq">
            <div class="tab">
                teste
            </div>
            <div class="desc">
                teste
            </div>
        </div>
        <div class="informacao">
        <div class="tab">
                Horário
        </div>
            <div class="desc">
                teste
            </div>
        </div>
        <div class="esqbdireito">
        <div class="tab">
                teste
            </div>
            <div class="desc">
                teste
            </div>
        </div>
        </div>
        


</div>


<?php include "footer.php"; ?>


</body>

</html>