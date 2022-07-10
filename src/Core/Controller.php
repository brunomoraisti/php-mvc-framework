<?php

namespace App\Core;

use App\Controllers\ErroController;
use App\Dao\PessoaDao;
use App\Dao\SisModuloDao;
use App\Lib\AlertaClass;
use App\Lib\CookieClass;
use App\Lib\FuncoesClass;
use App\Lib\JwtTokenClass;
use App\Lib\SessaoClass;

/**
 * Esta classe é responsável por instanciar um model e chamar a view correta
 * passando os dados que serão usados.
 */
class Controller
{

    public function __construct()
    {
    }

    public function response($objArray = [], $responseCode = 200, $type="application/json" )
    {
        header_remove();
        header("Content-type:{$type};charset=utf-8");
        header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
        header("Pragma: no-cache"); //HTTP 1.0
        http_response_code($responseCode);
        if ($type==="application/json")
            echo json_encode($objArray, JSON_UNESCAPED_UNICODE);
        else
            echo $objArray;
        exit;
    }

    public function redireciona(string $url)
    {
        header("location:" . $url);
        exit;

    }

    public function getJson()
    {
        // header('Content-type:application/json;charset=utf-8');
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getParams($valor){
        return $_GET[$valor]??'';
    }

    public function postParams($valor){
        return $_POST[$valor]??'';
    }

    public function requestMethod($method = 'POST', $api = false)
    {
        if ($_SERVER['REQUEST_METHOD'] != $method) {
            if ($api) {
                $retorno['error'] = true;
                $retorno['msg'] = "Metodo incorreto";
                $this->response($retorno);
            } else {
                $this->redireciona('/');
            }
        }
    }

    public function requestOrigem(){
        $permission_domains = CONFIG_SECURITY['permission_domains'];
        $request = $_SERVER['SERVER_NAME'];

        if (in_array($request, (array)$permission_domains)) {
            return true;
        } else {
            $retorno['error'] = true;
            $retorno['msg'] = "Origem não permitida";
            $this->response($retorno);
        }
    }

    public function modeDeveloper(){
        if (strpos($_SERVER['SERVER_NAME'],"localhost")){
            return true;
        } else {
            return false;
        }

    }

    public function getServicosModulo(){
        $sisModuloDao = new SisModuloDao();
        $alertaDao = new AlertaClass();
        $controller = explode("/", $_SERVER["REQUEST_URI"]);
        $modulo = $controller[1]??"";
        $id = SessaoClass::pegarCampo("id");

        if (!empty($modulo)) {
            $servicos = $sisModuloDao->buscaServicosUsuario($id,$modulo);
            if (empty($servicos)){
                $alertaDao->danger("Sem privilégio de acesso!","./");
            } else {
                $data["TITLE"] = $servicos[0]["titulo_modulo"];
                $data["TITLEIMAGE"] = $servicos[0]["icone_modulo"];
                $data["TITLEBREADCRUMB"] = "<li class='breadcrumb-item-custom'><a href='/'>Inicio</span></a><i class='mdi mdi-chevron-right mx-1' aria-hidden='true'></i></li><li class='breadcrumb-item-custom '><a href='/" . $modulo . "/'>" . $servicos[0]["titulo_modulo"] . "</a></li>";
                $data["SERVICOS"] = $servicos;

                return $data;
            }
        } else {
            $alertaDao->danger("Sem privilégio de acesso!","/");
        }
    }

    public function getServico(){
        $sisModuloDao = new SisModuloDao();
        $alertaDao = new AlertaClass();

        $controller = explode("/", $_SERVER["REQUEST_URI"]);
        $moduloParams = $controller[1]??"";
        $servicoParams = explode("-", $controller[2]);
        $moduloUrl = $moduloParams??"";
        $servicoUrl = $servicoParams[0]??"";
        $id = SessaoClass::pegarCampo("id");
        if (!empty($servicoUrl)) {
            $servico = $sisModuloDao->buscaServicoUsuario($id,$moduloUrl, $servicoUrl)[0];
            if (empty($servico)){
                $alertaDao->danger("Sem privilégio de acesso!","/");
            } else {
                $data["TITLE"] = $servico["titulo_servico"];
                $data["TITLEIMAGE"] = $servico["icone_servico"];
                $data["TITLEBREADCRUMB"] = "<li class='breadcrumb-item-custom'><a href='/'>Inicio</span></a><i class='mdi mdi-chevron-right mx-1' aria-hidden='true'></i></li><li class='breadcrumb-item-custom '><a href='/" . $moduloUrl . "/'>" . $servico["titulo_modulo"] . "</a></li><i class='mdi mdi-chevron-right mx-1' aria-hidden='true'></i></li><li class='breadcrumb-item-custom '><a href='/{$moduloUrl}/{$servicoUrl}/'>" . $servico["titulo_servico"] . "</a></li>";
                $data["SERVICO"] = $servico;

                $data["GETPARAMS"]["buscar"] = $this->getParams("buscar") ?: "";
                $data["GETPARAMS"]["pg"] = $this->getParams("pg") ?: "1";
                $data["SERVICO"]["url"] = "{$moduloUrl}/{$servicoUrl}";

                return $data;
            }
        } else {
            $alertaDao->danger("Serviço não encontrado!","./");
        }
    }

    public function pageNotFound(){
        (new ErroController())->index();
        exit();
    }


}
