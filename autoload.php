<?php

// Exibir erros do PHP
ini_set('display_errors', 0);

// Ajustar erros do PHP
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);

// Timezone América / São Paulo
date_default_timezone_set('America/Sao_Paulo');

// Ajustar data e hora para pt_BR
setlocale(LC_TIME, 'pt_BR.utf-8', 'pt_BR', 'portuguese');

// Redirecionar logs do PHP
ini_set('error_log', __DIR__ . '/logs/' . date('Y-m-d') . '.log');

// Conexão MySQL / MariaDB
require_once("db.php");

// Vendor (Composer autoloader)
if (file_exists(__DIR__ . '/vendor/autoload.php'))
{
    include_once(__DIR__ . '/vendor/autoload.php');
}

// Classes do PHP
spl_autoload_register(function($classe)
{
    $caminho = __DIR__ . '/class/' . $classe . '.php';

    if(file_exists($caminho))
    {
        include_once($caminho);
    }
});

// Popular a variável global $_ENV com as configurações
(new Configuracao)->get();

// Iniciar dados da sessão
Sessao::iniciar();
