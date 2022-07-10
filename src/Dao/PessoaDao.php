<?php
namespace App\Dao;

use BMorais\Database\Crud;
use App\Lib\FuncoesClass;
use App\Models\EnderecoModel;
use App\Models\FuncionarioModel;
use App\Models\PessoaModel;
use App\Models\UsuarioModel;

class PessoaDao extends Crud{


    public function __construct()
    {
        $this->tableName = "pessoa";
        $this->classModel = "PessoaModel";
    }


    public function buscarPessoaTodos($buscar = null)
    {
        $sql = "SELECT * FROM pessoa WHERE sts!='X'";
        if (!empty($buscar))
            $sql .= " AND nome_rs LIKE '%{$buscar}%' OR cpf_cnpj LIKE '%{$buscar}%'";
        $result = $this->executeSQL($sql);
        return $this->fetchArrayObj($result)??null;
    }

    public function buscarTodosFuncionarios($buscar = null)
    {
        $sql = "SELECT p.id, cpf_cnpj, nome_rs, fone1, fone2, email, origem_cad, rg, inscr_estadual,  p.sts, f.id as id_funcionario, f.token, f.senha,f2.id as id_funcao, f2.descr as funcao FROM pessoa as p
                    INNER JOIN funcionario as f on f.id_pessoa=p.id
                    INNER JOIN funcao f2 on f.id_funcao = f2.id
                    WHERE p.sts!='X' AND f.sts!='X'";
        if (!empty($buscar))
            $sql .= " AND p.nome_rs LIKE '%{$buscar}%' OR p.cpf_cnpj LIKE '%{$buscar}%'";
        $result = $this->executeSQL($sql);
        return $this->fetchArrayObj($result)??null;
    }

    public function buscarFuncionarioCpfCnpj($cpfcnpj): ?PessoaModel{

        $sql = "SELECT p.id, p.id_endereco, cpf_cnpj, nome_rs, fone1, fone2, email, origem_cad, rg, inscr_estadual,  p.sts, f.id as id_funcionario, f.token, f.senha, f2.id as id_funcao, f2.descr as funcao 
                    FROM pessoa as p
                    INNER JOIN funcionario as f on f.id_pessoa=p.id
                    INNER JOIN funcao f2 on f.id_funcao = f2.id
                    WHERE p.cpf_cnpj=? AND f.sts!='X'";
        $params = array($cpfcnpj);
        $result = $this->executeSQL($sql,$params);
        if ($this->count($result)>0){
            return $this->fetchOneClass($result,$this->classModel);
        } else {
            return null;
        }

    }

    public function buscarFuncionarioId($id)
    {
        $sql = "SELECT p.id, p.id_endereco, cpf_cnpj, nome_rs, fone1, fone2, email, origem_cad, rg, inscr_estadual,  p.sts, f.id as id_funcionario, f.token, f.senha,f2.id as id_funcao, f2.descr as funcao 
                    FROM pessoa AS p 
                    INNER JOIN funcionario as f on f.id_pessoa=p.id 
                    INNER JOIN funcao as f2 on f2.id=f.id_funcao
                    WHERE p.id=? AND p.sts!='X'";
        $params = array($id);
        $result = $this->executeSQL($sql,$params);
        if ($this->count($result) > 0) {
            return $this->fetchArrayObj($result);
        } else {
            return null;
        }
    }

    public function buscarFuncionarioModelId($codusuario):?PessoaModel
    {
        $sql = "SELECT p.id, cpf_cnpj, nome_rs, fone1, fone2, email, origem_cad, rg, inscr_estadual,  p.sts, f.id as id_funcionario, f.token, f.senha,f2.id as id_funcao, f2.descr as funcao 
                    FROM pessoa as p
                    INNER JOIN funcionario as f on f.id_pessoa=p.id
                    INNER JOIN funcao f2 on f.id_funcao = f2.id
                    WHERE p.id=? AND p.sts!='X'";
        $params = array($codusuario);
        $result = $this->executeSQL($sql,$params);
        if ($this->count($result) > 0) {
            return $this->fetchOneClass($result,$this->classModel);
        } else {
            return null;
        }
    }

    public function inserirFuncionario(PessoaModel $pessoaModel, EnderecoModel $enderecoModel, FuncionarioModel $funcionarioModel){

        $funcoesClass = new FuncoesClass();
        $funcionarioDao = new FuncionarioDao();

        $this->beginTrasaction();

        // ENDEREÇO
        $sql = "INSERT INTO endereco (logradouro, numero, bairro, cep, latitude, longitude, complemento, id_municipio, sts, dtcad) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $parametros = array($enderecoModel->getLogradouro(), $enderecoModel->getNumero(), $enderecoModel->getBairro(), $enderecoModel->getCep(), 0, 0, $enderecoModel->getComplemento(), $enderecoModel->getIdMunicipio(), $enderecoModel->getSts(), $enderecoModel->getDtcad());
        if ($this->executeSQL($sql,$parametros)) {
            $id_endereco = $this->lastInsertId();

            // PESSOA
            $sql = "INSERT INTO pessoa (nome_rs, cpf_cnpj, fone1, fone2, email, sts, id_endereco) VALUES (?,?,?,?,?,?,?)";
            $parametros = array($pessoaModel->getNomeRs(), $pessoaModel->getCpfCnpj(), $pessoaModel->getFone1(), $pessoaModel->getFone2(), $pessoaModel->getEmail(), $pessoaModel->getSts(), $id_endereco);
            if ($this->executeSQL($sql,$parametros)) {
                $id_pessoa = $this->lastInsertId();
                $funcionarioDao->insert("id_pessoa, id_funcao, senha, token",array($id_pessoa,$funcionarioModel->getIdFuncao(), $funcionarioModel->getToken(), $funcoesClass->create_password_hash($funcionarioModel->getSENHA())));
                $this->commitTransaction();
                return true;
            } else{
                $this->rollBackTransaction();
                return false;
            }
        } else {
            $this->rollBackTransaction();
            return false;
        }
    }

