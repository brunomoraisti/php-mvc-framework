<?php
namespace App\Controllers;

use App\Components\NavbarComponents;
use App\Core\Controller;
use App\Core\Page;
use App\Core\View;
use App\Dao\SisModuloDao;
use App\Lib\CookieClass;
use App\Lib\EmailClass;
use App\Lib\FuncoesClass;
use App\Lib\JwtTokenClass;
use App\Lib\SessaoClass;
use http\Exception;

class HomeController extends Controller
{
    /*
    * chama a view index.php   /
    */
    public function index($getParametro = null)
    {
        // VERIFICA SE ESTA LOGADO
        //SessaoClass::login();

        try {
            return (new Page())->pageDefault('home/index',
                ['TITLE' => "Início"],
            );
        } catch (\Error $e) {
            return $e;
        }
    }

}
