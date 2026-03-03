<?php

class Configuracao extends Base
{
    /**
     * Popular a variável global $_ENV com as configurações
     * 
     * @return array    Global <b>$_ENV</b> Array com as configurações
     */
    public function get()
    {
        if (empty($_ENV['SG_APP_PATH']))
        {
            try
            {
                $run = $this->pdo->query("SELECT chave, valor FROM {$this->tabela}");

                foreach ($run as $row)
                {
                    $_ENV[$row['chave']] = $row['valor'];
                }
            }
            catch (\PDOException $ex)
            {
                Extra::redirecionar("setup.php");
            }
        }

        return $_ENV;
    }

    /**
     * Executa as funçoes do index para a classe
     * 
     * @return mixed     Retorna a consulta da pesquisa realizada ou os dados do item
     */
    public function index()
    {
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
     * @return PDOStatement     Retorna a consulta da pesquisa realizada
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

        $where = "id > 0";

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']))
        {
            $where .= " AND (chave LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%' OR descricao LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%')";
        }

        return $this->consultar($where, "ORDER BY id", Paginacao::limit($this->contar($where)));
    }

    /**
     * Validar erros na requisição atual
     * 
     * @return boolean      <b>TRUE</b> se validou com sucesso ou <b>FALSE</b>
     */
    public function validar()
    {
        $erro = [];

        if (empty($this->chave))
        {
            $erro[] = "Preencha o campo: Chave";
        }

        if (empty($this->valor))
        {
            $erro[] = "Preencha o campo: Valor";
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
            'chave'     => $this->chave,
            'valor'     => $this->valor,
            'descricao' => $this->descricao
        ];

        $resultado = $this->registrar($registro, $id);

        if ($resultado)
        {
            unset($_POST);
            
            Alert::set("Registro salvo com sucesso", "success");
            
            return $resultado;
        }
        
        Alert::set("Nenhuma alteração, tente novamente", "warning");
        return 0;
    }
}
