<?php

namespace App\classes\Touite;
use App\classes\db\ConnectionFactory;
use PDO;


class GestionUser
{

    public static function config(): PDO
    {
        ConnectionFactory::setConfig('db.config.ini');
        return ConnectionFactory::makeConnection();
    }

    public static function getUsers(): array
    {
        $db = self::config();
        $query = "SELECT * FROM users";
        $stmt = $db->prepare($query);
        $res = $stmt->execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des utilisateurs");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserbyId(int $idUser): array
    {
        $db = self::config();
        $query = "SELECT * FROM users WHERE idUser = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idUser]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByUsername(string $login): array
    {
        $db = self::config();
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$login]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getIdByUsername(string $name): int
    {
        $db = self::config();
        $query = "SELECT idUser FROM users WHERE username = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$name]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'id");
        }

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int)$result['idUser'];
    }

    public static function isSubscribeUser(int $idUser1, int $idUser2): bool
    {
        $db = self::config();
        $query = "SELECT * FROM followers WHERE idUser1 = ? AND idUser2 = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idUser1, $idUser2]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'id");
        }
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result != null) {
            return true;
        }
        return false;
    }

    public static function abonnementsUser(int $idUser): array
    {
        $db = self::config();
        $query = "SELECT users.username FROM users 
                  inner join followers on users.idUser = followers.idUser2
                  WHERE followers.idUser1 = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$idUser]);
        $res = $stmt->execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function userAbonne(int $idUser)
    {
        $db = self::config();
        $query = "SELECT users.username FROM users 
                  inner join followers on users.idUser = followers.idUser1
                  WHERE followers.idUser2 = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$idUser]);
        $res = $stmt->execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur");
        }
        if ($stmt->rowCount() == 0)
            return null;
        return $stmt->fetch(PDO::FETCH_ASSOC)['username'];
    }

    public static function followUser(int $idFollower, int $idAFollow)
    {
        $db = self::config();
        $query = "INSERT INTO followers (idUser1,idUser2) values (?,?)
                  ON DUPLICATE KEY UPDATE idUser1 = idUser1";
        $stmt = $db->prepare($query);
        $stmt->execute([$idFollower, $idAFollow]);
    }

    public static function unfollowUser(int $idFollower, int $idAFollow)
    {
        $db = self::config();
        if (self::isSubscribeUser($idFollower, $idAFollow)) {
            $query = "DELETE FROM followers WHERE idUser1 = ? AND idUser2 = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$idFollower, $idAFollow]);
        } else {
            throw new \PDOException("Erreur lors de la suppression de l'abonnement");
        }
    }

    public static function getUserTendances()
    {
        $db = self::config();
        $query = "SELECT username, idUser2, count(followers.idUser2) FROM followers
                  inner join users on users.idUser = followers.idUser2
                  group by followers.idUser2
                  order by count(followers.idUser2) desc
                  limit 3";
        $stmt = $db->prepare($query);
        $res = $stmt->execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des tags");
        }
        if ($stmt->rowCount() == 0)
            return null;
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}