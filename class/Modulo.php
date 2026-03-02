<?php

class Modulo extends Base
{
    /**
     * Permissões
     * 
     * @var array
     */
    private static $modulo = null;
    
    /**
     * Retorna os módulos do sistema verificando as permissões de acesso
     * 
     * @param int $areaFK       Área de acesso aos módulos
     * @param string $url       URL atual para destacar o módulo na lista se ele for igual a URL
     * 
     * @return array            Array com todos os módulos encontrados
     */
    public static function get($areaFK, $url = '')
    {
        if (empty(self::$modulo[$areaFK]))
        {
            $dirname = dirname($url);
            
            $modulos = (new Modulo)->query("SELECT m.id, m.nome, m.descricao, m.url, m.icone, m.ocultar, p.acesso, p.areaFK, IF(m.url = '{$url}', 1, 0) AS atual FROM modulo m INNER JOIN permissao p ON m.id = p.moduloFK WHERE m.ativo = 1 AND m.moduloFK = 0 AND p.ativo = 1 AND p.acesso AND p.areaFK = '{$areaFK}' ORDER BY m.ordem");

            foreach($modulos as $modulo)
            {
                if (empty($modulo['atual']) && !empty($modulo['ocultar']))
                {
                    continue;
                }

                $subModulos = (new Modulo)->query("SELECT m.id, m.nome, m.descricao, m.url, m.icone, m.ocultar, p.acesso, p.areaFK, IF(m.url = '{$dirname}', 1, 0) AS atual FROM modulo m INNER JOIN permissao p ON m.id = p.moduloFK WHERE m.ativo AND m.moduloFK = '{$modulo['id']}' AND p.ativo = 1 AND p.acesso AND p.areaFK = '{$areaFK}' ORDER BY m.ordem");

                foreach ($subModulos as $subModulo)
                {
                    if (empty($subModulo['atual']) && !empty($subModulo['ocultar']))
                    {
                        continue;
                    }

                    if (!empty($subModulo['atual']))
                    {
                        $modulo['atual'] = true;
                    }

                    $modulo['subModulo'][] = $subModulo;
                }

                if (!empty($modulo['url']) || !empty($modulo['subModulo']))
                {
                    self::$modulo[$areaFK][] = $modulo;
                }
            }
        }
        
        return ((!empty(self::$modulo) && !empty(self::$modulo[$areaFK])) ? self::$modulo[$areaFK] : []);
    }
    
    /**
     * Cria a lista breadcrumb para exibir o caminho
     * 
     * @param array $areaFK     Área de acesso
     * 
     * @return array            Array com os caminhos do breadcrumb
     */
    public static function getBreadcrumb($areaFK)
    {
        $resultado = [];
        
        $resultado[] = ['url' => $_ENV['SG_URL_BACKEND'], 'nome' => 'Dashboard'];
        
        if (!empty(self::$modulo[$areaFK]))
        {
            foreach (self::$modulo[$areaFK] as $mod)
            {
                if (!empty($mod['atual']))
                {
                    $resultado[] = $mod;

                    if (!empty($mod['subModulo']))
                    {
                        foreach ($mod['subModulo'] as $subMod)
                        {
                            if (!empty($subMod['atual']))
                            {
                                $resultado[] = $subMod;
                            }
                        }
                    }
                }
            }
        }

        $scriptName = basename($_SERVER['SCRIPT_NAME'], ".php");
        
        if (!empty($scriptName))
        {
            if ($scriptName != 'index')
            {
                $resultado[] = ['url' => Extra::getURL(true), 'nome' => ucfirst($scriptName)];
            }
        }

        return $resultado;
    }
    
    /**
     * Executa as funçoes do index para a classe
     * 
     * @return mixed     Retorna a consulta da pesquisa realizada ou os dados do item
     */
    public function index()
    {
        if (!empty($this->alterar))
        {
            $this->ocultarExibir();
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

        $where = "ativo = 1";

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']))
        {
            $where .= " AND (nome LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%' OR descricao LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%')";
        }

        return $this->consultar($where, "ORDER BY ordem", Paginacao::limit($this->contar($where)));
    }

    /**
     * Validar erros na requisição atual
     * 
     * @return boolean      <b>TRUE</b> se validou com sucesso ou <b>FALSE</b>
     */
    public function validar()
    {
        $erro = [];
        
        if (empty($this->moduloFK))
        {
            $erro[] = "Preencha o campo: Módulo";
        }

        if ($this->ordem == "")
        {
            $erro[] = "Preencha o campo: Ordem";
        }

        if (empty($this->nome))
        {
            $erro[] = "Preencha o campo: Nome";
        }

        if (empty($this->descricao))
        {
            $erro[] = "Preencha o campo: Descrição";
        }

        if (empty($this->url))
        {
            $erro[] = "Preencha o campo: URL";
        }

        if ($this->ocultar == "")
        {
            $erro[] = "Preencha o campo: Ocultar";
        }

        if (empty($this->icone))
        {
            $erro[] = "Preencha o campo: Ícone";
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
            'moduloFK'  => $this->moduloFK,
            'ordem'     => $this->ordem,
            'nome'      => $this->nome,
            'descricao' => $this->descricao,
            'url'       => $this->url,
            'ocultar'   => $this->ocultar,
            'icone'     => $this->icone
        ];

        $resultado = $this->registrar($registro, $id);

        if ($resultado)
        {
            unset($_POST);
            
            $this->inserirPermissoes($resultado);

            Alert::set("Registro salvo com sucesso", "success");
            
            return $resultado;
        }
        
        Alert::set("Nenhuma alteração, tente novamente", "warning");
        return 0;
    }
    
    /**
     * Inativar / ativar os registros passados via $_POST['id']
     * 
     * @return int      Quantidade de registros alterados
     */
    public function ocultarExibir()
    {
        $resultado = 0;

        if (!empty($this->id) && is_array($this->id))
        {
            $list = is_array($this->id) ? implode(',', $this->id) : $this->id;

            $resultado = $this->query("UPDATE {$this->tabela} SET ocultar = IF(ocultar, 0, 1) WHERE id IN({$list})");
            $resultado = $resultado->rowCount();

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

    /**
     * Criar as permissões para área especificada a partir dos módulos do sistema
     * 
     * @param int $moduloFK     Index do módulo
     * 
     * @return int              Retorna a quantidade de permissões inseridas
     */
    public function inserirPermissoes($moduloFK)
    {
        $resultado = 0;

        if (!empty($moduloFK))
        {
            $run = (new Area)->consultarAtivo();
            foreach ($run as $row)
            {
                if ((new Permissao)->inserir(['areaFK' => $row['id'], 'moduloFK' => $moduloFK, 'acesso' => 'index,adicionar,editar,excluir']))
                {
                    $resultado++;
                }
            }
        }

        return $resultado;
    }
}
