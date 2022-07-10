<?php

namespace App\Controllers;


use App\Core\Controller;
use App\Core\Page;
use App\Core\View;
use App\Dao\EnderecoDao;
use App\Dao\EstadoDao;
use App\Dao\MunicipioDao;
use App\Dao\PessoaDao;
use App\Lib\AlertaClass;
use App\Lib\CookieClass;
use App\Lib\EmailClass;
use App\Lib\FuncoesClass;
use App\Lib\HttpClass;
use App\Lib\JwtTokenClass;
use App\Lib\SessaoClass;
use App\Models\EnderecoModel;
use App\Models\PessoaModel;
use App\Models\RecuperaSenhaModel;
use DateTimeImmutable;
use Firebase\JWT\JWT;

class UsuarioController extends Controller
{
    /*
    * chama a view index.php   /
    */
    public function index($getParametro = null)
    {
        $this->redireciona("/");

    }

    public function meusdados($getParametro = null)
    {
        SessaoClass::login();

        $usuarioDao = new PessoaDao();
        $funcoesClass = new FuncoesClass();
        $enderecoDao = new EnderecoDao();
        $estadoDao = new EstadoDao();
        $municipioDao = new MunicipioDao();

        // CONSULTA NO BANCO
        $data['PESSOA'] = $usuarioDao->buscarFuncionarioId(SessaoClass::pegarCampo('id'))[0];
        $data['PESSOA']->cpf_cnpj = $funcoesClass->formatCpfCnpjUsuario($data['PESSOA']->cpf_cnpj,true);
        $data['ENDERECO'] = $enderecoDao->buscaEnderecoId($data["PESSOA"]->id_endereco);
        $data['ESTADO'] = $estadoDao->select("*", "ORDER BY uf");
        $data['MUNICIPIO'] = $municipioDao->select("*", "WHERE uf=? ORDER BY uf",array($data["ENDERECO"]->uf));

        $data["enderecoComponent"] = (new Page)->render("components/div_endereco",$data,false );

        $head['TITLE'] = "Meus dados";

        (new Page())->pageDefault('usuario/meusdados',
            $head,
            $data
        );

    }

    public function meusDadosAction($getParametro = null)
    {
        SessaoClass::login();
        $this->requestMethod("POST");

        $sessaoClass = new SessaoClass();
        $alertaClass = new AlertaClass();
        $pessoaDao = new PessoaDao();
        $enderecoDao = new EnderecoDao();
        $pessoaModel = new PessoaModel();
        $funcoesClass = new FuncoesClass();
        $enderecoModel = new EnderecoModel();

        $pessoaModel->fromMap($_POST);
        $enderecoModel->fromMap($_POST);

        // ATUALIZA ENDEREÇO
        $atributos = "logradouro, numero, bairro, cep, latitude, longitude, complemento, id_municipio, sts, dtcad";
        $parametros = array($enderecoModel->getLogradouro(), $enderecoModel->getNumero(), $enderecoModel->getBairro(), $enderecoModel->getCep(), 0, 0, $enderecoModel->getComplemento(),$enderecoModel->getIdMunicipio(),$enderecoModel->getSts(), $enderecoModel->getDtcad(),$enderecoModel->getId());
        $enderecoDao->update($atributos,$parametros,"id=?");

        // ATUALIZA PESSOA
        $atributos = "email, fone1, fone2";
        $parametros = array($pessoaModel->getEmail(), $pessoaModel->getFone1(), $pessoaModel->getFone2());
        $result = $pessoaDao->update($atributos, $parametros, "id={$pessoaModel->getId()}");

        if ($result) {
            $resultUsuarioModel = $pessoaDao->buscarFuncionarioModelId($_POST['id_pessoa']);
            $sessaoClass->setDataSession($resultUsuarioModel);

            $alertaClass->success("Atualização realizada com sucesso!", "/usuario/meusdados");
        } else {
            $alertaClass->danger("Atualização realizada com sucesso!", "/usuario/meusdados");
        }
    }

