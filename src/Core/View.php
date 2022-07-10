<?php

namespace App\Core;

use App\Components\NavbarComponents;
use App\Lib\AlertaClass;
use App\Lib\FuncoesClass;
use Twig\Environment;
use Twig\Extension\EscaperExtension;
use Twig\Loader\FilesystemLoader;

class View
{

    /**
     * @var string
     */
    private $alertaMsgRecebida;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var NavbarComponents
     */
    protected $navbar;

    private $viewDirectory = null;
    private $viewFile = null;
    private $viewUrl = null;

    private $headerAuthor;
    private $headerTitle;
    private $headerDescription;
    private $headerUrl;
    private $headerImage;
    private $headerKeywords;
    private $headerColor;
    private $headerFbAppId;

    /**
     * @var string
     */
    private $titlePage;

    public function __construct(){
        $this->controller = new Controller();

    }

    protected function navigationBottom($menuAtivo = 1, $data = null)
    {
        $dataSessao = $data['SESSAO'];

        $menuAtivo == 1 ? $menu[1] = "navigation-menu-item-active" : $menu[1] = "";
        $menuAtivo == 2 ? $menu[2] = "navigation-menu-item-active" : $menu[2] = "";
        $menuAtivo == 3 ? $menu[3] = "navigation-menu-item-active" : $menu[3] = "";
        $menuAtivo == 4 ? $menu[4] = "navigation-menu-item-active" : $menu[4] = "";


        return (new Page)->render("components/navigation_bottom", [
            "nomeUsuario" => $dataSessao['primeironome'],
            "emailUsuario" => $dataSessao['email'],
            "imagemUsuario" => $dataSessao['IMAGEM']??"",
            "menu1" => $menu[1],
            "menu2" => $menu[2],
            "menu3" => $menu[3],
            "menu4" => $menu[4],
        ], false);
    }

    protected function navbar($data = null)
    {

        if (isset($data['SESSAO']['email'])) {
            $dataSessao = $data['SESSAO'];
            $primeiroNome = isset($dataSessao['primeironome']) ? $dataSessao['primeironome'] : null;
            $perfil = '';
        } else {
            $primeiroNome = '';
            $perfil = 'd-none';
        }

        $urlNavbar = explode('/', substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1));
        $urlNavbar = $urlNavbar[0] ?? '';
        $btnVoltarNavbar = $urlNavbar != "" ? "" : "d-none";

