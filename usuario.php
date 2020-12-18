<?php
// -------- CONSTANTE DE CONEXÃO --------
const nome = 'desafio';
const host = 'localhost';
const usuario = 'root';
const senha = 'root';

Class Usuario{
    private $pdo;
    public $msgErro = "";

    public function conectar(){
        global $pdo;
        try{
            $pdo = new PDO("mysql:dbname=".nome.";host=".host, usuario, senha);
        }catch(PDOException $e){
           global $msgErro; 
           $msgErro = $e->getMessage();
        }
    }  

    public function cadastrar($nome, $cpf, $dataNasc){
        global $pdo;
        //verificar se já existe cliente cadastrado.
        $sql = $pdo->prepare("SELECT idPessoa FROM pessoas WHERE nome = :n");
        $sql->bindValue(":n", $nome);
        $sql->execute();

        if($sql->rowCount()>0){
            return false; //ja esta cadastrado.
        }else{
            //caso não, Cadastrar
            $sql = $pdo->prepare("INSERT INTO pessoas(nome, cpf, dataNascimento) VALUES (:n, :c, :d)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":c", $cpf);
            $sql->bindValue(":d", $dataNasc);
            $sql->execute(); 
            
            return true;    // Cliente cadastrada.
        }
    }

    public function buscarUltimaPessoaCadastrada(){
        global $pdo;
        $sql = $pdo->prepare("SELECT * FROM pessoas WHERE idPessoa = (SELECT max(idPessoa) FROM pessoas)");
        $sql->execute();
        $res = $sql->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function cadastrarConta($idPessoa, $tipoConta, $senha){
        global $pdo;

        $sql = $pdo->prepare("INSERT INTO contas(idPessoa, tipoConta, dataCriacao, senha) VALUES (:p, :c, CURDATE(), :s)");
        $sql->bindValue(":p", $idPessoa);
        $sql->bindValue(":c", $tipoConta);
        $sql->bindValue(":s", md5($senha));
        $sql->execute(); 
    }

    public function logar($conta, $senha){
        global $pdo;
        $sql = $pdo->prepare("SELECT idConta FROM contas WHERE idConta = :c AND senha = :s");
        $sql->bindValue(":c", $conta);
        $sql->bindValue(":s", md5($senha));
        $sql->execute();
        if($sql->rowCount()>0){
            //entrar no sistema.
            $dado = $sql->fetch();
            session_start();
            $_SESSION['idConta'] = $dado['idConta'];
            return true; // logado com sucesso.
        }else{
            return false; //não foi possivel logar.
        }
    }
    
    public function buscarPessoa($idConta){
        global $pdo;
        $cmd = $pdo->prepare("SELECT idPessoa FROM contas WHERE idConta = :c");
        $cmd->bindValue(":c", $idConta);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        $sql = $pdo->prepare("SELECT * FROM pessoas WHERE idPessoa = :p"); 
        $sql->bindValue(":p", $res['idPessoa']);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function buscarConta($idPessoa){
        global $pdo;
        $cmd = $pdo->prepare("SELECT * FROM contas WHERE idPessoa = :p");
        $cmd->bindValue(":p", $idPessoa);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function depositar($idConta, $valor){
        global $pdo;
        $cmd = $pdo->prepare("SELECT saldo FROM contas WHERE idConta = :c");
        $cmd->bindValue(":c", $idConta);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);

        $total = (float)$res['saldo']+(float)$valor;
        $sql = $pdo->prepare("UPDATE contas SET saldo = :s WHERE idConta = :c");
        $sql->bindValue(":s", $total);
        $sql->bindValue(":c", $idConta);
        $sql->execute();
        
        $sql = $pdo->prepare("INSERT INTO transacoes(idTransacao, idConta, valor, dataTransacao) VALUES ('1', :c, :v, CURDATE())");
        $sql->bindValue(":c", $idConta);
        $sql->bindValue(":v", $valor);
        $sql->execute(); 
        return true;
    }

    public function sacar($idConta, $valor){
        global $pdo;

        // ====== CONSULTA SALDO ======
        $cmd = $pdo->prepare("SELECT saldo FROM contas WHERE idConta = :c");
        $cmd->bindValue(":c", $idConta);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);

        // ====== CONSULTA LIMITE ======
        
        $data  = date('Y-m-d'); //DATA DE HOJE.
        $cmd = $pdo->prepare("SELECT * FROM transacoes WHERE idConta = :c AND idTransacao = '2' AND dataTransacao = :d");
        $cmd->bindValue(":c", $idConta);
        $cmd->bindValue(":d", $data);
        $cmd->execute();
        $res1 = $cmd->fetchAll(PDO::FETCH_ASSOC);

        foreach($res1 as $key => $value){
            $soma += (float)$res1[$key]['valor']; //total sacado no 
        }
        // ---------------------------------------
        $total = ((float)$soma + (float)$valor);  // soma do saque diario e do saque atual.
        $limiteDiario = (float)800.00; 
        $novoSaldo = (float)$res['saldo'] - (float)$valor;
        // ---------------------------------------
        if((float)$res['saldo'] <= (float)$valor){
            $msg = '<p>Saldo insuficiente.</p>';
            return $msg;
        }elseif($total > $limiteDiario){
           $msg = '<p>Saque excede limite diário.</p>';
           return $msg;
        }else{
            $msg = '<p>Saque efetuado com sucesso.</p>';
            // ==== SUBTRAIR SAQUE DO SALDO ====
            $cmd = $pdo->prepare("UPDATE contas SET saldo = :s WHERE idConta = :c");
            $cmd->bindValue(":s", $novoSaldo);
            $cmd->bindValue(":c", $idConta);
            $cmd->execute();
            // ==== REGISTRAR TRANSAÇÃO NA TABELA ====
            $cmd = $pdo->prepare("INSERT INTO transacoes(idTransacao, idConta, valor, dataTransacao) VALUES ('2', :c, :v, CURDATE()");
            $cmd->bindValue(":c", $idConta);
            $cmd->bindValue(":v", (float)$valor);
            $cmd->execute();
            //-------------------------
           return $msg;
        } 
    }

    public function buscarTransacao($idTipoTransacao, $idConta){
        global $pdo;
        $cmd = $pdo->prepare("SELECT * FROM transacoes WHERE idTransacao = :t AND idConta = :c");
        $cmd->bindValue(":t", $idTipoTransacao);
        $cmd->bindValue(":c", $idConta);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
}
// $u = new Usuario;
// $u->conectar();

?>