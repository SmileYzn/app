<?php

class Conexao
{
    /**
     * A instância PDO
     * @var Instance
     */
    private static $pdo = null;
    
    /**
     * Retorna a instância PDO atual
     * 
     * @param string $host      Host do banco de dados
     * @param string $port      Porta
     * @param string $dbName    Nome do banco de dados
     * @param string $user      Usuário do banco de dados
     * @param string $password  Senha do usuário
     * 
     * @return PDO
     */
    public static function get()
    {
        if (empty(self::$pdo))
        {
            try
            {
                self::$pdo = new \PDO
                (   
                    sprintf("mysql:host=%s;port=%s;dbname=%s", SG_DB_HOST, SG_DB_PORT, SG_DB_NAME),
                    SG_DB_USER,
                    SG_DB_PASS,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                );
            }
            catch (PDOException $ex)
            {
                die($ex->getMessage());
            }
        }

        return self::$pdo;  
    }
}