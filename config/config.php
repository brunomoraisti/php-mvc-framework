<?php
if (strpos($_SERVER['SERVER_NAME'],"localhost")){
    require_once(dirname(__DIR__,1).'/config/developerConfig.php');
} else {
    require_once(dirname(__DIR__,1).'/config/productionConfig.php');

}
/*ALTERE ESSA VARIAVEL TODA VEZ QUE QUISER ATUALIZAR O CSS E JAVASCRIPT*/
const CONFIG_VERSION_CODE = "1.0.0";

const CONFIG_MAINTENANCE = false;

const CONFIG_SECURITY = [
    "domain" => 'seudominio.com',
    "token" => 'suaChavetoken',
    "permission_domains" => ['dev.seudominio.com', 'seudominio.com', 'www.seudominio.com']
];

const CONFIG_SITE = [
    "color-primary" => "#2AB164",
    "name" => "Nome Site",
    "nameFull" => "Nome Site Completo",
    "email" => "contato@seusite.com",
    "phone" => "+55 63 0000-00000",
    "url" => "https://seusite.com",
    "domain" => "https://seusite.com",
    "andress" => "Cidade-ESTADO",
    "cnpj" => ""
];

const CONFIG_DEVELOPER = [
    "name" => "Nome Desenvolvedor",
    "nameFull" => "Nome Desenvolvedor Completo",
    "email" => "emaildesenvolvedor@dominio.com",
    "url" => "https://www.seusite.com"
];

// CONFIGURAÇÃO HEADER
const CONFIG_HEADER = [
    "author" => 'Nome autor',
    "title" => 'Nome do site',
    "description" => 'Descricao completa do site',
    "image" => 'https://seusite.com/assets/img/ic_logosocial.jpg',
    "keywords" => "palavras, chaves, site",
    "color" => CONFIG_SITE['color-primary'],
    "fbAppId" => "0"
];

// CONFIGURAÇÃO EMAIL
const CONFIG_EMAIL = [
    "host" => 'smtp.gmail.com',
    "userName" => "naoresponda@seusite.com",
    "password" => 'suasenha',
    "port" => '465',
    "smtpAuth" => true,
    "smtpSecure" => 'ssl',
    "from" => "naoresponda@seusite.com",
    "reply" => "naoresponda@seusite.com"
];

// CONFIGURAÇÃO HEADER
// URL: CHAVES https://www.google.com/u/1/recaptcha/admin
// EMAIL: email@gmail.com
const CONFIG_RECAPTCHA = [
    "chaveSite" => 'chavesite',
    "chaveSecreta" => 'chavesecreta',
];

const CONFIG_KEY_API_GOOGLE = "ChaveKeyGoolge";