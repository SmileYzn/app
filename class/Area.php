<?php

class Area extends Base
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

        if (!empty($this->salvar))
        {
            $this->salvar(Extra::dec($_GET['id']));
        }

        if (!empty($_GET['id']))
        {
            return $this->id(Extra::dec($_GET['id']));
        }

        return $this->pesquisar();
    }

    /**
     * Executa uma pesquisa para listagem dos dados da classe
     * 
     * @return PDOStatement     Retorna a consulta da pesquisa 1realizada
     */
    public function pesquisar()
    {
        if (!empty($this->buscar))
        {
            $_SESSION[$_SERVER['SCRIPT_NAME']] = $_POST;
        }

        if (!empty($this->limpar))
        {
            unset($_SESSION[$_SERVER['SCRIPT_NAME']]);
        }

        $where = "ativo = 1";

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']))
        {
            $where .= " AND (nome LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%' OR descricao LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%')";
        }

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['areaFK']))
        {
            $where .= " AND areaFK = '{$_SESSION[$_SERVER['SCRIPT_NAME']]['areaFK']}'";
        }

        return $this->consultarAtivo($where, "ORDER BY id", Paginacao::limit($this->contar($where)));
    }

    /**
     * Validar erros na requisição atual
     */
    public function validar()
    {
        $erro = [];

        if (empty($this->nome))
        {
            $erro[] = "Preencha o campo: Nome";
        }

        if (empty($this->descricao))
        {
            $erro[] = "Preencha o campo: Descrição";
        }

        return $erro;
    }

    /**
     * Adicionar um registro a tabela
     * 
     * @return int                  Retorna o último index inserido ou 0
     */
    public function salvar($id)
    {
        $erro = $this->validar();

        if ($erro)
        {
            Alert::set($erro);
            return 0;
        }
        
        $registro =
        [
            'nome'      => $this->nome,
            'descricao' => $this->descricao
        ];

        $resultado = $this->registrar($registro, $id);

        if ($resultado)
        {
            unset($_POST);
            
            (new Permissao)->inserirPermissoes($resultado);

            Alert::set("Registro salvo com sucesso", "success");
            
            return $resultado;
        }
        
        Alert::set("Nenhuma alteração, tente novamente", "warning");
        
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
        
        $resultado = $this->inativar($this->id);

        if ($resultado)
        {
            Alert::set("{$resultado} registro(s) removido(s) com sucesso.", "success");
            
            return $resultado;
        }

        Alert::set("Nenhuma alteração, tente novamente.", "warning");
        
        return 0;
    }
}
