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
                    <tr>
                    <td class="hora"> 8:15 <br> 9:05 </td><td> ---</td>                          <td> EDFP </td>                     <td> PSINF/RED </td>                     <td> --- </td>                         <td> RED/PSINF </td>
                    </tr>
                    <tr>
                    <td class="hora"> 9:10 <br> 10:00 </td>  <td> MAT </td>                      <td> EDFP </td>                     <td> PSINF/RED </td>                     <td> PORT </td>                        <td> RED/PSINF </td>
                    </tr>
                    <tr>
                    <td class="hora"> 10:10 <br> 11:00 </td> <td> PSINF/RED </td>                <td> MAT </td>                      <td> MAT </td>                           <td> PORT </td>                        <td> PSINF/RED </td>
                    </tr>
                    <tr>
                    <td class="hora"> 11:05 <br> 11:55 </td> <td> PSINF/RED </td>                <td> MAT </td>                      <td> RED/PSINF </td>                     <td> MAT </td>                         <td> PSINF/RED </td>
                    </tr>
                    <tr>
                    <td class="hora"> 12:00 <br> 12:50 </td> <td> PSINF/FSQ </td>                <td class="almoco"> ALMOÇO </td>    <td> RED/PSINF </td>                     <td> FSQ </td>                         <td> FSQ </td>
                    </tr>
                    <tr>
                    <td class="hora"> 13:20 <br>  14.10 </td> <td class="almoco"> ALMOÇO </td>    <td> RED/PSINF </td>                <td> --- </td>                           <td class="almoco"> ALMOÇO </td>       <td class=almoco> ALMOÇO </td>
                    </tr>
                    <tr>
                    <td class="hora"> 14:15 <br> 15:05 </td> <td> RED/PSINF </td>                <td> PSINF/RED </td>                <td> --- </td>                           <td> PSINF/RED </td>                   <td> PORT </td>
                    </tr>
                    <tr>
                    <td class="hora"> 15:10 <br> 16:00 </td> <td> RED/PSINF </td>                <td> PSINF/RED </td>                <td> --- </td>                           <td> PSINF/RED </td>                   <td> PORT </td>
                    </tr>
                    <tr>
                    <td class="hora"> 16:05 <br> 16:55 </td> <td> FSQ/PSINF </td>                <td> RED/PSINF </td>                <td> --- </td>                           <td> RED/PSINF </td>                   <td> --- </td>
                    </tr>
                    <tr>
                    <td class="hora"> 17:00 <br> 17:50 </td> <td> PORT </td>                     <td> RED/PSINF </td>                <td> --- </td>                           <td> RED/PSINF </td>                   <td> --- </td>
                    </tr>




                
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