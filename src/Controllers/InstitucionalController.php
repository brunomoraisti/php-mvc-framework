<?php
namespace App\Controllers;

use App\Components\NavbarComponents;
use App\Core\Controller;
use App\Core\Page;
use App\Core\View;
use App\Lib\SessaoClass;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class InstitucionalController extends Controller
{
    /*
    * chama a view index.php   /
    */
    public function index($getParametro = null)
    {
        $this->redireciona("/");
    }

    public function privacidade(){

        // CARREGA VIEW
        (new Page())->pageDefault('institucional/privacidade',
            ['TITLE' => "Termo de uso e privacidade"],
            ["configSiteName" => CONFIG_SITE['name'],
                "configSiteNameFull" => CONFIG_SITE['nameFull'],
                "configSiteEmail" => CONFIG_SITE['email'],
                "configSitePhone" => CONFIG_SITE['phone'],
                "configSiteUrl" => CONFIG_SITE['url'],
                "configSiteDomain" => CONFIG_SITE['domain'],
                "configSiteAndress" => CONFIG_SITE['andress'],
                "configSiteCnpj" => CONFIG_SITE['cnpj'],
            ]
        );
    }

}
