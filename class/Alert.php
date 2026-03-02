<?php

class Alert
{
    /**
     * Nome dos dados $_SESSION do componente alert
     * 
     * @var string
     */
    private static string $campo = 'SG_SESSAO_ALERT';
    
    /**
     * Incluir uma mensagem na lista de errros
     * 
     * @param mixed $mensagem      Mensagem a ser incluida na lista de erros
     * @param string $tipo         Tipo do alert: danger, success, warning, information, primary, secondary, light, dark
     * @param boolean $fechar      Exibir o botão fechar (Close) do componente alert
     */
    public static function set($mensagem, $tipo = 'danger', $fechar = true)
    {
        if (!empty($mensagem))
        {
            $_SESSION[self::$campo]['tipo']    = $tipo;
            $_SESSION[self::$campo]['fechar']  = $fechar;
            
            if (is_array($mensagem))
            {
                $_SESSION[self::$campo]['mensagem'] = $mensagem;
            }
            else
            {
                $_SESSION[self::$campo]['mensagem'][] = $mensagem;
            }
        }
    }
    
    /**
     * Remover mensagens de alerta
     */
    public static function clean()
    {
        unset($_SESSION[self::$campo]);
    }
    
    /**
     * Retornar o component alert do bootstrap
     * 
     * @param string       Classe extra para adicionar
     * 
     * @return mixed       HTML contendo o component alert gerado ou <b>false</b>
     */
    public static function show($class = '')
    {
        if (!empty($_SESSION[self::$campo]))
        {
            ob_start();
            ?>
            <div class="alert alert-<?= $_SESSION[self::$campo]['tipo']; ?> <?= ($_SESSION[self::$campo]['fechar']) ? 'alert-dismissible' : ''; ?> fade show <?= $class; ?>" role="alert">
                <?= implode('<br>', $_SESSION[self::$campo]['mensagem']); ?>
                <?php if(!empty($_SESSION[self::$campo]['fechar'])): ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                <?php endif; ?>
            </div>
            <?php
            
            unset($_SESSION[self::$campo]);
            
            return ob_get_clean();
        }
        
        return false;
    }
}
