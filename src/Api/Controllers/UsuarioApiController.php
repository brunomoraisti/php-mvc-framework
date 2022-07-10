<?php

namespace App\Api\Controllers;

use App\Api\Lib\RequestClass;
use App\Api\Lib\ResponseClass;
use App\Components\TemplateEmailComponents;
use App\Core\Controller;
use App\Dao\EstoqueDao;
use App\Dao\LogDao;
use App\Dao\MunicipioDao;
use App\Dao\RecuperaSenhaDao;
use App\Dao\PessoaDao;
use App\Lib\AlertaClass;
use App\Lib\CookieClass;
use App\Lib\EmailClass;
use App\Lib\FuncoesClass;
use App\Lib\HttpClass;
use App\Lib\JwtTokenClass;
use App\Lib\SessaoClass;
use App\Models\PessoaModel;
use App\Models\RecuperaSenhaModel;
use App\Models\UsuarioCidadeModel;
use App\Models\UsuarioModel;
use ReCaptcha\ReCaptcha;

class UsuarioApiController
{

    public function login(RequestClass $request)
    {
        $sessao = new SessaoClass();
        $func = new FuncoesClass();
        $pessoaModel = new PessoaModel();
        $usuarioDao = new PessoaDao();
        $jwtTokenClass = new JwtTokenClass();
        $cookieClass = new CookieClass();

        $cpf = $func->removeCaracteres($request->getPostVars()['cpf']??"");
        $senha = $request->getPostVars()['senha']??"";

        $pessoaModel->setCpfCnpj($cpf)->setSenha($senha);
        $usuarioResult = $usuarioDao->buscarFuncionarioCpfCnpj($cpf);
        $retorno = array();

        if (!empty($usuarioResult)) {

            if ($func->verify_password_hash($pessoaModel->getSenha(), $usuarioResult->getSenha())) {

                if ($usuarioResult->getSts() != "A") {

                    $retorno['error'] = true;
                    $retorno['msg'] = "Usuário inativo, entre em contato com o suporte!";

                } else {
                    $data['id'] = $usuarioResult->getId();
                    $token = $jwtTokenClass->encode(1440, $data);
                    $usuarioResult->setToken($token);

                    $redireciona = !empty($sessao->pegarCampo('redireciona')) ? $sessao->pegarCampo('redireciona') : '/';
                    $sessao->setDataSession($usuarioResult);
                    $data['id'] = $usuarioResult->getId();
                    $retorno['id'] = $usuarioResult->getId();
                    $retorno['error'] = false;
                    $retorno['token'] = $token;
                    $retorno['msg'] = "";
                    $retorno['redireciona'] = $redireciona;
                    $usuarioDao->updateToken($token,$usuarioResult->getIdFuncionario());

                }
            } else {
                $retorno['error'] = true;
                $retorno['msg'] = "Login ou senha incorreto!";
            }
        } else {
            $retorno['error'] = true;
            $retorno['msg'] = "Login ou senha incorreto!";
        }

        return $retorno;
    }

    public function recuperasenha(RequestClass $request)
    {

        $func = new FuncoesClass();

        $pessoaDao = new PessoaDao();

        $cpf = $_POST['cpf'];
        $cpf = $func->removeCaracteres($cpf);

        $pessoaModel = $pessoaDao->buscarFuncionarioCpfCnpj($cpf);

        if (!empty($pessoaModel)) {

            $pessoaModel->setToken((new JwtTokenClass())->encode(60,["id" => $pessoaModel->getId()]));

            if ($pessoaDao->updateToken($pessoaModel->getToken(),$pessoaModel->getIdFuncionario())) {

                $msge = TemplateEmailComponents::getTemplate(
                    "Recuperação de senha",
                    "Solicitação de recuperação de senha de acesso",
                    "Você quer criar uma nova senha, certo?",
                    CONFIG_SITE['url'] . "/usuario/novasenha/" . $pessoaModel->getToken(),
                    "Sim, criar nova senha");

                if (EmailClass::sendEmail("Recuperacao de Senha | " . $func->pegarDataHoraAtualUsuario(), $msge, array($pessoaModel->getEmail()))) {
                    $retorno['error'] = false;
                    $retorno['msg'] = "Se tiver cadastrado, você receberá um link para redefinir a senha1!";

                } else {
                    $retorno['error'] = true;
                    $retorno['msg'] = "Erro ao enviar email de recuperação";
                }
            } else {
                $retorno['error'] = true;
                $retorno['msg'] = "Erro ao cadastrar solicitação!";
            }
        } else {
            $retorno['error'] = false;
            $retorno['msg'] = "Se tiver cadastrado, você receberá um link para redefinir a senha2!";
        }

        return $retorno;

    }


}
