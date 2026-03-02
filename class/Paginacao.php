<?php

class Paginacao
{
    /**
     * Quantidade de registros encontrados na busca
     * 
     * @var int
     */
    private static $totalRegistrosEncontrados = 0;
    /**
     * Número de registros a exibir por página
     * 
     * @var int
     */
    private static $maxRegistrosPagina = 100;

    /**
     * Retorna a clásula LIMIT para paginação
     * 
     * @param int $totalRegistrosEncontrados Quantidade de registros encontrados na consulta
     * @param int $maxRegistrosPagin Número de registros a exibir por página
     * 
     * @return string Clásula LIMIT
     */
    static function limit($totalRegistrosEncontrados = 0, $maxRegistrosPagina = 50)
    {
        // Se o total de registros não foi ajustado
        if (self::$totalRegistrosEncontrados == null)
        {
            // Ajustar o total de registros encontrados
            self::$totalRegistrosEncontrados = $totalRegistrosEncontrados;
        }

        // Limite de registros por páginas
        if ($maxRegistrosPagina)
        {
            // Limite de registros por página
            self::$maxRegistrosPagina = $maxRegistrosPagina;
        }

        // $pagina é a página atual, se nao houver valor, o padrão será 1
        $pagina = isset($_GET['p']) ? Extra::dec($_GET['p']) : 1;

        // Calcular o limite na clásula LIMIT com base nos registros por página
        $doRegistroInicio = (self::$maxRegistrosPagina * (int) $pagina) - self::$maxRegistrosPagina;

        // Se o início do registro for maior do que o total de registros
        if ($doRegistroInicio > $totalRegistrosEncontrados)
        {
            // Forçar início do primeiro registro
            $doRegistroInicio = 0;
        }

        // Concatenar
        $doRegistroFim = self::$maxRegistrosPagina;

        // Retorno clásula LIMIT
        return "LIMIT {$doRegistroInicio}, {$doRegistroFim}";
    }

    /**
     * Gera a frase na tabela para o número de registros
     * 
     * @param int $totalRegistrosEncontradosPagina Total de registros exibidos na página atual
     * 
     * @return string Texto com a frase de registros
     */
    static function frase($totalRegistrosEncontradosPagina = 0)
    {
        // Se houver registros
        if (self::$totalRegistrosEncontrados > 0)
        {
            // $pagina é a página atual, se nao houver valor, o padrão será 1
            $pagina = isset($_GET['p']) ? Extra::dec($_GET['p']) : 1;

            // Calcular o limite na clásula LIMIT com base nos registros por página
            $doRegistroInicio = 1 + (self::$maxRegistrosPagina * (int) $pagina) - self::$maxRegistrosPagina;

            // Calcular o número máximo de registros na página atual
            $registrosPaginaAtual = $doRegistroInicio + $totalRegistrosEncontradosPagina - 1;

            // Concatenar
            $totalRegistrosEncontrados = self::$totalRegistrosEncontrados;

            return "Registro {$doRegistroInicio} de {$registrosPaginaAtual} (Total {$totalRegistrosEncontrados})";
        }

        // Retorna frase
        return '<i class="bi-exclamation-triangle-fill text-danger"></i> Nenhum registro encontrado, tente realizar outra pesquisa.';
    }

    /**
     * Retorna os botões de paginação (Padrão Bootstrap 5)
     * 
     * @return string
     */
    static function show()
    {
        // $pagina é a página atual padrão 1
        $pagina = 1;

        // Número total de páginas (Arredondado para cima)
        $paginasTotal = ceil(self::$totalRegistrosEncontrados / self::$maxRegistrosPagina);

        // Se o número de paginas for zero
        if (empty($paginasTotal))
        {
            // Nenhum número de página, retornar vazio
            return '';
        }

        // Se estiver setado a variável 'p' no array $_GET
        if (isset($_GET['p']))
        {
            // Recupera o número da página do array $_GET
            $pagina = Extra::dec($_GET['p']);

            // Remover a variável do array $_GET para buscar a query string da URL
            unset($_GET['p']);
        }

        // Query String atual (Sem o argumento 'p')
        $queryString = http_build_query($_GET);

        // Se a página atual for maior que o total
        if ($pagina > $paginasTotal)
        {
            // Seta para a primeira página
            $pagina = 1;
        }

        // Range de links para mostrar
        $range = 2;

        // Exibir links para o 'range de páginas' em torno da 'página atual'
        $paginaInicial          = ($pagina - $range);
        $paginaCondicaoLimitNum = ($pagina + $range) + 1;

        // HTML
        $html = '<nav><ul class="pagination pagination-sm justify-content-center m-0">';

        // Se a página atual for maior que a primeira
        if ($pagina > 1)
        {
            // Se houver mais páginas do que o range exibido na página atual
            if ($paginasTotal > $range)
            {
                // Botão para ir a primeira página
                $primeiraPagina = Extra::enc(1);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão de primeira página e parâmetros $_GET
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$primeiraPagina}&{$queryString}\"><i class=\"bi-arrow-bar-left\"></i></a></li>";
                }
                else
                {
                    // Botão de primeira página
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$primeiraPagina}\"><i class=\"bi-arrow-bar-left\"></i></a></li>";
                }

