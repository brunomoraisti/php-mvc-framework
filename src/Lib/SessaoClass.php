<?php
namespace App\Lib;

use App\Core\Controller;
use App\Dao\PessoaDao;
use App\Models\PessoaModel;
use App\Models\UsuarioModel;

/**
 * Created by PhpStorm.
 * User: Bruno Morais
 * Email: brunosm08@gmail.com
 * Date: 13/06/2017
 * Time: 15:17
 */
class SessaoClass
{
    protected static $nomeSessao = "SESSION-APP";

    public function __construct()
    {
    }

    public static function start(){
        @ob_start();
        session_name(self::$nomeSessao);
        session_start();
    }

    private static function end(){
        session_write_close();
        ob_end_flush();
    }

    public static function apagaSessao()
    {
        self::start();

        session_unset(); // Eliminar todas as vari�veis da sess�o
        session_destroy(); // Destruir a sess�o

        self::end();
    }

    public static function gravarCampo($nomeCampo, $valorCampo)
    {
        self::start();
        $_SESSION[$nomeCampo]= $valorCampo;
        self::end();
    }

    public static function pegarCampo($nomeCampo)
    {
        $valorRetorno = null;
        self::start();
        if (isset($_SESSION[$nomeCampo]))
            $valorRetorno = $_SESSION[$nomeCampo];
        self::end();

        return $valorRetorno;
    }

    public static function apagaCampo($nomeCampo)
    {
        self::start();
        unset($_SESSION[$nomeCampo]);
        self::end();
    }

    /*public function verificaLoginSessao()
    {
        $email = $this->pegarCampo("EMAIL");

        if ((!isset($email)) || empty($email)) {
            header("location: /login/logoff");
            exit;
        } else {
            return true;
        }
    }*/

    public static function setDataSession(PessoaModel $dados){

        self::apagaSessao();

        self::gravarCampo("id", $dados->getId());
        self::gravarCampo("cpf_cnpj", $dados->getCpfCnpj());
        self::gravarCampo("nome_rs", $dados->getNomeRs());
        self::gravarCampo("fone1", $dados->getFone1());
        self::gravarCampo("fone2", $dados->getFone2());
        self::gravarCampo("email", $dados->getEmail());
        self::gravarCampo("origem_cad", $dados->getOrigemCad());
        self::gravarCampo("rg", $dados->getRg());
        self::gravarCampo("inscr_estadual", $dados->getInscrEstadual());

        self::gravarCampo("id_funcionario", $dados->getIdFuncionario());
        self::gravarCampo("token", $dados->getToken());

        self::gravarCampo("id_funcao", $dados->getIdFuncao());
        self::gravarCampo("funcao", $dados->getFuncao());

        self::gravarCampo("primeironome", explode(" ", $dados->getNomeRs())[0]);

    }

    public static function getDataSession(){

        $dados['id'] = self::pegarCampo("id");
        $dados['cpf_cnpj'] = self::pegarCampo("cpf_cnpj");
        $dados['nome_rs'] = self::pegarCampo("nome_rs");
        $dados['fone1'] = self::pegarCampo("fone1");
        $dados['fone2'] = self::pegarCampo("fone2");
        $dados['email'] = self::pegarCampo("email");
        $dados['origem_cad'] = self::pegarCampo("origem_cad");
        $dados['rg'] = self::pegarCampo("rg");
        $dados['inscr_estadual'] = self::pegarCampo("inscr_estadual");

        $dados['id_funcionario'] = self::pegarCampo("id_funcionario");
        $dados['token'] = self::pegarCampo("token");

        $dados['id_funcao'] = self::pegarCampo("id_funcao");
        $dados['funcao'] = self::pegarCampo("funcao");

        $dados['primeironome'] = self::pegarCampo("primeironome");
        $dados['redireciona'] = self::pegarCampo("redireciona");

        return $dados;
    }

    public static function login(){
        $funcoes = new FuncoesClass();
        $cookie = new CookieClass();
        $usuarioDao = new PessoaDao();
        $jwtTokenClass = new JwtTokenClass();
        $controller = new Controller();

        $codusuarioSessao = self::pegarCampo("id");
        $tokenuser = $cookie->pegarCampo("token");
        if (empty($codusuarioSessao)) {

            if (empty($tokenuser)) {
                self::gravarCampo("redireciona", $funcoes->pegarUrlAtual());
                $controller->redireciona("/usuario/logoff");

            } else {
                $dataToken = $jwtTokenClass->decode($tokenuser);
                if ($dataToken) {
                    $codusuarioCookie = $dataToken->data->id;
                    self::setDataSession($usuarioDao->buscarFuncionarioModelId($codusuarioCookie));
                    return true;
                } else {
                    self::gravarCampo("redireciona", $funcoes->pegarUrlAtual());
                    $controller->redireciona("/usuario/logoff");
                }
            }
        } else {
            return true;
        }
    }
}
