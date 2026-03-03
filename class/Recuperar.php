<?php

class Recuperar extends Base
{
    /**
     * Executa as funçoes do index para a classe
     * 
     * @return mixed     Retorna a consulta da pesquisa realizada ou os dados do item
     */
    public function index()
    {
        if (!empty($this->recuperar))
        {
            return $this->recuperarConta();
        }

        if (!empty($this->alterar) && !empty($_GET['token']))
        {
            return $this->alterarSenha(Extra::dec($_GET['token']));
        }

        return false;
    }
    
    /**
     * Validar erros na requisição atual
     * 
     * @return boolean      <b>TRUE</b> se validou com sucesso ou <b>FALSE</b>
     */
    public function validarRecuperar()
    {
        if (!Turnstile::valida())
        {
            return ["Resolva o captcha apresentado."];
        }
        
        if (empty($this->email))
        {
            return ["Preencha o campo e-mail"];
        }
        
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
        {
            return ["O e-mail {$this->email} não é válido"];
        }

        return false;
    }
    
    /**
     * Tenta gravar uma recuperação de conta
     * 
     * @return bool         <b>TRUE</b> ou <b>FALSE</b>
     */
    public function recuperarConta()
    {
        $erro = $this->validarRecuperar();

        if ($erro)
        {
            Alert::set($erro);
            return false;
        }
        
        $run = (new Usuario)->consultarAtivo("situacaoFK = 1 AND CAST(email AS BINARY) = '{$this->email}'","","LIMIT 1");

        foreach ($run as $row)
        {
            if (!empty($row['id']))
            {
                $id = $this->inserir(['usuarioFK' => $row['id'], 'ip' => $_SERVER['REMOTE_ADDR']]);

                if ($id)
                {
                    if(!$this->enviarEmail($id, $row))
                    {
                        $this->inativar($id);
                        
                        Alert::set("Falha ao enviar email a: <strong>{$this->email}</strong> contate o suporte.", 'danger');
                        return false;
                    }
                }
            }
        }

        Alert::set("Se houver conta em <strong>{$this->email}</strong>, ela deve receber um e-mail recuperação agora.", 'info');

        Extra::redirecionar("{$_ENV['SG_URL_BACKEND']}login/");

        return true;
    }
    /**
     * Envia o email de dados da recuperação de conta
     * 
     * @param int $id           Index do registro da tabela recuperar
     * @param array $usuario    Array com os dados do usuário
     */
    public function enviarEmail($id, $usuario)
    {
        if (!empty($id) && !empty($usuario['id']))
        {
            $unidade = (new Unidade)->id($usuario['unidadeFK']);
            
            $mensagem =
            [
                'assunto' => "Recuperar Conta - {$unidade['nome']}",
                'nome'    => $usuario['nome'],
                'login'   => $usuario['login'],
                'link'    => $_ENV['SG_URL_BACKEND'] . 'recuperar/?token=' . Extra::enc($id),
                'ip'      => $_SERVER['REMOTE_ADDR'],
                'suporte' => $_ENV['SG_URL_SUPORTE'],
                'unidade' => $unidade['nome'],
            ];
            
            $email = new Email;
            return $email->enviar($usuario['email'], $mensagem['assunto'], $email->getTemplate('recuperar.html', $mensagem));
        }
        
        return false;
    }
    
    /**
     * Validar erros na requisição atual
     * 
     * @return boolean              <b>TRUE</b> se validou com sucesso ou <b>FALSE</b>
     */
    public function validarAlterar()
    {
        if (!Turnstile::valida())
        {
            return ["Resolva o captcha apresentado."];
        }
        
        if(empty($this->senha[0]) || empty($this->senha[1]))
        {
            return ["Preencha os campos: Senha e Repetir Senha"];
        }
        
        if($this->senha[0] !== $this->senha[1])
        {
            return ["A senha digitada não confere com a confirmação"];
        }
        
        if((strlen($this->senha[0]) < 4) || (strlen($this->senha[1]) < 4))
        {
            return ["A senha deve conter 4 caracteres ou mais."];
        }
        
        return false;
    }
    
    /**
     * Tenta ler os dados da recuperação de conta e altera a senha do usuário
     * 
     * @param $id int       Index do registro da tabela recuperar
     * 
     * @return mixed        Retorna <b>TRUE</b> se houver a recuperação ou <b>FALSE</b> se não forem encontrados
     */
    public function alterarSenha($id)
    {
        $recuperar = $this->id($id);

        if (!empty($recuperar['id']) && !empty($recuperar['ativo']) && !empty($recuperar['usuarioFK']))
        {
            if (!empty($this->alterar))
            {
                $erro = $this->validarAlterar();
                
                if ($erro)
                {
                    Alert::set($erro);
                    
                    return false;
                }
                
                $resultado = (new Usuario)->alterar(["senha" => Extra::enc($this->senha[0])], "id = '{$recuperar['usuarioFK']}'");
                
                if ($resultado)
                {
                    $this->alterar(['ativo' => 0], "usuarioFK = '{$recuperar['usuarioFK']}'");
                    
                    Alert::set("Senha definida com sucesso, faça login para continuar", "success");

                    Extra::redirecionar("{$_ENV['SG_URL_BACKEND']}login/");
                    
                    return true;
                }

                Alert::set("Falha ao alterar, verifique a senha e tente novamente.");
            }
        }

        return false;
    }
}
