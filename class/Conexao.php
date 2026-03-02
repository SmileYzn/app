<?php

class Conexao
{
    private static $pdo = null;

    /**
     * Retorna a instância PDO atual
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
                    "mysql:host=localhost;port=3306;dbname=seu_db",
                    "seu_usuario",
                    "sua_senha",
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