                // Botão para ir a página anterior
                $paginaAnterior = Extra::enc($pagina - 1);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão da página anterior e parâmetros $_GET
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$paginaAnterior}&{$queryString}\"><i class=\"bi-arrow-bar-left\"></i></a></li>";
                }
                else
                {
                    // Botão da página anterior
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$paginaAnterior}\"><i class=\"bi-arrow-bar-left\"></i></a></li>";
                }
            }
        }

        // Loop de páginas
        for ($i = $paginaInicial; $i < $paginaCondicaoLimitNum; $i++)
        {
            // Ter certeza que '$i é maior do que 0' AND 'menor ou igual do que $paginasTotal'
            if (($i > 0) && ($i <= $paginasTotal))
            {
                // Se for igual a página atual
                if ($i == $pagina)
                {
                    // Botão falso (ativo) se a página atual for igual a página do botão
                    $html .= "<li class=\"page-item active\"><span class=\"page-link\">{$i}</span></li>";
                }
                else
                {
                    // Index da página
                    $getPagina = Extra::enc($i);

                    // Se houver parâmetros $_GET, adicionar ao link
                    if (!empty($queryString))
                    {
                        // Adiciona o botão com o número e link da página e parâmetros $_GET
                        $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$getPagina}&{$queryString}\">{$i}</a></li>";
                    }
                    else
                    {
                        // Adiciona o botão com o número e link da página
                        $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$getPagina}\">{$i}</a></li>";
                    }
                }
            }
        }

        // Se a página atual for menor que o total de páginas
        if ($pagina < $paginasTotal)
        {
            // Se houver mais páginas do que o range exibido na página atual
            if ($paginasTotal > $range)
            {
                // Número da próxima página (Página atual + 1)
                $proximaPagina = Extra::enc($pagina + 1);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão seta próxima página se não for a última página e parâmetros $_GET
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$proximaPagina}&{$queryString}\"><i class=\"bi-arrow-right\"></i></a></li>";
                }
                else
                {
                    // Botão seta próxima página se não for a última página
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$proximaPagina}\"><i class=\"bi-arrow-right\"></i></a></li>";
                }

                // Número da última página se não for a última página
                $ultimaPagina = Extra::enc($paginasTotal);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão seta última página se não for a última página e parâmetros $_$GET
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$ultimaPagina}&{$queryString}\"><i class=\"bi-arrow-bar-right\"></i></a></li>";
                }
                else
                {
                    // Botão seta última página se não for a última página
                    $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$_SERVER['PHP_SELF']}?p={$ultimaPagina}\"><i class=\"bi-arrow-bar-right\"></i></a></li>";
                }
            }
        }

        // HTML
        $html .= '</ul></nav>';

        // Retorno
        return $html;
    }

    /**
     * Retorna os botões e select de paginação (Padrão Bootstrap 5)
     * 
     * @param int $minimoPaginas = 2    Número de páginas para exibir o select
     * 
     * @return string
     */
    static function select($minimoPaginas = 1)
    {
        // $pagina é a página atual padrão 1
        $pagina = 1;

        // Número total de páginas (Arredondado para cima)
        $paginasTotal = ceil(self::$totalRegistrosEncontrados / self::$maxRegistrosPagina);

        // Se o número de paginas for zero
        if (empty($paginasTotal))
        {
            // Nenhum número de página, retornar vazio
            return '';
        }

        // Ocultar paginação se houver somente uma página
        if ($paginasTotal < $minimoPaginas)
        {
            return '';
        }

        // Se estiver setado a variável 'p' no array $_GET
        if (isset($_GET['p']))
        {
            // Recupera o número da página do array $_GET
            $pagina = Extra::dec($_GET['p']);

            // Remover a variável do array $_GET para buscar a query string da URL
            unset($_GET['p']);
        }

        // Query String atual (Sem o argumento 'p')
        $queryString = http_build_query($_GET);

        // Se a página atual for maior que o total
        if ($pagina > $paginasTotal)
        {
            // Seta para a primeira página
            $pagina = 1;
        }

        // Range de links para mostrar
        $range = 2;

        // HTML
        $html = '<div class="input-group input-group-sm">';

        // Se a página atual for maior que a primeira
        if ($pagina > 1)
        {
            // Se houver mais páginas do que o range exibido na página atual
            if ($paginasTotal > $range)
            {
                // Botão para ir a primeira página
                $primeiraPagina = Extra::enc(1);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão de primeira página e parâmetros $_GET
                    $html .= "<a class=\"btn btn-primary\" href=\"{$_SERVER['PHP_SELF']}?p={$primeiraPagina}&{$queryString}\"><i class=\"bi-arrow-bar-left\"></i></a>";
                }
                else
                {
                    // Botão de primeira página
                    $html .= "<a class=\"btn btn-primary\" href=\"{$_SERVER['PHP_SELF']}?p={$primeiraPagina}\"><i class=\"bi-arrow-bar-left\"></i></a>";
                }

                // Botão para ir a página anterior
                $paginaAnterior = Extra::enc($pagina - 1);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão da página anterior e parâmetros $_GET
                    $html .= "<a class=\"btn btn-primary\" href=\"{$_SERVER['PHP_SELF']}?p={$paginaAnterior}&{$queryString}\"><i class=\"bi-arrow-left\"></i></a>";
                }
                else
                {
                    // Botão da página anterior
                    $html .= "<a class=\"btn btn-primary\" href=\"{$_SERVER['PHP_SELF']}?p={$paginaAnterior}\"><i class=\"bi-arrow-left\"></i></a>";
                }
            }
        }

        // Se houver mais de uma página
        if ($paginasTotal > 1)
        {
            $html .= "<select class=\"form-control text-center\" onchange=\"if(this.value){window.location.href=this.value}\">";
        }
        else
        {
            $html .= "<select class=\"form-control text-center\" disabled>";
        }

        // Loop de páginas
        for ($i = 1; $i <= $paginasTotal; $i++)
        {
            // Se for igual a página atual
            if ($i == $pagina)
            {
                // Botão falso (ativo) se a página atual for igual a página do botão
                $html .= "<option selected>Página {$i} de {$paginasTotal}</option>";
            }
            else
            {
                // Index da página
                $getPagina = Extra::enc($i);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Adiciona o botão com o número e link da página e parâmetros $_GET
                    $html .= "<option value=\"{$_SERVER['PHP_SELF']}?p={$getPagina}&{$queryString}\">Página {$i} de {$paginasTotal}</option>";
                }
                else
                {
                    // Adiciona o botão com o número e link da página
                    $html .= "<option value=\"{$_SERVER['PHP_SELF']}?p={$getPagina}\">Página {$i} de {$paginasTotal}</option>";
                }
            }
        }

        $html .= "</select>";

        // Se a página atual for menor que o total de páginas
        if ($pagina < $paginasTotal)
        {
            // Se houver mais páginas do que o range exibido na página atual
            if ($paginasTotal > $range)
            {
                // Número da próxima página (Página atual + 1)
                $proximaPagina = Extra::enc($pagina + 1);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão seta próxima página se não for a última página e parâmetros $_GET
                    $html .= "<a class=\"btn btn-primary\" href='{$_SERVER['PHP_SELF']}?p={$proximaPagina}&{$queryString}'><i class=\"bi-arrow-right\"></i></a>";
                }
                else
                {
                    // Botão seta próxima página se não for a última página
                    $html .= "<a class=\"btn btn-primary\" href='{$_SERVER['PHP_SELF']}?p={$proximaPagina}'><i class=\"bi-arrow-right\"></i></a>";
                }

                // Número da última página se não for a última página
                $ultimaPagina = Extra::enc($paginasTotal);

                // Se houver parâmetros $_GET, adicionar ao link
                if (!empty($queryString))
                {
                    // Botão seta última página se não for a última página e parâmetros $_$GET
                    $html .= "<a class='btn btn-primary' href='{$_SERVER['PHP_SELF']}?p={$ultimaPagina}&{$queryString}'><i class=\"bi-arrow-bar-right\"></i></a>";
                }
                else
                {
                    // Botão seta última página se não for a última página
                    $html .= "<a class='btn btn-primary' href='{$_SERVER['PHP_SELF']}?p={$ultimaPagina}'><i class=\"bi-arrow-bar-right\"></i></a>";
                }
            }
        }

        // HTML
        $html .= '</div>';

        // Retorno
        return $html;
    }
}
