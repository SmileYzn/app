<?php

class Turnstile
{
    /**
     * Retorna a tag <script> do Turnstile
     * 
     * @return string
     */
    public static function getJavaScript()
    {
        if (!empty($_ENV['SG_CAPTCHA_SITE_KEY']))
        {
            return '<script src="//challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
        }
        
        return '';
    }
    
    /**
     * Retorna o widget 'cf-turnstile' no formulário
     * 
     * @return string
     */
    public static function getWidget()
    {
        if (!empty($_ENV['SG_CAPTCHA_SITE_KEY']))
        {
            return '<div class="cf-turnstile" size="compact" theme="auto" data-sitekey="' . $_ENV['SG_CAPTCHA_SITE_KEY'] .'"></div>';
        }
        
        return '';
    }
    
    /**
     * Valida o envio do Turnstile pelo cliente com o servidor Turnstile
     * 
     * @return bool     <b>TRUE</b> se não configurado e/ou validado pelo Turnstile, <b>FALSE</b> se falhar na validaçào
     */
    public static function valida()
    {
        if (!empty($_ENV['SG_CAPTCHA_SITE_KEY']) && !empty($_ENV['SG_CAPTCHA_SECRET_KEY']))
        {
            $ch = curl_init();
            curl_setopt_array($ch,
            [
                CURLOPT_URL => "https://challenges.cloudflare.com/turnstile/v0/siteverify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query
                ([
                    "secret"          => $_ENV['SG_CAPTCHA_SECRET_KEY'],
                    "response"        => $_POST['cf-turnstile-response'],
                    "remoteip"        => $_SERVER['REMOTE_ADDR'],
                    "idempotency_key" => time()
                ])
            ]);

            $resposta = curl_exec($ch);

            curl_close($ch);

            $json = json_decode($resposta, true);
            
            return !empty($json['success']);
        }
        
        return true;
    }
}