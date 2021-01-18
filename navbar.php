<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    
    <div class="navigation">

    <div class="logo"> <img src="imagens/innoplus_icon.png" height="100px"> </div>
    <?php

    // tirar os warnings / erros !!! IMPORTANTE !!!!
    error_reporting(0);

    // se nao dermos start nao da p usar $_SEssion mas isto da erro na mesma, fix l8r
    session_start(); 

    // funcao para meter cor na pagina 
    function menu_pag_ativa() {
        switch(basename($_SERVER["PHP_SELF"])) {
            case "index.php": {
                printf("<a href='index.php'> <div class='inicial  active'> <b> Página Inicial </b> </div> </a>
                        <a href='sumarios.php'> <div class='references'> Sumários </div> </a>
                        <a href='faltas.php'> <div class='references'> Faltas </div> </a> ");
                break;
            }
            case "sumarios.php": {
                printf("<a href='index.php'> <div class='inicial'> Página Inicial </div> </a> 
                        <a href='sumarios.php'> <div class='references active'> <b> Sumários </b> </div> </a>
                        <a href='faltas.php'> <div class='references'> Faltas </div> </a> ");
                break;
            }
            case "faltas.php": {
                printf("<a href='index.php'> <div class='inicial'> Página Inicial </div> </a>
                        <a href='sumarios.php'> <div class='references'> Sumários </div> </a>
                        <a href='faltas.php'> <div class='references  active'> <b> Faltas </b> </div> </a> ");
                break;
            }
        }
    }

    /*
        MAIN
    */

    menu_pag_ativa();
    
    ?>

    <div class="infoprof">            
        <div class="esquerda">
            <?php 

            // dados do stor (canto superior direito)
            printf("
                    <table>

                        <tr>
                            <td> CodCartao:  </td> <td> <b> &emsp; %s </b> </td>
                        </tr>
                        <tr>
                            <td> Nome:  </td> <td> <b> &emsp; %s </b> </td>
                        </tr>
                        <tr>
                            <td> Status:  </td> <td> <b> &emsp; %s </b> </td>
                        </tr>
                        <tr>
                            <td> Saldo:  </td> <td> <b> &emsp; %s € </b> </td>
                        </tr>

                    </table>",

                    $_SESSION["codcartao"], $_SESSION["nome"], $_SESSION["status"], $_SESSION["saldo"]);
            
        
            ?>
            <a href="logout.php"><img src="imagens/logout.png" width="30px" height="30px" legend="Encerrar Sessão"></a>
        </div>
        <div class="direita">
            <div class="imagem"> <?php echo '<img height=88px width=88px src="data:image/jpeg;base64,'.base64_encode( $_SESSION['img'] ).'"/>'; ?> </div>
        </div>

    </div>

    </div>

</body>
</html>