    public function atualizarFuncionario(PessoaModel $pessoaModel, EnderecoModel $enderecoModel, FuncionarioModel $funcionarioModel){

        $funcoesClass = new FuncoesClass();
        $funcionarioDao = new FuncionarioDao();

        $this->beginTrasaction();

        $sql = "UPDATE endereco SET logradouro=?, numero=?, bairro=?, cep=?, latitude=?, longitude=?, complemento=?, id_municipio=? WHERE id=?";
        $values = array($enderecoModel->getLogradouro(),
            $enderecoModel->getNumero(),
            $enderecoModel->getBairro(),
            $enderecoModel->getCep(),
            $enderecoModel->getLatitude(),
            $enderecoModel->getLongitude(),
            $enderecoModel->getComplemento(),
            $enderecoModel->getIdMunicipio(),
            $enderecoModel->getId(),
        );


        if ($this->executeSQL($sql,$values)) {

            $sql = "UPDATE pessoa SET nome_rs=?, id_endereco=?, cpf_cnpj=?, fone1=?, fone2=?, email=?, sts=? WHERE id=?";
            $values = array($pessoaModel->getNomeRs(),
                $enderecoModel->getId(),
                $pessoaModel->getCpfCnpj(),
                $pessoaModel->getFone1(),
                $pessoaModel->getFone2(),
                $pessoaModel->getEmail(),
                $pessoaModel->getSts(),
                $pessoaModel->getId()
            );
            if ($this->executeSQL($sql, $values)) {
                $funcionarioDao->update("id_funcao", array($funcionarioModel->getIdFuncao(),  $pessoaModel->getId()), "id_pessoa=?");
                $this->commitTransaction();
                return true;
            } else {
                $this->rollBackTransaction();
                return false;
            }
        } else {
            $this->rollBackTransaction();
            return false;
        }
    }

    public function updateSenha(PessoaModel $pessoaModel){


        $sql = "UPDATE funcionario SET senha='" . $pessoaModel->getSenha() . "' WHERE id='" . $pessoaModel->getIdFuncionario() . "'";;
        if ($this->executeSQL($sql)){
            return true;
        } else {
            return false;
        }

    }

    public function buscarPessoaEmail($email): ?PessoaModel{

        $sql = "SELECT * FROM pessoa AS U WHERE U.email=? AND U.sts!='X'";
        $params = array($email);
        $result = $this->executeSQL($sql,$params);
        if ($this->count($result)>0){
            return $this->fetchOneClass($result,$this->classModel);
        } else {
            return null;
        }

    }

    public function buscarPessoaCpfCnpjModel($cpfcnpj): ?PessoaModel{

        $sql = "SELECT * FROM pessoa AS U WHERE U.cpf_cnpj LIKE ? AND U.sts!='X'";
        $params = array($cpfcnpj);
        $result = $this->executeSQL($sql,$params);
        if ($this->count($result)>0){
            return $this->fetchOneClass($result,$this->classModel);
        } else {
            return null;
        }

    }

    public function buscarPessoaModelId($codusuario):?PessoaModel
    {
        $sql = "SELECT * FROM pessoa AS U WHERE U.id=? AND U.sts!='X'";
        $params = array($codusuario);
        $result = $this->executeSQL($sql,$params);
        if ($this->count($result) > 0) {
            return $this->fetchOneClass($result,$this->classModel);
        } else {
            return null;
        }
    }

    public function buscarPessoaId($codusuario)
    {
        $sql = "SELECT * FROM pessoa AS U WHERE U.id=? AND U.sts!='X'";
        $params = array($codusuario);
        $result = $this->executeSQL($sql,$params);
        if ($this->count($result) > 0) {
            return $this->fetchArrayObj($result);
        } else {
            return null;
        }
    }

    public function buscarTokenPessoa($token): ?array{

        $sql = "SELECT p.id, cpf_cnpj, nome_rs, fone1, fone2, email, origem_cad, rg, inscr_estadual,  p.sts, f.id as id_funcionario, f.token, f.senha,f2.id as id_funcao, f2.descr as funcao 
                    FROM pessoa as p
                    INNER JOIN funcionario as f on f.id_pessoa=p.id
                    INNER JOIN funcao f2 on f.id_funcao = f2.id
                    WHERE f.token LIKE ?";
        $params = array($token);
        $result = $this->executeSQL($sql,$params);
        $obj = $this->fetchArrayAssoc($result);
        if (!empty($obj)){
            return $obj[0];
        } else {
            return null;
        }

    }

    public function buscarIdToken(PessoaModel $pessoaModel){


        $sql = "SELECT * FROM funcionario WHERE token = '" . $pessoaModel->getToken() . "' AND id ='" . $pessoaModel->getIdFuncionario() . "'";
        $result = $this->executeSQL($sql);
        if ($this->count($result)>0){
            return $this->fetchArrayObj($result);
        } else {
            return false;
        }

    }

    public function updateToken($token, $id){
        $sql = "UPDATE funcionario SET token=? WHERE id=?";
        $result = $this->executeSQL($sql,array($token, $id));
        if ($result)
            return true;
        else
            return false;
    }

}