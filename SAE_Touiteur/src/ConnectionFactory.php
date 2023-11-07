<?php

use PDO;
class ConnectionFactory{
    private static $config = [];
    public static function setConfig($file){

        self::$config = parse_ini_file($file);
    }
    public static function makeConnection(){

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            self::$config['mysql:host'],
            self::$config['dbname'],
            self::$config['charset']
        );

        $pdo = new PDO($dsn, self::$config['username'], self::$config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo("Connexion réussie!");
        return $pdo;
    }
}

?>
