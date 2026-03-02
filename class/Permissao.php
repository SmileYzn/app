<?php

class Permissao extends Base
{
    /**
     * Permissões
     * 
     * @var array
     */
    private static $permissao = [];
    
    /**
     * Retorna as permissões de acesso da área especificada em um array
     * 
     * @param int $areaFK       Área de acesso
     * @return array            Retorna os dados de acesso em  um array
     */
    public static function get($areaFK)
    {
        if (empty(self::$permissao))
        {
            $sql = "SELECT p.areaFK, p.acesso AS permissoes, m.url, a.acesso FROM acesso a INNER JOIN permissao p ON FIND_IN_SET(a.acesso, p.acesso) > 0 INNER JOIN modulo m ON p.moduloFK = m.id WHERE a.ativo AND p.ativo AND m.ativo AND p.areaFK = '{$areaFK}' AND LENGTH(m.url) > 1";
            $run = (new Permissao)->query($sql);

            foreach ($run as $row)
            {
                $arquivo = "{$row['acesso']}.php";

                if (strpos($row['url'], '/') !== false)
                {
                    $arquivo = $row['url'] . DIRECTORY_SEPARATOR . $arquivo;
                }

                self::$permissao[$areaFK][$arquivo] = explode(',', $row['permissoes']);
            }
        }

        return self::$permissao[$areaFK];
    }
    
    /**
     * Verificar se há permissao para acesso a url especificada conforme a área do usuário
     * Se não houver permissão de acesso, redireciona a página de erro
     * 
     * @param string $url           URL a ser verificada o acesso
     * @param integer $areaFK       Index da área do usuário
     * @param boolean $redirecionar Redirecionar se necessário a página não encontrada
     * 
     * @return array                Retorna os acessos se houver permissão, ou <b>FALSE</b> e redireciona a página de erro
     */
    public static function verificar($url, $areaFK, $redirecionar = true)
    {
        if (!empty($url) && !empty($areaFK))
        {
            if (!in_array($url, ['404.php', 'login/index.php', 'login/logout.php', 'recuperar/index.php']))
            {
                $permissao = Permissao::get($areaFK);

                foreach ($permissao as $caminho => $acesso)
                {
                    if ($url == $caminho)
                    {
                        return $permissao[$caminho];
                    }
                }

                if (!empty($redirecionar))
                {
                    Extra::redirecionar("{$_ENV['SG_URL_BACKEND']}404.php");
                }
            }
        }

        return false;
    }

    /**
     * Criar as permissões para área especificada a partir dos módulos do sistema
     * 
     * @param int $areaFK       Index da área
     * 
     * @return bool             Retorna <b>TRUE</b> ou <b>FALSE</b>
     */
    public function inserirPermissoes($areaFK)
    {
        if (!empty($areaFK))
        {
            $run = $this->query("INSERT INTO permissao (SELECT NULL, {$areaFK}, id, 'index,adicionar,editar,excluir', NOW(), 1 FROM modulo);");

            if (!empty($run))
            {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Executa as funçoes do index para a classe
     * 
     * @return mixed     Retorna a consulta da pesquisa realizada ou os dados do item
     */
    public function index()
    {
        if (!empty($_GET['fk']))
        {
            $_SESSION['fk'] = Extra::dec($_GET['fk']);
        }

        if (!empty($this->alterarPermissao))
        {
            $this->alterarPermissao();
        }

        if (!empty($this->salvar))
        {
            $this->areaFK = $_SESSION['fk'];
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

        $where = "ativo AND areaFK = '{$_SESSION['fk']}'";

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']))
        {
            $where .= " AND (SELECT COUNT(id) FROM modulo m WHERE m.ativo AND (m.nome LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%' OR m.nome LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%')) > 0";
        }

        return $this->consultar($where,"ORDER BY id", Paginacao::limit($this->contar($where)));
    }
    
    /**
     * Validar erros na requisição atual
     * 
     * @param int $id       Index do item a ser validado ou 0 se for um novo item
     * 
     * @return boolean      <b>TRUE</b> se validou com sucesso ou <b>FALSE</b>
     */
    public function validar($id)
    {
        $erro = [];
        
        if (empty($this->areaFK))
        {
            $erro[] = "Preencha o campo: Área";
        }

        if (empty($this->moduloFK))
        {
            $erro[] = "Preencha o campo: Módulo";
        }

        if (empty($this->acesso))
        {
            $erro[] = "Preencha o campo: Acesso";
        }
        
        if (empty($id))
        {

            if (!empty($this->areaFK) && !empty($this->moduloFK))
            {
                if ($this->contar("areaFK = '{$this->areaFK}' AND moduloFK = '{$this->moduloFK}'") > 0)
                {
                    $area   = (new Area)->id($this->areaFK);
                    $modulo = (new Modulo)->id($this->moduloFK);

                    $erro[] = "Permissões de <b>{$area['nome']}</b> já existem no módulo <b>{$modulo['nome']}</b>.";
                }
            }
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
        $erro = $this->validar($id);

        if ($erro)
        {
            Alert::set($erro);
            return 0;
        }

        $registro =
        [
            'areaFK'   => $this->areaFK,
            'moduloFK' => $this->moduloFK,
            'acesso'   => (!empty($this->acesso) ? implode(',', $this->acesso) : '')
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
    
    /**
     * Alterar os registros passados via $_POST['id']
     * 
     * @return int      Quantidade de registros alterados
     */
    public function alterarPermissao()
    {
        $resultado = 0;
        
        if (!empty($this->id) && is_array($this->id))
        {
            $this->id     = (!empty($this->id) ? implode(',', $this->id) : '');
            $this->acesso = (!empty($this->acesso) ? implode(',', $this->acesso) : '');

            $resultado = $this->alterar(["acesso" => $this->acesso], "id IN ($this->id)");

            if ($resultado > 0)
            {
                Alert::set("{$resultado} registro(s) alterado(s) com sucesso.", "success");
            }
            else
            {
                Alert::set("Nenhuma alteração, tente novamente.");
            }
        }
        else
        {
            Alert::set("Selecione algum registro.");
        }
        
        return $resultado;
    }
}
