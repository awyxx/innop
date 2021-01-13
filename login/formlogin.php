<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="login.css">
</head>

<body style="background-image: url('../imagens/login_background.jpg');">
    <?php
    // verificar se temos sessao, se sim, bazar
    session_start();
    if (isset($_SESSION["codprof"])) {
        header("Location: ../index.php");
        exit;
    }
    ?>

    <div class="center">
        <div class="login">
            <h2 class="login-header"><img src="../imagens/innoplus_icon_branco.png" height="75px" width="190px"></h2>
            <form action="login.php" method="POST" class="login-container">
                <p><input name="np" type="text" placeholder="Nº de Processo"></p>
                <p><input name="cc" type="password" placeholder="CC"></p>
                <p><input name="login_post" type="submit" value="Iniciar sessão"></p>
            </form>
        </div>
    </div>

</body>
</html>