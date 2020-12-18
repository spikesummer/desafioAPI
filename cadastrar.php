<?php
session_start();
require_once 'usuario.php';
$u = new Usuario;


if(isset($_POST['nome'])){
    if(empty($_POST['tipoConta']) || empty($_POST['nome']) || empty($_POST['cpf']) || empty($_POST['dataNasc'])  || empty($_POST['senha']) || empty($_POST['confirmarSenha'])){
        $_SESSION['msgInvalida'] = 'Os campos devem ser preenchidos.';
        header('Location: ./views/abrirConta.php');
        exit();
    }else{
        if($_POST['senha'] != $_POST['confirmarSenha']){
            $_SESSION['msgInvalida'] = 'Os campos senha e confirmar senha são diferentes.';
            header('Location: ./views/abrirConta.php');
            exit();
        }else{
            $tipoConta = addslashes($_POST['tipoConta']);
            $nome = addslashes($_POST['nome']);
            $cpf = addslashes($_POST['cpf']);
            $dataNasc = addslashes($_POST['dataNasc']);
            $senha = addslashes($_POST['senha']);
            $u->conectar();
            if($u->cadastrar($nome, $cpf, $dataNasc)){
                //cadastrar a conta e associar a pessoa.
                $idPessoa = $u->buscarUltimaPessoaCadastrada();
                $u->cadastrarConta($idPessoa['idPessoa'], $tipoConta, $senha);
                $idConta = $u->buscarConta($idPessoa['idPessoa']);
                $_SESSION['msgInvalida'] = 'Conta: '.$idConta['idConta'].' cadastratada com sucesso.';
                header('Location: ./views/index.php');
            }else{
                $_SESSION['msgInvalida'] = 'Conta já cadastrada.';
                header('Location: ./views/abrirConta.php');
                exit();
            }
        }
    }  
}
?>