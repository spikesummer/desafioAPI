<?php
session_start();
require_once '../usuario.php';

if(!isset($_SESSION['idConta'])){
    header("Location: index.php");
}
$u = new Usuario;
$u->conectar();
$nome = $u->buscarPessoa($_SESSION['idConta']);
$conta = $_SESSION['idConta'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <link rel="stylesheet" href="../estilo.css">
</head>
<body>
    <div class="navbar">
        <span>Olá, <?php echo $nome['nome'] ?> </span>
    </div>  

    <div class="acesso menuPrincipal">
        <a class="teclas dadosPessoais" href="../processa.php?valor=<?php echo $conta?>&funcao=dadosPessoais">DADOS PESSOAIS</a>
        <a class="teclas" href="../processa.php?valor=<?php echo $conta?>&funcao=deposito">DEPÓSITO</a>
        <a class="teclas" href="../processa.php?valor=<?php echo $conta?>&funcao=saque">SAQUE</a>
        <a class="teclas" href="../processa.php?valor=<?php echo $conta?>&funcao=extrato">EXTRATO</a>
        <a class="teclas" href="../processa.php?valor=<?php echo $conta?>&funcao=saldo">SALDO</a>
        <a class="teclas" href="../processa.php?valor=<?php echo $conta?>&funcao=pagamento">PAGAMENTO</a>
        <a class="teclas" href="../processa.php?valor=<?php echo $conta?>&funcao=bloqueio">BLOQUEIO DE CONTA</a>
        <a class="teclas sair" href="../logout.php">SAIR</a>
    </div>

</body>
</html>