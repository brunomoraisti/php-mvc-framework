<?php

namespace App\Core;


use App\Components\NavbarComponents;
use App\Lib\CookieClass;
use App\Lib\JwtTokenClass;
use App\Lib\SessaoClass;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Page extends View
{

    public function pageDefault(string $view, $head = [], $data = [], $css = [], $js = [])
    {
        try {

            $this->setHead($head['TITLE'] ?? "");

            $data['SESSAO'] = SessaoClass::getDataSession();
            $data['JWT'] = (new JwtTokenClass())->encode();

            $data['head'] = $this->head();
            $data['navbar'] = $this->navbar($data);
            $data['title'] = $this->title();
            $data['main'] = $this->render($view, $data, false);
            $data['menu'] = $this->navigationBottom($data['MENU'] ?? 0, $data);
            $data['footer'] = $this->footer();
            $data['javascript'] = $this->javascript();
            $data['css'] = $this->addCssJsPage($css, "css");
            $data['js'] = $this->addCssJsPage($js, "js");

            $this->render("components/theme", $data);
        } catch (\Error $e) {
            return $e;
        }
    }

    public function pageModuloServicos(string $view, $data = [], $css = [], $js = [])
    {
        try {
            $controller = new Controller();
            SessaoClass::login();
            $data = $controller->getServicosModulo();

            $this->setHead($data['TITLE'] ?? "");

            $data['SESSAO'] = SessaoClass::getDataSession();
            $data['JWT'] = (new JwtTokenClass())->encode();

            $data['head'] = $this->head();
            $data['navbar'] = $this->navbar($data);
            $data['title'] = $this->titleBreadcrumb($data);
            $data['main'] = $this->render($view, $data, false);
            //$data['menu'] = $this->navigationBottom($data['MENU'] ?? 0, $data);
            $data['footer'] = $this->footer();
            $data['javascript'] = $this->javascript();
            $data['css'] = $this->addCssJsPage($css, "css");
            $data['js'] = $this->addCssJsPage($js, "js");

            $this->render("components/theme", $data);
        } catch (\Error $e) {
            return $e;
        }
    }

    public function pageServico(string $view, $data = [], $css = [], $js = [])
    {
        try {
            $controller = new Controller();

            $this->setHead($data['TITLE'] ?? "");

            $data['SESSAO'] = SessaoClass::getDataSession();
            $data['JWT'] = (new JwtTokenClass())->encode();

            $data['head'] = $this->head();
            $data['navbar'] = $this->navbar($data);
            $data['title'] = $this->titleBreadcrumb($data);
            $data['main'] = $this->render($view, $data, false);
            //$data['menu'] = $this->navigationBottom($data['MENU'] ?? 0, $data);
            $data['footer'] = $this->footer();
            $data['javascript'] = $this->javascript();
            $data['css'] = $this->addCssJsPage($css, "css");
            $data['js'] = $this->addCssJsPage($js, "js");

            $this->render("components/theme", $data);
        } catch (\Error $e) {
            return $e;
        }
    }

    public function pageLogin(string $view, $head = [], $data = [], $css = [], $js = [])
    {
        try {
            // VERIFICA LOGIN
            if (!empty(SessaoClass::pegarCampo('CODUSUARIO')))
                $this->controller->redireciona('/');

            $this->setHead($head['TITLE'] ?? "");

            $data['JWT'] = (new JwtTokenClass())->encode();

            $data['head'] = $this->head();
            $data['main'] = $this->render($view, $data, false);
            $data['footer'] = $this->footer();
            $data['javascript'] = $this->javascript();
            $data['css'] = $this->addCssJsPage($css, "css");
            $data['js'] = $this->addCssJsPage($js, "js");

            $this->render("components/theme", $data);
        } catch (\Error $e) {
            return $e;
        }

    }

    public function pageErro(string $view, $head = [], $data = [], $css = [], $js = [])
    {

        $this->setHead($head['TITLE'] ?? "");

        $data['head'] = $this->head();
        $data['main'] = $this->render($view, $data, false);
        $data['footer'] = $this->footer();
        $data['javascript'] = $this->javascript();
        $data['css'] = $this->addCssJsPage($css, "css");
        $data['js'] = $this->addCssJsPage($js, "js");

        $this->render("components/theme", $data);

    }


    private function addCssJsPage($cssjs = [], $type = "css"): array
    {
        $data = [];
        foreach ($cssjs as $item) {
            if ($type === "css")
                array_push($data, $this->addCss($item));
            else
                array_push($data, $this->addJs($item));

        }

        return $data;

    }

}