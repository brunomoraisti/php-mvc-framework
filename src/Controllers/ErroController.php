<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Page;
use App\Core\View;

class ErroController extends Controller
{
    /*
    * chama a view index.php do  /menu   ou somente   /
    */
    public function index()
    {
        (new Page())->pageErro('erro/index',['TITLE' => 'Página não encontrada']);
    }

    public function erro500()
    {
        (new Page())->pageErro('erro/500',['TITLE' => 'Ops! Erro no servidor']);
    }

    public function erro503()
    {
        (new Page())->pageErro('erro/503',['TITLE' => 'Ops! Erro no servidor']);
    }

    public function seminternet()
    {
        (new Page())->pageErro('erro/seminternet',['TITLE' => 'Sem internet']);
    }

    public function database()
    {
        (new Page())->pageErro('erro/database',['TITLE' => 'Erro no servidor']);
    }

    public function manutencao()
    {
        (new Page())->pageErro('erro/manutencao',['TITLE' => 'Erro no servidor']);
    }

}
