<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Lib\FuncoesClass;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class ConfigController extends Controller
{
    /*
    * chama a view index.php do  /menu   ou somente   /
    */
    public function index()
    {
        $this->redireciona("/");
    }

    /*
   * chama a view index.php do  /menu   ou somente   /
   */
    public function build()
    {
        if ($this->modeDeveloper()){

            unlink(dirname(__DIR__,2)."/public/assets/css/style.min.css");
            unlink(dirname(__DIR__,2)."/public/assets/js/script.min.js");

            // GERAR ARQUIVOS CSS MINIFICADOS
            $minCss = new CSS();
            $cssDir = scandir(dirname(__DIR__,2).""."/public/assets/css/");
            foreach ($cssDir as $cssItem){
                $cssFile = dirname(__DIR__,2)."/public/assets/css/{$cssItem}";
                if (is_file($cssFile) && pathinfo($cssFile)["extension"] ==  "css"){
                    $minCss->add($cssFile);
                }
            }
            $minCss->minify(dirname(__DIR__,2)."/public/assets/css/style.min.css");


            // GERAR ARQUIVOS JS MINIFICADOS
            $minJs = new JS();
            $jsDir = scandir(dirname(__DIR__,2).""."/public/assets/js/");
            foreach ($jsDir as $jsItem){
                $jsFile = dirname(__DIR__,2)."/public/assets/js/{$jsItem}";
                if (is_file($jsFile) && pathinfo($jsFile)["extension"] ==  "js"){
                    $minJs->add($jsFile);
                }
            }
            $minJs->minify(dirname(__DIR__,2)."/public/assets/js/script.min.js");

            echo "Gerado com sucesso";
        } else {
            $this->redireciona("/");
        }
    }

}
