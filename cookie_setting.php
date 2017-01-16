<?php

class CookieSetting
{
    public $expires; //目前的 timestamp + 一天的秒數
    public $path = '/'; //根目錄，整個網站都能使用，預設會是網頁當下的目錄
    public $domain = ''; //目前的 domain，只可設定為子網域，不可設定成其他網域
    public $httpOnly = true; //無法使用 Javascript 取得 Cookie，防止有心人士釣魚取得
    public $secure = false;
    //When this parameter is on/active cookie will only be accessible from secure connection.
    //Means if the parameter is true cookie will be accessible from HTTPS protocol.
    public function getExpires()
    {
        $this->expires = time() + 86400;

        return $this->expires;
    }
}
