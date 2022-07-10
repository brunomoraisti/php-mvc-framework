<?php
namespace App\Dao;

use BMorais\Database\Crud;
use App\Lib\FuncoesClass;
use App\Models\RecuperaSenhaModel;

class ModeloDao extends Crud{


    public function __construct()
    {
        $this->tableName = "modelo";
        $this->classModel = "ModeloModel";
    }

    public function buscarTodos($buscar = null){
        $sql = "SELECT m.id, m.id_fabricante, m.descr, m.sts, 
                m.dtcad, m.id_pessoa, p2.nome_rs as nomefabricante 
                FROM modelo as m
                    INNER JOIN fabricante as f on f.id=m.id_fabricante
                    INNER JOIN pessoa p2 on p2.id=f.id_pessoa
                    WHERE m.sts!='X'";
        if (!empty($buscar))
            $sql .= " AND p2.nome_rs LIKE '%{$buscar}%'";
        $sql .=" ORDER BY p2.nome_rs";

        $result = $this->executeSQL($sql);
        return $this->fetchArrayObj($result);
    }
}