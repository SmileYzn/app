<?php

class Login extends Base
{
    /**
     * Validar erros na requisição atual
     * 
     * @return boolean      <b>TRUE</b> se validou com sucesso ou <b>FALSE</b>
     */
    public function valida()
    {
        if (!Turnstile::valida())
        {
            return ["Resolva o captcha apresentado."];
        }

        if(empty($this->login) || empty($this->senha))
        {
            return ["Preencha os campos Usuário e Senha"];
        }

        if($this->contar("ativo AND CAST(login AS BINARY) = '{$this->login}'") >= $_ENV['SG_LOGIN_TENTATIVAS'])
        {
            return ["Bloqueado por excesso de tentativas"];
        }
        
        return false;
    }
    
    /**
     * Iniciar a sessão utilizando o formulário de login
     * 
     * @return bool         <b>TRUE</b> o login foi realizado ou <b>FALSE</b>
     */
    public function login()
    {
        if(!empty($this->entrar))
        {
            $erro = $this->valida();
            
            if ($erro)
            {
                Alert::set($erro);
                return false;
            }
            
            $run = (new Usuario)->consultarAtivo("situacaoFK = 1 AND CAST(login AS BINARY) = '{$this->login}'", "ORDER BY id ASC", "LIMIT 1");

            foreach($run as $row)
            {
                if($this->senha === Extra::dec($row['senha']))
                {
                    $permissao = Permissao::get($row['areaFK']);

                    if (!empty($permissao))
                    {
                        if(!empty($this->cookie))
                        {
                            if (!empty($_ENV['SG_COOKIE_TOKEN']) && !empty($_ENV['SG_COOKIE_DIAS']))
                            {
                                setcookie($_ENV['SG_COOKIE_TOKEN'], Extra::enc($row['id']), (time() + $_ENV['SG_COOKIE_DIAS'] * 86400), '/', $_ENV['SG_SESSAO_DOMINIO']);
                            }
                        }

                        Sessao::set($row);

                        $this->alterar(["ativo" => "0"], "CAST(login AS BINARY) = '{$row['login']}'");

                        Extra::redirecionar($_ENV['SG_URL_BACKEND']);

                        return true;
                    }
                }
            }

            $this->inserir(['ip' => $_SERVER['REMOTE_ADDR'], 'login' => $this->login]);

            Alert::set("Usuário e/ou senha não econtrado(s)");
        }
        
        return false;
    }
    
    /**
     * Finalizar a sessão e redireciona ao login
     */
    public function logout()
    {
        if (!empty($_COOKIE[$_ENV['SG_COOKIE_TOKEN']]))
        {
            unset($_COOKIE[$_ENV['SG_COOKIE_TOKEN']]);
            
            setcookie($_ENV['SG_COOKIE_TOKEN'], '', (time() - 3600), '/', $_ENV['SG_SESSAO_DOMINIO']);
        }
        
        Sessao::destruir();
        
        Extra::redirecionar("{$_ENV['SG_URL_BACKEND']}login/");
        
        return true;
    }
    
    /**
     * Verificar se está logado e redirecionar ao login se não estiver
     * 
     * @param string $url       URL atual a ser verificada
     */
    public function verificar($url)
    {
        if(!in_array($url, ['login/index.php', 'login/logout.php','recuperar/index.php']))
        {
            if (empty(Sessao::get('id')) && !empty($_COOKIE[$_ENV['SG_COOKIE_TOKEN']]))
            {
                $usuario = (new Usuario)->id(Extra::dec($_COOKIE[$_ENV['SG_COOKIE_TOKEN']]));

                if(!empty($usuario['id']))
                {
                    Sessao::set($usuario);
                }
            }
            
            if(empty(Sessao::get('id')) || empty(Sessao::get('ativo')))
            {
                $this->logout();
            }
        }
    }
}
