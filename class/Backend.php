<?php

class Backend
{
    /**
     * Executa o controlador backend e executa os seguintes passos
     * 
     * - Busca os dados da unidade atual
     * - Verifica o usuário logado
     * - Verifica as permissões de acesso
     * - Inicia os módulos do sistema
     * - Verifica e executa cada módulo conforme o acesso
     * - Redireciona o usuário em caso de falha de acesso
     * - Redireciona o usuário em caso de página não encontrada
     * 
     * @global array $_BACKEND      Array que contém os dados do backend
     * 
     * @return bool                 <b>TRUE</b> ou <b>FALSE</b>
     */
    public static function executar()
    {
        // Backend
        global $_BACKEND;
        
        // Dados
        global $_DADOS;
        
        // Caminho relativo do script atual
        $_BACKEND['url'] = str_replace($_ENV['SG_PATH'], '', $_SERVER['SCRIPT_FILENAME']);
        
        // Verificar Login
        (new Login)->verificar($_BACKEND['url']);
        
        // Permissões
        $_BACKEND['acesso'] = Permissao::verificar($_BACKEND['url'], Sessao::get('areaFK'));
        
        // Módulos
        $_BACKEND['modulo'] = Modulo::get(Sessao::get('areaFK'), $_BACKEND['url']);
        
        // Breadcrumb
        $_BACKEND['breadcrumb'] = Modulo::getBreadcrumb(Sessao::get('areaFK'));
        
        // Unidade
        $_BACKEND['unidade'] = (new Unidade)->id(Sessao::get('unidadeFK') ? Sessao::get('unidadeFK') : 1);
        
        // URL (Caminho atual)
        $_BACKEND['caminho'] = dirname($_SERVER['PHP_SELF']);
        
        // Título
        $_BACKEND['titulo'] = "Dashboard";

        // Controlador
        switch($_BACKEND['url'])
        {
            case 'index.php':
            {
                $_BACKEND['titulo'] = "Dashboard";
                return true;
            }
            case '404.php':
            {
                $_BACKEND['titulo'] = 'Página não encontrada';
                return true;
            }
            case 'login/index.php':
            {
                $_BACKEND['titulo'] = 'Login';
                return (new Login)->login();
            }
            case 'login/logout.php':
            {
                $_BACKEND['titulo'] = 'Logout';
                return (new Login)->logout();
            }
            case 'recuperar/index.php':
            {
                $_BACKEND['titulo'] = 'Recuperar Conta';
                return (new Recuperar)->index();
            }
            //
            // Configurações
            case 'configuracoes/area/index.php':
            case 'configuracoes/area/adicionar.php':
            case 'configuracoes/area/editar.php':
            {
                $_BACKEND['titulo'] = 'Área';
                return (new Area)->index();
            }
            case 'configuracoes/configuracao/index.php':
            case 'configuracoes/configuracao/adicionar.php':
            case 'configuracoes/configuracao/editar.php':
            {
                $_BACKEND['titulo'] = 'Configuração';
                return (new Configuracao)->index();
            }
            case 'configuracoes/logs/index.php':
            case 'configuracoes/logs/adicionar.php':
            case 'configuracoes/logs/editar.php':
            {
                $_BACKEND['titulo'] = 'Logs do dashboard';
                return (new Logs)->index();
            }
            case 'configuracoes/modulo/index.php':
            case 'configuracoes/modulo/adicionar.php':
            case 'configuracoes/modulo/editar.php':
            {
                $_BACKEND['titulo'] = 'Módulo';
                return (new Modulo)->index();
            }
            case 'configuracoes/permissao/index.php':
            case 'configuracoes/permissao/adicionar.php':
            case 'configuracoes/permissao/editar.php':
            {
                $_BACKEND['titulo'] = 'Permissões';
                return (new Permissao)->index();
            }
            case 'configuracoes/unidade/index.php':
            case 'configuracoes/unidade/adicionar.php':
            case 'configuracoes/unidade/editar.php':
            {
                $_BACKEND['titulo'] = 'Unidades';
                return (new Unidade)->index();
            }
            case 'configuracoes/usuario/index.php':
            case 'configuracoes/usuario/adicionar.php':
            case 'configuracoes/usuario/editar.php':
            {
                $_BACKEND['titulo'] = 'Usuários';
                return (new Usuario)->index();
            }
        }
        
        Extra::redirecionar($_ENV['SG_URL_BACKEND']);
        
        return false;
    }
}
