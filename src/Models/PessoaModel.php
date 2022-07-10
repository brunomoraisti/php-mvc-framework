<?php
namespace App\Models;

use App\Lib\FuncoesClass;

class PessoaModel {

    private $id;
    private $cpf_cnpj;
    private $id_endereco;
    private $nome_rs;
    private $fone1;
    private $fone2;
    private $email;
    private $origem_cad;
    private $rg;
    private $inscr_estadual;
    private $sts;
    private $dtcad;

    private $id_funcionario;
    private $token;
    private $senha;

    private $id_funcao;
    private $funcao;

    /**
     * @return mixed
     */
    public function getDtcad()
    {
        return $this->dtcad;
    }

    /**
     * @param mixed $dtcad
     * @return PessoaModel
     */
    public function setDtcad($dtcad)
    {
        $this->dtcad = $dtcad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdEndereco()
    {
        return $this->id_endereco;
    }

    /**
     * @param mixed $id_endereco
     * @return PessoaModel
     */
    public function setIdEndereco($id_endereco)
    {
        $this->id_endereco = $id_endereco;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getIdFuncionario()
    {
        return $this->id_funcionario;
    }

    /**
     * @param mixed $id_funcionario
     * @return PessoaModel
     */
    public function setIdFuncionario($id_funcionario)
    {
        $this->id_funcionario = $id_funcionario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdFuncao()
    {
        return $this->id_funcao;
    }

    /**
     * @param mixed $id_funcao
     * @return PessoaModel
     */
    public function setIdFuncao($id_funcao)
    {
        $this->id_funcao = $id_funcao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFuncao()
    {
        return $this->funcao;
    }

    /**
     * @param mixed $funcao
     * @return PessoaModel
     */
    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return PessoaModel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpfCnpj()
    {
        return $this->cpf_cnpj;
    }

    /**
     * @param mixed $cpf_cnpj
     * @return PessoaModel
     */
    public function setCpfCnpj($cpf_cnpj)
    {
        $this->cpf_cnpj = $cpf_cnpj;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomeRs()
    {
        return $this->nome_rs;
    }

    /**
     * @param mixed $nome_rs
     * @return PessoaModel
     */
    public function setNomeRs($nome_rs)
    {
        $this->nome_rs = $nome_rs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFone1()
    {
        return $this->fone1;
    }

    /**
     * @param mixed $fone1
     * @return PessoaModel
     */
    public function setFone1($fone1)
    {
        $this->fone1 = $fone1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFone2()
    {
        return $this->fone2;
    }

    /**
     * @param mixed $fone2
     * @return PessoaModel
     */
    public function setFone2($fone2)
    {
        $this->fone2 = $fone2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return PessoaModel
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrigemCad()
    {
        return $this->origem_cad;
    }

    /**
     * @param mixed $origem_cad
     * @return PessoaModel
     */
    public function setOrigemCad($origem_cad)
    {
        $this->origem_cad = $origem_cad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * @param mixed $rg
     * @return PessoaModel
     */
    public function setRg($rg)
    {
        $this->rg = $rg;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInscrEstadual()
    {
        return $this->inscr_estadual;
    }

    /**
     * @param mixed $inscr_estadual
     * @return PessoaModel
     */
    public function setInscrEstadual($inscr_estadual)
    {
        $this->inscr_estadual = $inscr_estadual;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return PessoaModel
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     * @return PessoaModel
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSts()
    {
        return $this->sts;
    }

    /**
     * @param mixed $sts
     * @return PessoaModel
     */
    public function setSts($sts)
    {
        $this->sts = $sts;
        return $this;
    }


    public function fromMap($getParams = []){

        $funcoesClass = new FuncoesClass();

        $this->setid($getParams["id_pessoa"]??"")
            ->setCpfCnpj($funcoesClass->removeCaracteres($getParams["cpf_cnpj"]??""))
            ->setIdEndereco($getParams["id_endereco"]??"")
            ->setNomeRs($getParams["nome_rs"]??"")
            ->setFone1($getParams["fone1"]??"")
            ->setFone2($getParams["fone2"]??"")
            ->setEmail($getParams["email"]??"")
            ->setInscrEstadual($getParams["inscr_estadual"]??0)
            ->setOrigemCad($getParams["origem_cad"]??"Intranet")
            ->setRg($getParams["rg"]??"")
            ->setSts(empty($getParams["sts"])?"I":"A")
            ->setDtcad($funcoesClass->pegarDataAtualBanco());
    }


}