    public function logoff()
    {
        $cookie = new CookieClass();
        SessaoClass::apagaSessao();
        $cookie->apagarCampo("TOKEN_USER");
        $this->redireciona("/usuario/login");
    }

    public function login($getParametro = null)
    {

        $head['TITLE'] = "Login";

        // CARREGA VIEW
        (new Page())->pageLogin('usuario/login',$head);

    }

    public function esquecisenha($getParametro = null)
    {
        $head['TITLE'] = "Esqueci senha";

        // CARREGA VIEW
        (new Page())->pageLogin('usuario/esquecisenha', $head);
    }

    public function novasenha($getParametro = null)
    {

        (new FuncoesClass())->setDisplayError();
        //PEGANDO PARAMETRO GET
        if (empty($getParametro[0])) {
            $this->redireciona("/usuario/login");
        } else {
            $data['TOKEN'] = $getParametro[0];
            $dataToken = (new JwtTokenClass())->decode($data["TOKEN"]);

            if ($dataToken){
                $pessoaDao = new PessoaDao();
                $data["PESSOA"] = $pessoaDao->buscarTokenPessoa($data['TOKEN']);
                if (!empty($data["PESSOA"])) {
                    $head['TITLE'] = "Nova senha";
                    // CARREGA VIEW
                    (new Page())->pageLogin('usuario/novasenha', $head, $data);
                } else {
                    (new AlertaClass())->warning("Token não encontrado","/usuario/login");
                }
            } else {
                (new AlertaClass())->warning("Token exipirou ou é inválido","/usuario/login");
            }
        }



    }

    public function addnovasenha()
    {
        $this->requestMethod("POST");

        $func = new FuncoesClass();
        $pessoaModel = new PessoaModel();
        $pessoaDao = new PessoaDao();
        $alerta = new AlertaClass();

        $pessoaModel->setId($_POST["id"]);
        $pessoaModel->setIdFuncionario($_POST["idfuncionario"]);
        $pessoaModel->setToken($_POST["token"]);
        $pessoaModel->setSenha($func->create_password_hash($_POST['cdsenha']));

        $dados = $pessoaDao->buscarIdToken($pessoaModel);

        if ($dados == false) {
            $alerta->warning('Sua solicitação expirou! Tente novamente.', '/usuario/esquecisenha');
        } else {
            if ($pessoaDao->updateSenha($pessoaModel) == false) {
                $alerta->warning('Aconteceu um erro, tente mais tarde', '/usuario/login');
            } else {
                $alerta->success("Alteração realizada!", '/usuario/login');
            }
        }

    }

    public function alterarsenha()
    {
        SessaoClass::login();
        $this->requestMethod("POST");

        $func = new FuncoesClass();
        $usuarioModel = new PessoaModel();
        $usuarioDao = new PessoaDao();
        $alerta = new AlertaClass();


        $usuarioModel->setSenha($_POST['senhaatual'])
            ->setId($_POST["id"])->setIdFuncionario($_POST['idfuncionario']);

        $resultUsuarioModel = $usuarioDao->buscarFuncionarioModelId($_POST["id"]);

        if (!empty($resultUsuarioModel)) {
            if ($func->verify_password_hash($usuarioModel->getSenha(), $resultUsuarioModel->getSenha())) {
                $usuarioModel->setSenha($func->create_password_hash($_POST['novasenha']));
                if ($usuarioDao->updateSenha($usuarioModel) == false) {
                    $alerta->warning('Aconteceu um erro, tente mais tarde', '/usuario/meusdados');
                } else {
                    $alerta->success("Alteração realizada!", '/usuario/meusdados');
                }
            } else {
                $alerta->danger("Senha atual não confere!", '/usuario/meusdados');
            }
        } else {
            $alerta->danger("Erro ao buscar usuário", '/usuario/meusdados');
        }
    }

}
