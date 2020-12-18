<?php
session_start();
require_once 'usuario.php';
$u = new Usuario;

if(isset($_POST['conta'])){
    $conta = addslashes($_POST['conta']);
    $senha = addslashes($_POST['senha']);  
}

if(empty($_POST['conta']) || empty($_POST['senha'])){
    $_SESSION['msgInvalida'] = 'Conta e senha devem ser preenchidos.';
    header('Location: ./views/index.php');
    exit();
}else{
    $u->conectar();
    if($u->msgErro == ""){
        if($u->logar($conta, $senha)){
            header('Location: ./views/menuPrincipal.php'); 
            
        }else{
            $_SESSION['msgInvalida'] = 'Conta ou senha invalidos';
            header('Location: ./views/index.php');
            exit();
        }
    }else{
        echo $u->msgErro;
    } 
}

?>