        return (new Page)->render("components/navbar", [
            "btnVoltar" => $btnVoltarNavbar,
            "primeiroNome" => $primeiroNome,
            "perfil" => $perfil
        ], false);

    }

    protected function head()
    {

        //VERIFICA SE TEM MENSAGENS A SEREM MOSTRADAS AO USUÁRIO
        $alertaClass = new AlertaClass();
        $this->alertaMsgRecebida = $alertaClass->verificaMsg();

        header("Cache-Control: max-age=1800, public");
        return (new View())->render("components/head",[
            "author" => $this->headerAuthor,
            "title" => $this->headerTitle,
            "description" => $this->headerDescription,
            "url" => $this->headerUrl,
            "image" => $this->headerImage,
            "keywords" => $this->headerKeywords,
            "color" => $this->headerColor,
            "fbAppId" => $this->headerFbAppId,
            "configVersionCode" => CONFIG_VERSION_CODE]);

    }

    //CARREGA FIM HTML
    protected function javascript(){

        $result = '';

        $result = (new View())->render("components/javascript",[
            "configVersionCode" => CONFIG_VERSION_CODE], false);

        if (file_exists(dirname(__DIR__, 2) . "/templates/{$this->viewDirectory}/{$this->viewDirectory}.js")) {
            $result .= "<!--SCRIPT CONTROLLER-->\n";
            $result .= "<script>";
            $result .= file_get_contents(dirname(__DIR__,2)."/templates/{$this->viewDirectory}/{$this->viewDirectory}.js");
            $result .= "</script>";
        }

        if (file_exists(dirname(__DIR__, 2) . "/templates/{$this->viewUrl}/{$this->viewFile}.js")) {
            $result .= "<!--SCRIPT VIEW-->\n";
            $result .= "<script>";
            $result .= file_get_contents(dirname(__DIR__,2)."/templates/{$this->viewUrl}/{$this->viewFile}.js");
            $result .= "</script>";
        }

        //MOSTRA MENSAGEM AO USUÁRIO SE TIVER
        $result .= $this->alertaMsgRecebida;

        return $result;

    }

    //CARREGA RODAPE HTML
    protected function footer($data = null)
    {
        return (new View())->render("components/footer",[
            "nameFull" => CONFIG_DEVELOPER['nameFull']
        ], false);

    }

    protected function setHead($title = null, $description = null, $image = null){
        $funcoesClass = new FuncoesClass();
        $this->controller = new Controller();

        $this->titlePage = $title;

        $this->headerAuthor = CONFIG_HEADER['author'];
        $this->headerTitle = empty($title) ? CONFIG_HEADER['title'] : CONFIG_SITE['name'] . " › " . $title;
        $this->headerDescription = empty($description) ? CONFIG_HEADER['description'] : $description;
        $this->headerUrl = $funcoesClass->pegarUrlAtual();
        $this->headerImage = empty($image) ? CONFIG_HEADER['image'] : $image;
        $this->headerKeywords = CONFIG_HEADER['keywords'];
        $this->headerColor = CONFIG_HEADER['color'];
        $this->headerFbAppId = CONFIG_HEADER['fbAppId'];
    }

    //CARREGA RODAPE HTML
    protected function title($data = [])
    {
        if (!empty($this->titlePage))
            return "<header class='view-navbar'><div class='container my-container'><h1 class='h1-title'>$this->titlePage</h1></div></header>";
        else
            return "";
    }

    protected function titleBreadcrumb($data = []){
        if (!empty($data["TITLE"]))
            return '<header class="view-navbar">
                        <div class="container my-container pt-1">
                            <div class="div-title-page px-2 px-0">
                                <div class="row">
                                    <div style="vertical-align: top"><span class="mdi mdi-48px '.$data["TITLEIMAGE"].'"></span></div>
                                    <div class="col p-0">
                                        <div class="col row pb-2 pt-4">
                                            <nav aria-label="breadcrumb">
                                                <ul class="breadcrumb breadcrumb-custom" style="line-height: 0">
                                                    '.$data["TITLEBREADCRUMB"].'
                                                </ul>
                                            </nav>
                                        </div>
                                        <div class="col row pt-0">
                                            <p class="title-page"> '.$data["TITLE"].'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>';
        else
            return "";
    }

    public function render(string $view, $data = [], $print = true, $cache = false){

        $loader = new FilesystemLoader(dirname(__DIR__,2).'/templates');
        if ($cache)
            $twig = new Environment($loader, ['debug' => CONFIG_DISPLAY_ERROR_DETAILS, 'cache' => dirname(__DIR__, 2) . '/templates/cache']);
        else
            $twig = new Environment($loader,['debug' => CONFIG_DISPLAY_ERROR_DETAILS]);


        $retorno = '';

        $varsDefault = [
            "URL" => CONFIG_URL,
        ];

        $data = array_merge($varsDefault,$data);


        $this->setView($view);

        if (file_exists(dirname(__DIR__, 2) . "/templates/{$this->viewDirectory}/{$this->viewDirectory}.css")) {
            $result = "<!--STYLE CONTROLER-->\n";
            $result .= "<style>";
            $result .= file_get_contents(dirname(__DIR__,2)."/templates/{$this->viewDirectory}/{$this->viewDirectory}.css");
            $result .= "</style>";
            $retorno = $result;
        }

        if (file_exists(dirname(__DIR__, 2) . "/templates/{$this->viewDirectory}/{$this->viewFile}/{$this->viewFile}.css")) {
            $result = "<!--STYLE VIEW-->\n";
            $result .= "<style>";
            $result .= file_get_contents(dirname(__DIR__,2)."/templates/{$this->viewDirectory}/{$this->viewFile}/{$this->viewFile}.css");
            $result .= "</style>";
            $retorno .= $result;
        }

        if ($this->viewFile === "index") {
            if (file_exists(dirname(__DIR__, 2) . "/templates/{$this->viewDirectory}/{$this->viewDirectory}.html.twig")) {
                //include_once dirname(__DIR__, 2) . "/templates/{$this->viewDirectory}/{$this->viewDirectory}.php";
                //$result .= str_replace($keysMap,$vars, file_get_contents(dirname(__DIR__,2)."/templates/{$this->viewDirectory}/{$this->viewDirectory}.php"));
                $retorno .= $twig->render("{$this->viewDirectory}/{$this->viewDirectory}.html.twig",$data);
            }else {
                //include_once dirname(__DIR__, 2) . "/templates/erro/erro.php";
                //$result .= str_replace($keysMap,$vars, file_get_contents(dirname(__DIR__,2)."/templates/erro/erro.php"));
                $retorno .= $twig->render("erro/erro.html.twig",$data);
            }
        } else if (file_exists(dirname(__DIR__, 2) . "/templates/{$view}/{$this->viewFile}.html.twig")) {
            //include_once dirname(__DIR__, 2) . "/templates/{$view}/{$this->viewFile}.php";
            //$result .= str_replace($keysMap,$vars, file_get_contents(dirname(__DIR__,2)."/templates/{$view}/{$this->viewFile}.php"));
            $retorno .= $twig->render("{$view}/{$this->viewFile}.html.twig",$data);
        } else {
            //include_once dirname(__DIR__, 2) . "/templates/erro/erro.php";
            //$result .= str_replace($keysMap,$vars, file_get_contents(dirname(__DIR__,2)."/templates/erro/erro.php"));
            $retorno .= $twig->render("erro/erro.html.twig",$data);

        }

        if ($print)
            echo $retorno;
        else
            return $retorno;
    }

    protected function addCss(string $url)
    {
        return "<link href='{$url}?v=" . CONFIG_VERSION_CODE . "' rel='stylesheet'>";
    }

    protected function addJs(string $url)
    {
        return "<script src='{$url}?v=" . CONFIG_VERSION_CODE . "'></script>";
    }

    protected function setView($url)
    {
        $controller = explode("/", $url);

        $this->viewUrl = $url;
        $this->viewDirectory = $controller[0];
        $this->viewFile = $controller[1];
    }

    protected function addServiceWork(){
        return "<script>
                if ('serviceWorker' in navigator) {
                     window.addEventListener('load', () => {
                        navigator.serviceWorker.register('/sw.js',{scope: './'}).then(function (registration) {
                            //console.log('ServiceWorker registration successful with scope: ', registration.scope);
                            }, function (err) {
                            //console.log('ServiceWorker registration failed: ', err);
                            }
                        );
                    });
                }
            </script>";
    }

}