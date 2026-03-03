<?php

class Setup
{
    private static $sqlTable = "./public/sql/instalar.sql";
    private static $sqlDados = "./public/sql/instalar_dados.sql";
    /**
     * Executa os passos do instalador
     */
    public static function iniciar()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            if (!headers_sent())
            {
                session_start();
            }
        }

        // Se voltou um passo
        if (!empty($_REQUEST['voltar']))
        {
            // $_POST
            if (isset($_SESSION[$_REQUEST['voltar']]))
            {
                // $_POST
                $_POST = (!empty($_SESSION[$_REQUEST['voltar']]) ? $_SESSION[$_REQUEST['voltar']] : []);
                
                // Alert
                unset($_SESSION['alert']);
                
                // Unset
                unset($_SESSION[$_REQUEST['voltar']]);
            }
            
            return true;
        }
        
        // Se instalar
        if (!empty($_POST['instalar']))
        {
            // Tentar instalar o passo selecionado
            switch ($_POST['instalar'])
            {
                case 'conexao':
                {
                    // Validar Host e Porta
                    if (empty($_POST['host']) || empty($_POST['port']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Servidor e Porta."];
                        return false;
                    }

                    // Validar nome do banco de dados
                    if (empty($_POST['dbName']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Nome do banco."];
                        return false;
                    }

                    // Validar usuário e senha
                    if (empty($_POST['user']) || empty($_POST['password']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Usuário e Senha."];
                        return false;
                    }

                    try
                    {
                        // Vamos tentar conectar-se diretamente usando PDO
                        $pdo = new \PDO
                        (
                            "mysql:host={$_POST['host']};port={$_POST['port']};dbname={$_POST['dbName']}",
                            $_POST['user'],
                            $_POST['password'],
                            [
                                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                            ]
                        );
                        
                        // Gravar db.php
                        $db = "<?php\n";
                        $db .= "define('SG_DB_HOST', '{$_POST['host']}');\n";
                        $db .= "define('SG_DB_PORT', '{$_POST['port']}');\n";
                        $db .= "define('SG_DB_NAME', '{$_POST['dbName']}');\n";
                        $db .= "define('SG_DB_USER', '{$_POST['user']}');\n";
                        $db .= "define('SG_DB_PASS', '{$_POST['password']}');";

                        // Criar arquivo
                        if (file_put_contents("db.php", $db) < 1)
                        {
                            // Apagar dados
                            unset($_SESSION['conexao']);
                            
                            // Retornar erro
                            $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Falha ao criar configuração, tente novamente."];
                            
                            return false;
                        }
                        
                        // Salvar os dados
                        $_SESSION['conexao'] = $_POST;
                        
                        // Mensagem alert
                        $_SESSION['alert'] = ['tipo' => 'success', 'mensagem' => "Conexão com o banco de dados estabelecida, preencha os dados para continuar."];

                        // Unset
                        unset($_POST);

                        return true;
                    }
                    catch (\PDOException $ex)
                    {
                        // Limpar conexão
                        unset($_SESSION['conexao']);

                        // Alert
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Ocorreram os seguintes erros:<br><br><pre>{$ex->getMessage()}</pre>"];
                        return false;
                    }
                    break;
                }
                case 'configuracao':
                {
                    // Validar Servidor SMTP
                    if (empty($_POST['SG_SMTP_HOST']) || empty($_POST['SG_SMTP_PORT']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Servidor e Porta."];
                        return false;
                    }
                    
                    // Validar STMP Mode
                    if (empty($_POST['SG_SMTP_MODE']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Autenticação."];
                        return false;
                    }
                    
                    // Validar Email
                    if (empty($_POST['SG_SMTP_USER']) || !filter_var($_POST['SG_SMTP_USER'], FILTER_VALIDATE_EMAIL))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Email com um endereço válido."];
                        return false;
                    }
                    
                    // Validar Senha
                    if (empty($_POST['SG_SMTP_PASS']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Senha."];
                        return false;
                    }
                    
                    // Setar configurações
                    $_SESSION['configuracao'] =
                    [
                        'SG_URL_BACKEND'  => sprintf("%s://{$_SERVER['HTTP_HOST']}/", ($_SERVER['HTTPS'] == 'on' ? "https" : "http")),
                        'SG_URL_FRONTEND' => sprintf("%s://{$_SERVER['HTTP_HOST']}/", ($_SERVER['HTTPS'] == 'on' ? "https" : "http")),
                        'SG_PATH'         => sprintf("%s/", rtrim($_SERVER['DOCUMENT_ROOT'], "/")),
                        'SG_PATH_PUBLIC'  => sprintf("%s/public/", rtrim($_SERVER['DOCUMENT_ROOT'], "/")),
                        'SG_SMTP_HOST'    => $_POST['SG_SMTP_HOST'],
                        'SG_SMTP_PORT'    => $_POST['SG_SMTP_PORT'],
                        'SG_SMTP_MODE'    => $_POST['SG_SMTP_MODE'],
                        'SG_SMTP_USER'    => $_POST['SG_SMTP_USER'],
                        'SG_SMTP_PASS'    => $_POST['SG_SMTP_PASS'],
                        'SG_SMTP_NAME'    => $_POST['SG_SMTP_NAME']
                    ];

                    // Mensagem alert
                    $_SESSION['alert'] = ['tipo' => 'success', 'mensagem' => "Configurações concluídas, preencha os dados do usuário."];

                    // Unset
                    unset($_POST);

                    return true;
                }
                case 'usuario':
                {
                    // Validar Nome
                    if (empty($_POST['nome']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Nome."];
                        return false;
                    }

                    // Validar Login
                    if (empty($_POST['login']))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Usuário."];
                        return false;
                    }

                    // Validar Email
                    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha o campo: Email com um endereço válido."];
                        return false;
                    }

                    // Validar Senha
                    if (empty($_POST['senha'][0]) || empty($_POST['senha'][1]))
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Preencha os campos: Senha e Confirmar Senha."];
                        return false;
                    }

                    // Validar Senha (Confirmação)
                    if ($_POST['senha'][0] != $_POST['senha'][1])
                    {
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "As senhas digitadas não conferem."];
                        return false;
                    }

                    try
                    {
                        // Usuário
                        $_SESSION['usuario'] =
                        [
                            'nome'  => $_POST['nome'],
                            'email' => $_POST['email'],
                            'login' => $_POST['login'],
                            'senha' => Extra::enc($_POST['senha'][0])
                        ];

                        // PDO
                        $pdo = new \PDO
                        (
                            "mysql:host={$_SESSION['conexao']['host']};port={$_SESSION['conexao']['port']};dbname={$_SESSION['conexao']['dbName']}",
                            $_SESSION['conexao']['user'],
                            $_SESSION['conexao']['password'],
                            [
                                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                            ]
                        );

                        // Tabelas
                        $tabelas = file_get_contents(self::$sqlTable);

                        // Dados
                        $dados = file_get_contents(self::$sqlDados);

                        // Tabelas
                        $pdo->exec($tabelas);

                        // Dados
                        $pdo->exec($dados);

                        // Atualizar Configurações
                        if (!empty($_SESSION['configuracao']))
                        {
                            foreach ($_SESSION['configuracao'] as $chave => $valor)
                            {
                                $pdo->exec("UPDATE configuracao SET valor = '{$valor}' WHERE chave = '{$chave}'");
                            }
                        }

                        // Atualizar usuário
                        $run = $pdo->prepare("UPDATE usuario SET nome = ?, email = ?, login = ?, senha = ?");
                        $run->execute(array_values($_SESSION['usuario']));
                        
                        // Unset
                        unset($_POST);

                        // Unset
                        unset($_SESSION);

                        // Destroy $_SESSION
                        session_destroy();

                        // Instalação concluída
                        Extra::redirecionar("index.php");

                        return false;
                    }
                    catch (\Exception $ex)
                    {
                        // Limpar usuário
                        unset($_SESSION['usuario']);

                        // Alert
                        $_SESSION['alert'] = ['tipo' => 'danger', 'mensagem' => "Ocorreram os seguintes erros:<br><br><pre>{$ex->getMessage()}</pre>"];
                        return false;
                    }
                    break;
                }
            }   
        }
        
        return true;
    }
}
