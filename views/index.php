<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <div class="container">
        <div class="acesso">
            <h2>Acesse sua conta</h2>
            <form method="POST" action="../login.php">
                <label for="">Conta</label>
                <input type="text" name="conta" id="conta">
                <label for="">Senha</label>
                <input type="password" name="senha" id="senha"> 
                <input type="submit" value="Acessar">
            </form>
            <a href="./abrirConta.php" class="linkAbrirConta">Clique aqui e abra sua conta.</a>
        </div>
        <div class="alert">
            <?php
                if(isset($_SESSION['msgInvalida'])){
                    echo $_SESSION['msgInvalida'];
                    unset($_SESSION['msgInvalida']);
                }
            ?>
        </div>
    </div>
    
</body>
</html>
