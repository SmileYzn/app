<?php

class Sessao
{
    /**
     * Variável que armazena o token (String única da sessão)
     * 
     * @var string
     */
    static protected $SG_SESSAO_TOKEN = 'SG_SESSAO_TOKEN';

    /**
     * Iniciar os dados da sessão no PHP
     */
    public static function iniciar()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            if (!empty($_ENV['SG_SESSAO_TOKEN']))
            {
                self::$SG_SESSAO_TOKEN = $_ENV['SG_SESSAO_TOKEN'];
            }

            if (!headers_sent())
            {
                session_start
                ([
                    'name'            => ($_ENV['SG_SESSAO_TOKEN']) ? $_ENV['SG_SESSAO_TOKEN'] : 'SGVEC_SESSION_ID',
                    'cookie_lifetime' => (!empty($_ENV['SG_SESSAO_DIAS'])) ? ($_ENV['SG_SESSAO_DIAS'] * 86400) : 0,
                    'cookie_path'     => '/',
                    'cookie_domain'   => (!empty($_ENV['SG_SESSAO_DOMINIO'])) ? $_ENV['SG_SESSAO_DOMINIO'] : '',
                ]);
            }
        }
    }

    /**
     * Finalizar os dados da sessão no PHP
     */
    public static function destruir()
    {
        if (session_status() == PHP_SESSION_ACTIVE)
        {
            session_unset();

            session_destroy();
        }
    }

    /**
     * Inserir os dados na sessão
     * 
     * @param array $array          Array dos dados a serem inseridos
     */
    public static function set($array)
    {
        $_SESSION[self::$SG_SESSAO_TOKEN] = $array;
    }

    /**
     * 
     * @param string $chave     Chave a retornar
     * 
     * @return mixed            Retorna os dados da chave ou null
     */
    public static function get($chave)
    {
        if (isset($_SESSION[self::$SG_SESSAO_TOKEN][$chave]))
        {
            return $_SESSION[self::$SG_SESSAO_TOKEN][$chave];
        }

        return null;
    }
}
