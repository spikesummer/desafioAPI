<?php
session_start();
if(!isset($_SESSION['idConta'])){
    header("Location: ./views/index.php");
}
require_once 'usuario.php';

$conta = $_GET['valor'];
$funcao = $_GET['funcao'];

$u = new Usuario;
$u->conectar();
$nome = $u->buscarPessoa($conta);
$dadosConta = $u->buscarConta($nome['idPessoa']);
$msg = "";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="funcoes.css">
    <title>menu Principal</title>
</head>
<body>
    <div class="funcoes">
        <div class='teclas'>
            <?php
                switch ($funcao) {
                    case 'dadosPessoais':
                        echo "
                            <h2>Dados Pessoais</h2>
                            <p>Nome: ". $nome['nome']."</p>
                            <p>CPF: ". $nome['cpf']."</p>
                            <p>Data de Nascimento: ". $nome['dataNascimento']."</p>
                            <p>Conta número: ". $conta."</p>
                            <p>Tipo de Conta: ". $dadosConta['tipoConta']."</p>
                            ";
                        break;
                    case 'deposito':
                        if($valor == null){
                            echo    "<h2>Depósito</h2>
                                    <form action='processa.php' method='GET'>
                                        <input type='number' min='0' step='0.010' name='deposito' required='required'></input>
                                        <input type='submit' value= 'Depositar'>
                                    </form>
                                    ";
                        }
                        break;
                    case 'saque':
                        echo    "<h2>Saque</h2>
                                <form action='processa.php' method='GET'>
                                    <input type='number' min='0' step='0.010' name='sacar' required='required'></input>
                                    <input type='submit' value= 'Sacar'>
                                </form>
                                ";

                        break;
                    case 'extrato':
                        echo    "<h2>Extrato</h2>
                                <p>Função ainda será implementada.</p>";
                        break;
                    case 'saldo':
                        echo "
                            <h2>Saldo</h2>
                            <p>Saldo Atual: R$ ". number_format( $dadosConta['saldo'],2,",",".")."</p>
                            ";
                        break;
                    case 'pagamento':
                        echo    "<h2>Pagamento</h2>
                                <p>Função ainda será implementada.</p>
                                ";
                        break;
                    case 'bloqueio':
                        echo    "<h2>Bloqueio de Conta</h2>
                                <p>Função ainda será implementada.</p>";
                        break;
                }
                // ==== RESULTADO DAS FUNCOES ====
                
                // ----- DEPOSITAR -----
                if($_GET['deposito'] != null){
                    echo "
                        <h2>Depósito</h2>
                        <p>Valor depositado: R$ ". number_format( $_GET['deposito'],2,",",".")."</p>";
                    $valor = number_format( $_GET['deposito'],2,".","");
                    
                    $u->depositar($_SESSION['idConta'], $valor);
                    
                }
                // ----- SACAR -----
                if($_GET['sacar'] != null){
                    $msg = $u->sacar($_SESSION['idConta'], $_GET['sacar']);
                    echo "<h2>Saque</h2>".$msg;   
                }
            ?>
        </div>
    </div>
        <a href="./views/menuPrincipal.php">Voltar</a>
</body>
</html>