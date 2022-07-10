<?php
namespace App\Dao;

use BMorais\Database\Crud;
use App\Lib\FuncoesClass;
use App\Models\RecuperaSenhaModel;

class FuncionarioDao extends Crud{


    public function __construct()
    {
        $this->tableName = "funcionario";
        $this->classModel = "FuncionarioModel";
    }

    public function buscarTodos($buscar=""){
        $sql = "SELECT f.id, f.id_pessoa, p.cpf_cnpj, p.id_endereco, p.nome_rs, p.fone1, p.fone2, p.email, p.inscr_estadual, p.origem_cad, p.rg, p.sts, p.dtcad FROM funcionario as f
                INNER JOIN pessoa as p on p.id=f.id_pessoa
                WHERE f.sts!='X' AND p.sts!='X'";
        if (!empty($buscar))
            $sql .= " AND (p.nome_rs LIKE '%{$buscar}%' OR p.cpf_cnpj LIKE '%{$buscar}%')";
        $sql .=" ORDER BY nome_rs";

        $result = $this->executeSQL($sql);
        return $this->fetchArrayObj($result);
    }
}