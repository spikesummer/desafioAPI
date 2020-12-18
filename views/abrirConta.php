<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura de Conta</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <div class="container">
        <div class="acesso abrirConta">
            <h2>Abertura de conta</h2>
            <form method="POST" action="../cadastrar.php">
                <label for="">Tipo de conta</label>
                <select name="tipoConta" id="">
                    <option>Conta corrente</option>
                    <option>Conta poupança</option>
                </select>
                <label for="">Nome</label>
                <input type="text" name="nome" id="">
                <label for="">CPF</label>
                <input type="text" name="cpf" id="">
                <label for="">Data de Nascimento</label>
                <input type="date" name="dataNasc" id="">
                <label for="">Senha</label>
                <input type="password" name="senha" id=""> 
                <label for="">Confirmar senha</label>
                <input type="password" name="confirmarSenha" id="">
                <input type="submit" value="Cadastrar">
                <a href="index.php" style="display: flex; color:white; font-weight: bold; cursor: pointer; justify-content: center; margin-top: 20px; text-shadow: 1px 1px 5px rgba(0,0,0, 0.5); border: 1px solid #fff; background-color: #f61f0e; padding: 8px; font-size: 16px; letter-spacing: 1px; border-radius: 2px; text-decoration: none;">Voltar</a>
            </form>
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