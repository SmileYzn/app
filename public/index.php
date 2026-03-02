<?php include("../autoload.php");

// Retorno em JSON
header('Content-Type: application/json; charset=utf-8');

// Se não estiver logado, nao há retorno
if (empty(Sessao::get('id')))
{
    http_response_code(400);
    exit;
}

// Resultado
$resultado = [];

// Request: Upload via SUMMERNOTE
if (!empty($_POST['textarea']))
{
    $uploads = (new Upload('summernote'))->arquivo('arquivo');
    //
    foreach ($uploads as $caminho)
    {
        $resultado[] = str_replace(['https:', 'http:'], '', $_ENV['SG_URL_BACKEND']) . $caminho;
    }
}

// Request: AJAX
if (!empty($_REQUEST['ajax']))
{
    $ajax = filter_var($_REQUEST['ajax'], FILTER_SANITIZE_STRING);
    $pesquisa = filter_var($_REQUEST['pesquisa'], FILTER_SANITIZE_STRING);
    //
    if (!empty($pesquisa))
    {
        switch ($ajax)
        {
            case 'cidade':
            {
                $resultado = (new Cidade)->consultar("estadoFK = '{$pesquisa}'", "ORDER BY nome");
                break;
            }
        }
    }
}            

// Resultado
die(json_encode($resultado));
