<?php

class Base
{
    /**
     * Instância PHP PDO
     *
     * @var PDO
     */
    protected $pdo = null;
    
    /**
     * Nome da tabela atual
     *
     * @var string
     */
    protected $tabela = "";
    
    /**
     * Dados da instância atual
     */
    protected $dados = [];

    /**
     * Construtor inicial
     *
     * 1. Criar instância PDO
     * 2. Setar o nome da tabela da instância
     * 3. Se a requisição foi um $_POST, importar para os dados da instância
     */
    public function __construct()
    {
        $this->pdo = Conexao::get();

        $this->tabela = lcfirst(get_called_class());

        if (isset($_POST))
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $this->dados = $_POST;
            }
        }
    }

    /**
     * Retornar um valor dos dados da instância atual como em $obj->nome
     *
     * @param string $nome  Chave a ser retornada o seu valor
     *
     * @return mixed        Valor a ser retornado ou <b>null</b>
     */
    public function __get($nome)
    {
        return isset($this->dados[$nome]) ? $this->dados[$nome] : null;
    }

    /**
     * Verificar se a chave existe nos dados da instância atual como em isset($obj->nome)
     *
     * @param string $nome  Chave a verificar
     *
     * @return boolean     <b>true</b> se a chave existir ou <b>false</b>
     */
    public function __isset($nome)
    {
        return isset($this->dados[$nome]);
    }

    /**
     * Setar um valor dos dados da instância atual como em $obj->nome = "Meu Nome"
     *
     * @param string $nome  Chave a setar
     * @param mixed $valor  Valor a setar
     */
    public function __set($nome, $valor)
    {
        $this->dados[$nome] = $valor;
    }

    /**
     * Remove uma chave dos dados da instância atual como em unset($obj->nome)
     *
     * @param string $nome  Chave a remover
     */
    public function __unset($nome)
    {
        unset($this->dados[$nome]);
    }

    /**
     * Executa uma consulta SQL
     *
     * @param string $sql       String da consulta
     *
     * @return mixed            Retorna PDOStatement ou <b>false</b>
     */
    public function query($sql)
    {
        try
        {
            return $this->pdo->query($sql);
        }
        catch (PDOException $ex)
        {
            error_log($ex);
        }

        return false;
    }

    /**
     * Retorna os dados do registro em um array
     *
     * @param int $id       Index a ser consultado
     *
     * @return mixed        Retorna os dados em <b>array()<b>
     */
    public function id($id)
    {
        if (!empty($id) && filter_var($id, FILTER_VALIDATE_INT))
        {
            $run = $this->query("SELECT * FROM {$this->tabela} WHERE id = {$id}");

            if (!empty($run))
            {
                return $run->fetch();
            }
        }

        return [];
    }

    /**
     * Contar os registros de uma tabela
     *
     * @param string $where     Clásula WHERE
     * @param string $campo     Nome do campo a contar
     *
     * @return int              Quantidade de registros encontrados
     */
    public function contar($where = "", $campo = "id")
    {
        if (!empty($where))
        {
            $where = "WHERE $where";
        }

        $run = $this->query("SELECT COUNT({$campo}) AS total FROM {$this->tabela} {$where}");

        if (!empty($run))
        {
            return $run->fetchColumn();
        }

        return 0;
    }

    /**
     * Realizar uma consulta na tabela atual
     *
     * @param string $where     Clásula WHERE
     * @param string $order     Clásula ORDER BY
     * @param string $limit     Clásula LIMIT
     * w
     * @return mixed            array() contendo os dados ou <b>false</b>
     */
    public function consultar($where = "", $order = "", $limit = "")
    {
        if (!empty($where))
        {
            $where = "WHERE {$where}";
        }

        $run = $this->query("SELECT * FROM {$this->tabela} {$where} {$order} {$limit}");

        if (!empty($run))
        {
            return $run->fetchAll();
        }

        return false;
    }

    /**
     * Realizar uma consulta na tabela atual com registro ativo
     *
     * @param string $where     Clásula WHERE
     * @param string $order     Clásula ORDER BY
     * @param string $limit     Clásula LIMIT
     *
     * @return mixed            PDOStatement ou <b>false</b>
     */
    public function consultarAtivo($where = "", $order = "", $limit = "")
    {
        if (!empty($where))
        {
            $where = "AND {$where}";
        }

        $run = $this->query("SELECT * FROM {$this->tabela} WHERE ativo {$where} {$order} {$limit}");

        if (!empty($run))
        {
            return $run->fetchAll();
        }

        return false;
    }

    /**
     * Inserir um registro em uma tabela
     *
     * @param array $registro       Array de registro 'coluna' => 'valor'
     *
     * @return int                  Retorna o último index inserido na tabela ou 0
     */
    public function inserir($registro)
    {
        if (!empty($registro))
        {
            try
            {
                $colunas = implode(",", array_keys($registro));

                $valores = implode(",", array_fill(0, count($registro), "?"));

                $run = $this->pdo->prepare("INSERT INTO {$this->tabela} ({$colunas}) VALUES ({$valores})");

                $run->execute(array_values($registro));

                return $this->pdo->lastInsertId();
            }
            catch (PDOException $ex)
            {
                error_log($ex);
            }
        }

        return 0;
    }

    /**
     * Alterar um registro na tabela
     *
     * @param array $registro   Array de registro 'coluna' => 'valor'
     * @param string $where     Clásula WHERE
     *
     * @return int              Número de registros alterados na consulta
     */
    public function alterar($registro, $where = "")
    {
        if (!empty($registro))
        {
            if (!empty($where))
            {
                $where = "WHERE $where";
            }

            try
            {
                $colunas = implode(",", preg_filter('/$/', " = ?", array_keys($registro)));

                $run = $this->pdo->prepare("UPDATE {$this->tabela} SET {$colunas} {$where}");

                $run->execute(array_values($registro));

                return $run->rowCount();
            }
            catch (PDOException $ex)
            {
                error_log($ex);
            }
        }

        return 0;
    }

    /**
     * Inserir ou alterar um novo registro em uma tabela
     *
     * @param array $registro   Array de registro 'coluna' => 'valor'
     * @param int $id           Index do registro a ser inserido / alterado
     * @param string $campo     Nome do campo a comparar com o index
     *
     * @return int              Número de registros inseridos ou alterados
     */
    public function registrar($registro, $id = 0, $campo = "id")
    {
        if (!empty($id))
        {
            return $this->alterar($registro, "{$campo} = {$id}");
        }

        return $this->inserir($registro);
    }

    /**
     * Inativar os registro(s) passados pelo parâmetro id
     *
     * @param mixed $id     Index  a ser inativada ou <b>array()</b> de index
     *
     * @return int          Quantidade de registro(s) alterado(s)
     */
    public function inativar($id, $where = "")
    {
        if (!empty($id))
        {
            if (!empty($where))
            {
                $where = " AND $where";
            }

            $list = is_array($id) ? implode(",", $id) : $id;

            $run = $this->query("UPDATE {$this->tabela} SET ativo = IF(ativo, 0, 1) WHERE id IN({$list}) {$where};");

            if ($run)
            {
                return $run->rowCount();
            }
        }

        return 0;
    }
}
