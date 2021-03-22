<?php 

include_once "connection.php";
include_once "Funcoes.php";

class Funcionario{

	private $con;
    private $objfc;
    private $idFuncionario;
    private $nome;
    private $cpf;
    private $status;

    public function __construct(){
        $this->con = new Connection();
        $this->objfc = new Funcoes();
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }
    public function __get($atributo){
        return $this->$atributo;
    }

    public function querySeleciona($dado){
        try{
            $this->idFuncionario = $this->objfc->base64($dado, 2);
            $cst = $this->con->Conectar()->prepare("SELECT cand_id, nome, cpf, status FROM `candidat_caua` WHERE `cand_id` = :idFunc;");
            $cst->bindParam(":idFunc", $this->idFuncionario, PDO::PARAM_INT);
            $cst->execute();
            return $cst->fetch();
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }

    public function querySelect(){
        try{
            $cst = $this->con->Conectar()->prepare("SELECT `cand_id`, `nome`, `cpf` FROM `candidat_caua`;");
            $cst->execute();
            return $cst->fetchAll();
        } catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }

     public function queryInsert($dados){
        try{

            $this->nome = $this->objfc->tratarCaracter($dados['nome'], 1);
            $this->cpf = $this->objfc->LimpaCPF($dados['cpf']);
            $this->status = $dados['status'];
            $cst = $this->con->Conectar()->prepare("INSERT INTO `candidat_caua` (`nome`, `cpf`, `status`) VALUES (:nome, :cpf, :status);");
            $cst->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $cst->bindParam(":cpf", $this->cpf, PDO::PARAM_STR);
            $cst->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($cst->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }

    public function queryUpdate($dados){
        try{
            $this->idFuncionario = $this->objfc->base64($dados['func'], 2);
            $this->nome = $this->objfc->tratarCaracter($dados['nome'], 1);
            $this->cpf = $this->objfc->LimpaCPF($dados['cpf']);
            $this->status = $dados['status'];
            $cst = $this->con->conectar()->prepare("UPDATE `candidat_caua` SET  `nome` = :nome, `cpf` = :cpf, `status` = :status  WHERE `cand_id` = :idFunc;");
            $cst->bindParam(":idFunc", $this->idFuncionario, PDO::PARAM_INT);
            $cst->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $cst->bindParam(":cpf", $this->cpf, PDO::PARAM_STR);
            $cst->bindParam(":status", $this->status, PDO::PARAM_STR);
            if($cst->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }


    public function queryDelete($dado){
        try{
            $this->idFuncionario = $this->objfc->base64($dado, 2);
            $cst = $this->con->conectar()->prepare("DELETE FROM `candidat_caua` WHERE `cand_id` = :idFunc;");
            $cst->bindParam(":idFunc", $this->idFuncionario, PDO::PARAM_INT);
            if($cst->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
}

 ?>