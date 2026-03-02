<?php

class Usuario extends Base
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

        if (Sessao::get('areaFK') > 1)
        {
            $where .= " AND id > 1 AND areaFK >= " . Sessao::get('areaFK');
        }

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']))
        {
            $where .= " AND (nome LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%' OR email LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%')";
        }

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['areaFK']))
        {
            $where .= " AND areaFK = '{$_SESSION[$_SERVER['SCRIPT_NAME']]['areaFK']}'";
        }
        
        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][0]))
        {
            $where .= " AND (data >= '{$_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][0]} 00:00:00')";
        }
        
        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][1]))
        {
            $where .= " AND (data <= '{$_SESSION[$_SERVER['SCRIPT_NAME']]['periodo'][1]} 23:59:59')";
        }
        
        return $this->consultarAtivo($where, "ORDER BY nome", Paginacao::limit($this->contar($where)));
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

        if (empty($this->nome))
        {
            $erro[] = "Preencha o campo: Nome";
        }

        if ($this->unidadeFK == "")
        {
            $erro[] = "Preencha o campo: Unidade";
        }

        if (empty($this->areaFK))
        {
            $erro[] = "Preencha o campo: Área";
        }

        if (empty($this->situacaoFK))
        {
            $erro[] = "Preencha o campo: Situação";
        }

        if (empty($this->estadoFK))
        {
            $erro[] = "Preencha o campo: Estado";
        }

        if (empty($this->cidadeFK))
        {
            $erro[] = "Preencha o campo: Cidade";
        }

        if (empty($this->login))
        {
            $erro[] = "Preencha o campo: Usuário";
        }
        else
        {
            if ($this->contar("CAST(login AS BINARY) = '{$this->login}' AND id <> '{$id}'"))
            {
                return ["O Login {$this->login} já está em uso"];
            }
        }

        if (empty($this->senha[0]) || empty($this->senha[1]))
        {
            return ["Preencha os campos: Senha e Confirmar Senha"];
        }

        if ($this->senha[0] !== $this->senha[1])
        {
            return ["A senha digitada não confere com a confirmação"];
        }

        if ((strlen($this->senha[0]) < 4) || (strlen($this->senha[1]) < 4))
        {
            return ["A senha deve conter 4 caracteres ou mais"];
        }

        if (empty($this->email))
        {
            $erro[] = "Preencha o campo: E-mail";
        }
        else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
        {
            $erro[] = ["O E-mail {$this->email} é inválido"];
        }
        else
        {            
            if ($this->contar("email = '$this->email' AND id <> '{$id}'"))
            {
                $erro[] = ["O E-mail {$this->email} já está em uso"];
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
            'unidadeFK'   => $this->unidadeFK,
            'areaFK'      => $this->areaFK,
            'situacaoFK'  => $this->situacaoFK,
            'estadoFK'    => $this->estadoFK,
            'cidadeFK'    => $this->cidadeFK,
            'nome'        => $this->nome,
            'nascimento'  => !empty($this->nascimento) ? $this->nascimento : null,
            'login'       => $this->login,
            'senha'       => Extra::enc($this->senha[0]),
            'email'       => $this->email,
            'cep'         => $this->cep,
            'bairro'      => $this->bairro,
            'logradouro'  => $this->logradouro,
            'numero'      => $this->numero,
            'complemento' => $this->complemento,
            'telefone'    => $this->telefone,
            'celular'     => $this->celular,
            'website'     => $this->website,
            'observacoes' => $this->observacoes,
        ];

        $imagem = (new Upload($this->tabela))->imagem('imagem');

        if(!empty($imagem))
        {
            $registro['imagem'] = reset($imagem);
        }
        
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
    
    /**
     * Enviar instruções de ativação de conta ao usuário 
     * 
     * @param int $id       Index do usuário
     * 
     * @return boolean      Retorna <b>TRUE</b> ou <b>FALSE</b>
     */
    public function enviarInstrucoesAtivacao($id)
    {
        $usuario = $this->id($id);
        
        if (!empty($usuario['id']) && !empty($usuario['email']) && !empty($usuario['unidadeFK']))
        {
            $recuperar = (new Recuperar)->inserir(['usuarioFK' => $usuario['id'], 'ip' => $_SERVER['REMOTE_ADDR']]);

            if(!empty($recuperar))
            {
                $unidade = (new Unidade)->id($usuario['unidadeFK']);

                $mensagem =
                [
                    'assunto' => "Ativação de conta - {$unidade['nome']}",
                    'nome'    => $usuario['nome'],
                    'url'     => $_ENV['SG_URL_BACKEND'],
                    'login'   => $usuario['login'],
                    'link'    => $_ENV['SG_URL_BACKEND'] . "recuperar/?token=" . Extra::enc($recuperar),
                    'ip'      => $_SERVER['REMOTE_ADDR'],
                    'suporte' => $_ENV['SG_URL_SUPORTE'],
                    'unidade' => $unidade['nome'],
                ];

                $email = new Email;
                return $email->enviar($usuario['email'], $mensagem['assunto'], $email->getTemplate('instrucao.html', $mensagem));
            }
        }
        
        return false;
    }
}
