# Framework MVC com PHP
Framework em PHP Puro desenvolvido por @brunomoraisti utilizando a arquitetura MVC.

## COMO FUNCIONA O FRAMEWORK?
https://seudominio.com/controller/metodo-controle/parametro1/parametro2/param....

## COMO FUNCIONA A API?
https://seudominio.com/api/nome_da_rota

Todo tráfego passo pelo index principal, a aplicação faz um autoloader dentro da pasta Application e chama o controler específico com o método index, a qual é o método padrão, caso a pessoa não especifique.

## ESTRUTURA

    meu-projeto
    ├── config/
    ├── docker/
    ├── public/
    │   ├── index.php
    │   └── assets/
    │       ├── css/
    │       │   └── style.css -> Arquivo de personalização do CSS
    │       ├── img/
    │       │   ├── ic_logonavegador.png
    │       │   ├── logo-branco.png
    │       │   └── logo.png
    │       ├── js/
    │       │   └── login.js -> Funções globais javascript
    │       ├── plugin/
    │       └── upload/   
    ├── src/
    │   ├── Api/
    │   ├── Components/
    │   │   ├── Components.php -> Classe de componentes html
    │   │   └── Table.php -> Classe para construir uma tabela de forma dinamica
    │   ├── Controllers/
    │   │   └── HomeController.php
    │   ├── Core/
    │   │   ├── Database/
    │   │   │   ├── Connect.php]
    │   │   │   ├── Crud.php
    │   │   │   └── DataLayerTrait.php
    │   │   ├── App.php
    │   │   ├── Controller.php
    │   │   ├── Page.php
    │   │   └── View.php
    │   ├── Dao/
    │   │   ├──
    │   │   └── UsuarioDao.php
    │   ├── Lib/
    │   │   └── AlertaClass.php
    │   │   └── CookieClass.php
    │   │   └── FuncoesClass.php
    │   │   └── PushNotificationClass.php
    │   │   └── SessaoClass.php
    │   │   └── VariavelClass.php
    │   └── Models/
    │       └── UsuarioModel.php
    │
    ├── template/
    │
    ├── .htaccess
    ├── index.php
    ├── manifest.json -> Arquivo de configuração do PWA
    └── sw.js -> Service Worke para funcionamento do PWA
    
    #config/ → 
    Pasta de configurações e de informações do site

    #controllers/ → 
    Este diretório armazenará todos os controladores da aplicação que recebe os dados informados pelo usuário e decide o que fazer com eles e cada método deve realizar uma ação ou chamar view. Além disso, toda classe criada herda os métodos da classe Controller do arquivo armazenado em Application/core/Controller.php que será discutido em breve.
    
    #core/ → 
    Neste diretório será armazenado três arquivos: App.php que é responsavel por tratar a URL decidindo qual controlador e qual método deve ser executado; Controller.php responsável por chamar o model, view e pageNotFound que são herdados para as classes no diretório Application/controllers; E por último, o arquivo Database.php que armazena a conexão com o banco de dados.
    
    #models/ → 
    Aqui fica a entidade que corresponde a tabela do banco com os campos GET e SET de cada campo.
    
    #dao/  →  
    Aqui fica a lógica das suas entidades, no nosso caso usaremos classes que irá interagir com o banco de dados e fornecer tais dados para o controle a qual passará para view.

    views/ → 
    As views serão responsável por interagir com o usuário. Uma das suas principais características é que cada view sabe como exibir um model.
    
    #.htaccess → 
    Neste arquivo, apenas negaremos a navegação no diretório com a opção Options -Indexes.
    
#INICIO
    Preencha as informações da pasta config 

#GERAR FAVICON
https://www.favicon-generator.org/

## TESTAR
    Configure php como variavel de ambiente e execute o comando abaixo
    php -S localhost:8080 -t ./public

## GERAR ARQUIVOS MINIFICADOS CSS E JS
    Acesse seu site http://seusite.com/config/build

## FRAMEWORK FRONT-END
    https://getstisla.com/ -> versão 2.2
    https://getbootstrap.com/ -> versão 4.6

## COMANDOS
    Otimizar autoloader para produção
    composer dump-autoload --optimize