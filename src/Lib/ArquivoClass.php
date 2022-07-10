<?php

namespace App\Lib;

class ArquivoClass{

    /**
     * FUNÇÃO ENVIAR ARQUIVOS DIVERSOS
     *
     * @param $arquivo
     * @param $destino
     * @return string
     */
    function enviaArquivo($arquivo, $destino)
    {

        // Pega extensão da imagem
        $nomeArquivo = $this->geraNomeArquivo($arquivo['name']);

        // Caminho de onde ficará a imagem
        $caminhoArquivo = $destino . "" . $nomeArquivo;
        $caminhoArquivo = $_SERVER['DOCUMENT_ROOT'] . $caminhoArquivo;


        // Faz o upload da imagem para seu respectivo caminho
        move_uploaded_file($arquivo["tmp_name"], $caminhoArquivo);

        return $nomeArquivo;
    }

    /**
     * FUNÇÃO DE COPIAR UM ARQUIVO
     *
     * @param $arquivo
     * @param $destino
     * @return string
     */
    function copiarArquivo($arquivo, $destino)
    {

        // Pega extensão da imagem
        $nomeArquivo = $this->geraNomeArquivo($arquivo['name']);

        copy($destino . $arquivo['tmp_name'], $destino . $nomeArquivo);

        return $nomeArquivo;
    }

    /**
     * FUNÇÃO PARA REMOVER ARQUIVO
     *
     * @param $caminho
     */
    function removeArquivo($caminho)
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $caminho))
            unlink($_SERVER['DOCUMENT_ROOT'] . $caminho);
    }

    /**
     * FUNÇÃO PARA GERAR NOME DO ARQUIVO NO FORMATO MD5
     *
     * @param string $arquivo
     * @return string
     */
    function geraNomeArquivo($arquivo = 'protocolo.pdf')
    {
        $funcoesClass = new FuncoesClass();
        $extensao = $this->pegaExtensaoArquivo($arquivo);
        $nomeArquivo = md5(uniqid(time()) . $funcoesClass->pegaIpUsuario()) . "." . $extensao;

        return $nomeArquivo;
    }

    /**
     * FUNÇÃO PARA PEGAR EXTENSÃO DE UM ARQUIVO
     *
     * @param $arquivo
     * @return mixed
     */
    function pegaExtensaoArquivo($arquivo)
    {

        // Pega extensão da imagem
        return pathinfo($arquivo, PATHINFO_EXTENSION);

    }
}