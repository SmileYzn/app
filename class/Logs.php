<?php

class Logs extends Base
{    
    /**
     * Executa as funçoes do index para a classe
     * 
     * @return mixed     Retorna a consulta da pesquisa realizada ou os dados do item
     */
    public function index()
    {
        if (!empty($this->excluir))
        {
            $this->excluir();
        }

        if (!empty($_GET['id']))
        {
            $caminho = Extra::dec($_GET['id']);

            if (file_exists($caminho))
            {
                return file_get_contents($caminho);
            }

            return false;
        }

        return $this->pesquisar();
    }

    /**
     * Executa uma pesquisa para listagem dos dados da classe
     * 
     * @return array    Retorna a consulta da pesquisa 1realizada
     */
    public function pesquisar()
    {
        if(!empty($this->buscar))
        {
            $_SESSION[$_SERVER['SCRIPT_NAME']] = $_POST;
        }
        
        if(!empty($this->limpar))
        {
            unset($_SESSION[$_SERVER['SCRIPT_NAME']]);
        }
        
        $periodo =
        [
            0 => (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][0]) ? $_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][0] : date('Y-m-d', strtotime('FIRST DAY OF THIS MONTH'))),
            1 => (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][1]) ? $_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][1] : date('Y-m-d', strtotime('LAST DAY OF THIS MONTH'))),
        ];

        $resultado = [];
        
        $logs = dirname(ini_get('error_log')) . DIRECTORY_SEPARATOR . "*.log";
        $glob = glob($logs);
        
        if (!empty($glob))
        {
            foreach (array_reverse($glob) as $caminho)
            {
                $pathinfo = pathinfo($caminho);
                
                if (($periodo[0] <= $pathinfo['filename']) && ($pathinfo['filename'] <= $periodo[1]))
                {
                    $resultado[$caminho] = $pathinfo;
                }
            }
        }
        
        return $resultado;
    }
    
    /**
     * Inativar os registros passados via $_POST['id']
     * 
     * @return int      Quantidade de registros inativados
     */
    public function excluir()
    {
        if (empty($this->id))
        {
            Alert::set("Selecione algum registro.");
            return 0;
        }

        $resultado = 0;
        
        foreach ($this->id as $id)
        {
            $caminho = Extra::dec($id);
            
            if (unlink($caminho))
            {
                $resultado++;
            }
        }

        if ($resultado > 0)
        {
            Alert::set("{$resultado} registro(s) removido(s) com sucesso.", "success");
            return $resultado;
        }
        
        Alert::set("Nenhuma alteração, tente novamente.");
        return $resultado;
    }
}
