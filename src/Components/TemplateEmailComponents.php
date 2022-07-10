<?php

namespace App\Components;

use App\Core\View;
use App\Models\RecuperaSenhaModel;

class TemplateEmailComponents
{

    private static $titleEmail;
    private static $subTitleEmail;
    private static $descriptionEmail;
    private static $buttonLinkEmail;
    private static $buttonNameEmail;
    private static $colorPrimaryEmail;
    private static $urlEmail;
    private static $nameFullEmail;

    public function __construct(){
    }


    public static function getTemplate($title=null, $subTitle=null, $description=null, $buttonLink=null, $buttonName=null):string
    {
        self::$titleEmail = $title;
        self::$subTitleEmail = $subTitle;
        self::$descriptionEmail = $description;
        self::$buttonLinkEmail = $buttonLink;
        self::$buttonNameEmail = $buttonName;

        self::$colorPrimaryEmail = CONFIG_SITE['color-primary'];
        self::$urlEmail = CONFIG_SITE['url'];
        self::$nameFullEmail = CONFIG_SITE['nameFull'];

        $button = (!empty(self::$buttonLinkEmail)) ? "<a href='" . self::$buttonLinkEmail . "'><div style=\"text-decoration:none;display:inline-block;color:#ffffff;background-color:".self::$colorPrimaryEmail.";border-radius:15px;-webkit-border-radius:15px;-moz-border-radius:15px;width:auto; width:auto;;border-top:1px solid ".self::$colorPrimaryEmail.";border-right:1px solid ".self::$colorPrimaryEmail.";border-bottom:1px solid ".self::$colorPrimaryEmail.";border-left:1px solid ".self::$colorPrimaryEmail.";padding-top:10px;padding-bottom:10px;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;\"><span style=\"padding-left:20px;padding-right:20px;font-size:14px;display:inline-block;\"><span style=\"font-size: 16px; line-height: 32px;\"><span data-mce-style=\"font-family: 'lucida sans unicode', 'lucida grande', sans-serif; font-size: 14px; line-height: 32px;\" mce-data-marked=\"1\" style=\"font-family: 'lucida sans unicode', 'lucida grande', sans-serif; font-size: 14px; line-height: 28px;\">" . self::$buttonNameEmail . "</span></span></span></div></a>" : "";

        return (new View())->render("components/email",[
            "titleEmail" => $title,
            "subTitleEmail" => $subTitle,
            "descriptionEmail" => $description,
            "buttonLinkEmail" => $buttonLink,
            "buttonNameEmail" => $buttonName,
            "colorPrimaryEmail" => CONFIG_SITE['color-primary'],
            "urlEmail" => CONFIG_SITE['url'],
            "nameFullEmail" => CONFIG_SITE['nameFull'],
            "nameEmail" => CONFIG_SITE['name'],
            "button" => $button
        ],false);
    }


}