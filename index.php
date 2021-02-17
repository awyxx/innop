<html>

<head>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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

            <table class="horariotabela">
                <th class="tit"> <img src="imagens/plus.png" width="22px" height="20px">  </th> <th class="tit"> Segunda-Feira </th> <th class="tit"> Terça-Feira </th> <th class="tit"> Quarta-Feira </th> <th class="tit"> Quinta-Feira </th> <th class="tit"> Sexta-Feira </th>
            <?php
            include("connect_db.php");

            // optimização 
            $horas = array(
                "<td class='hora'>8:15<br>9:05</td>",
                "<td class='hora'>9:10<br>10:00</td>",
                "<td class='hora'>10:10<br>11:00</td>",
                "<td class='hora'>11:05<br>11:55</td>",
                "<td class='hora'>12:00<br>12:50</td>",
                "<td class='hora'>13:20<br>14:10</td>",
                "<td class='hora'>14:15<br>15:05</td>",
                "<td class='hora'>15:10<br>16:00</td>",
                "<td class='hora'>16:10<br>17:00</td>",
                "<td class='hora'>17:05<br>17:55</td>"
            );

            $query = "SELECT * FROM horarios ORDER BY hora ASC"; /* temos q adicionar codprof ao horario? ou codhorario a codprof? */
            $result = mysqli_query($con, $query);
            if (!$result)   printf("Erro: %s", mysqli_error($con));
            else {
                $idxhora = 0;
                while ($row = mysqli_fetch_row($result)) {
                    // isto dá pa optimizar ainda mais mas nao tou a conseguir pensar xddd

                    // 2 = seg , 3 = terça, etc..
                    $seg = "<td>".$row[2]."</td>";
                    $ter = "<td>".$row[3]."</td>";
                    $qua = "<td>".$row[4]."</td>";
                    $qui = "<td>".$row[5]."</td>";
                    $sex = "<td>".$row[6]."</td>";

                    if ($seg == "<td>ALMOÇO</td>") $seg = "<td class='almoco'> ALMOÇO </td>"; 
                    if ($ter == "<td>ALMOÇO</td>") $ter = "<td class='almoco'> ALMOÇO </td>";
                    if ($qua == "<td>ALMOÇO</td>") $qua = "<td class='almoco'> ALMOÇO </td>";
                    if ($qui == "<td>ALMOÇO</td>") $qui = "<td class='almoco'> ALMOÇO </td>";
                    if ($sex == "<td>ALMOÇO</td>") $sex = "<td class='almoco'> ALMOÇO </td>";

                    printf("<tr> %s %s %s %s %s %s </tr>", $horas[$idxhora], $seg, $ter, $qua, $qui, $sex);

                    $idxhora++;
                    if ($idxhora == 10) break;
                }
            }
            ?>
            
            </table>

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