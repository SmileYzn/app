<?php

class Extra
{
    /**
     * Open SSL encrypt method
     * 
     * @var string
     */
    private static string $uuid_method = 'AES-256-CTR';
    
    /**
     * Chave únida para desencriptar / encriptar strings
     * 
     * @var string
     */
    private static string $uuid_key = 'b9d919cc-8ec9-11ee-862c-005056a0b4b6';
    
    /**
     * Retorna o caminho completo da URL atual
     * 
     * @param boolean $queryString  Incluir parâmetros $_GET (Query String)
     * 
     * @return string               Retorna a URL atual
     */
    public static function getURL($queryString = false)
    {
        $scheme = "http";

        if (!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))
        {
            $scheme = "https";
        }

        $parameter = $_SERVER['PHP_SELF'];

        if ($queryString)
        {
            $parameter = $_SERVER['REQUEST_URI'];
        }

        return "{$scheme}://{$_SERVER['HTTP_HOST']}{$parameter}";
    }

    /**
     * Redirecionar a uma url
     * 
     * @param string $url       URL a redirecionar
     */
    public static function redirecionar($url)
    {
        if (!headers_sent())
        {
            header("Location: {$url}");
        }
        
        die("<script type=\"text/javascript\">window.location.href = '{$url}';</script>");
    }
    
    /**
     * Codifica uma string
     * 
     * @param string $string    String a ser codificada
     * 
     * @return type             String codificada ou <b>false</b>
     */
    public static function enc($string)
    {
        if(!empty($string))
        {
            $tamanho = openssl_cipher_iv_length(self::$uuid_method);

            $random = openssl_random_pseudo_bytes($tamanho);

            $textoCifrado = openssl_encrypt($string, self::$uuid_method, self::$uuid_key, OPENSSL_RAW_DATA, $random);

            return bin2hex($random . $textoCifrado);
        }
        
        return false;
    }
    
    /**
     * Decodifica uma string
     * 
     * @param string $string        String a ser decodificada
     * 
     * @return mixed                String decodificada ou <b>false</b>
     */
    public static function dec($string)
    {
        if(!empty($string))
        {
            $string = hex2bin($string);
            
            if(!empty($string))
            {
                $tamanho = openssl_cipher_iv_length(self::$uuid_method);

                $random = mb_substr($string, 0, $tamanho, '8bit');

                $textoCifrado = mb_substr($string, $tamanho, null, '8bit');

                return openssl_decrypt($textoCifrado, self::$uuid_method, self::$uuid_key, OPENSSL_RAW_DATA, $random);
            }
        }
        
        return false;
    }
}
