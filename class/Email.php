<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    /**
     * Instância do PHPMailer
     * 
     * @var PHPMailer
     */
    private $mail = null;

    /**
     * Construir instância com os dados do SMTP
     */
    function __construct()
    {
        // PHPMailer + Exceptions
        $this->mail = new PHPMailer(true);

        // SMTP
        $this->mail->isSMTP();
        $this->mail->SMTPSecure = $_ENV['SG_SMTP_MODE'];
        $this->mail->SMTPDebug  = $_ENV['SG_SMTP_DEBUG'];
        $this->mail->SMTPAuth   = true;
        
        // Host, Port, Timeout
        $this->mail->Host     = $_ENV['SG_SMTP_HOST'];
        $this->mail->Port     = $_ENV['SG_SMTP_PORT'];
        $this->mail->Timeout  = (!empty($_ENV['SG_SMTP_TIMEOUT']) ? $_ENV['SG_SMTP_TIMEOUT'] : 5);

        // Usuário e Senha
        $this->mail->Username = $_ENV['SG_SMTP_USER'];
        $this->mail->Password = $_ENV['SG_SMTP_PASS'];
        
        // Remetente: Importante ser do usuário SMTP, para prevenção de SPAM
        $this->mail->setFrom($_ENV['SG_SMTP_USER'], $_ENV['SG_SMTP_NAME']);
        
        // HTML
        $this->mail->isHTML(true);
        
        // Charset
        $this->mail->CharSet = PHPMailer::CHARSET_UTF8;

        // Saída DEBUG
        $this->mail->Debugoutput = function ($mensagem)
        {
            error_log($mensagem);
        };
    }
    
    /**
     * Enviar email ao destinatário
     * 
     * @param mixed $destinatario   Destinatário ou array de destinatários
     * @param string $assunto       Assunto
     * @param string $mensagem      Mensagem
     * @param mixed $anexo          Caminho do arquivo de anexo ou array com os caminhos
     * 
     * @return boolean              <b>true</b> em caso de sucesso ou <b>false</b> se falhar
     */
    function enviar($destinatario, $assunto, $mensagem, $anexo = [])
    {
        try
        {
            // Destinatário(s)
            if (!empty($destinatario))
            {
                if (!is_array($destinatario))
                {
                    $this->mail->addAddress($destinatario);
                }
                else
                {
                    foreach ($destinatario as $k => $email)
                    {
                        if (empty($k))
                        {
                            // Destinatário
                            $this->mail->addAddress($email);
                        }
                        else
                        {
                            // Cópia cega
                            $this->mail->addBCC($email);
                        }
                    }
                }
            }

            // Assunto
            $this->mail->Subject = (!empty($assunto) ? $assunto : 'SEM ASSUNTO');

            // Mensagem
            if (!empty($mensagem))
            {
                $this->mail->msgHTML($mensagem);
                $this->mail->Body = $mensagem;
            }

            // Anexo(s)
            if(!empty($anexo))
            {
                if(!is_array($anexo))
                {
                    $this->mail->addAttachment($anexo);
                }
                else
                {
                    foreach($anexo as $path)
                    {
                        $this->mail->addAttachment($path);
                    }
                }
            }

            // Envio
            return $this->mail->send();
        }
        catch (Exception $ex)
        {
            error_log($ex->getMessage());
        }

        return false;
    }
    
    /**
     * Retornar o HTML do teplate com os dados já preenchidos
     * 
     * @param text $arquivo         Caminho do template.html
     * @param text $dados           Array com a chave e o valor a serem trocados no template
     * 
     * @return mixed                Retorna o código HTML ou <b>false</b> se o template não existir
     */
    function getTemplate($arquivo, $dados = [])
    {
        $path = $_ENV['SG_PATH_PUBLIC'] . lcfirst(get_called_class()) . DIRECTORY_SEPARATOR . $arquivo;
        
        if(file_exists($path))
        {
            $html = file_get_contents($path);

            if(!empty($html))
            {
                if(!empty($dados))
                {
                    foreach ($dados as $key => $value)
                    {
                        $html = str_replace('{{' . $key . '}}', $value, $html);
                    }
                }
                
                return $html;
            }
        }
        
        return false;
    }
}

