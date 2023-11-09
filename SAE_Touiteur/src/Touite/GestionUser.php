<?php

namespace Touite;

use BD\ConnectionFactory;
use PDO;

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

    public static function getIdByUsername(string $name) : int
    {
        $db = self::config();
        $query = "SELECT idUser FROM users WHERE username = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$name]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'id");
        }

        $result = $stmt -> fetch(\PDO::FETCH_ASSOC);

        return (int)$result['idUser'];
    }


    public static function abonnementsTag(int $iduser){
        $db = self::config();
        $query = "SELECT tags.labelTag FROM tags 
                  INNER JOIN trackedtag ON tags.idTag = trackedtag.idTag
                  WHERE username = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$iduser]);

        echo "<table border='1'>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['labelTag'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public static function abonnementsUser(int $iduser){
        $db = self::config();
        $query = "SELECT users.username FROM users 
                  inner join followers on users.idUser = followers.idUser2
                  WHERE followers.idUser1 = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$iduser]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print($row['username']);
            echo '<br>';
        }
    }

    public static function follow(int $idFollower, int $idAFollow)
    {
        $db = self::config();
        $query = "INSERT INTO followers (idUser1,idUser2) values (?,?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$idFollower,$idAFollow]);
    }
}