<?php
namespace App\Lib;

use Application\models\CookieModel;
use Application\models\DispositivosModel;

/**
 * Created by PhpStorm.
 * User: Bruno Morais
 * Email: brunosm08@gmail.com
 * Date: 13/06/2017
 * Time: 15:17
 */
class CookieClass
{

    public function __construct()
    {

    }

    public static function pegarCampo($nome){
        $valor = null;
        if (!empty($_COOKIE[$nome]))
            $valor = $_COOKIE[$nome];
        return $valor;
    }

    public static function gravarCampo($nome,$valor){

        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        //setcookie('cross-site-cookie', 'name', ['samesite' => 'None', 'secure' => true]);
        setcookie($nome, $valor , (time()+(365 * 24 * 3600)), "/", $domain);
        return true;

        //setcookie($nome, $valor , (time()+(365 * 24 * 3600)), "/; SameSite=None; Secure"); // < 7.3
        //setcookie($nome, $valor , ['samesite' => 'None', 'secure' => true]); // >= 7.3

    }

    public static function apagarCampo($nome){
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie($nome, '' , (time()-(365 * 24 * 3600)), "/", $domain);
    }

    public static function verificaCookieLogin(){

        if (!empty($_COOKIE['HASH'])){
            return true;
        } else {
            header("location: /login/logoff");
            exit;
        }

    }

    public static function gravarDadosNoCookie($dados){

        self::gravarCampo("HASH", $dados[0]->HASH);

    }

    public static function apagarCookies(){
        self::apagarCampo('HASH');
    }
}
