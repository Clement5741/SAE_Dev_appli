<?php

namespace Touite;

use BD\ConnectionFactory;

require_once '../BD/ConnectionFactory.php';

class GestionUser
{

    public static function config(): \PDO
    {
        ConnectionFactory::setConfig('db.config.ini');
        return ConnectionFactory::makeConnection();
    }

    public static function getUsers() : array
    {
        $db = self::config();
        $query = "SELECT * FROM users";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des utilisateurs");
        }
        return $stmt -> fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getUserbyId(int $idUser) : array
    {
        $db = self::config();
        $query = "SELECT * FROM users WHERE idUser = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idUser]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur");
        }
        return $stmt -> fetch(\PDO::FETCH_ASSOC);
    }

    public static function getUserByUsername(string $login) : array
    {
        $db = self::config();
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$login]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur");
        }
        return $stmt -> fetch(\PDO::FETCH_ASSOC);
    }

}