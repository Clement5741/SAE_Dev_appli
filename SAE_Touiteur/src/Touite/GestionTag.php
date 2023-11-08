<?php
namespace Touite;

use BD\ConnectionFactory;
use PDO;

require_once '../BD/ConnectionFactory.php';

class GestionTag
{


    public static function config(): PDO
    {
        ConnectionFactory::setConfig('db.config.ini');
        return ConnectionFactory::makeConnection();
    }

    public static function getTags() : array
    {
        $db = self::config();
        $query = "SELECT * FROM tags";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des tags");
        }
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTag(int $idTag) : array
    {
        $db = self::config();
        $query = "SELECT * FROM tags WHERE idTag = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTag]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération du tag");
        }
        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

    public static function getTagsByTouite(int $idTouite) : array
    {
        $db = self::config();
        $query = "SELECT * FROM utiliserTag WHERE idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des tags");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTagByLabel(string $label) : ?array
    {
        $db = self::config();
        $query = "SELECT * FROM tags WHERE labelTag = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$label]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération du tag");
        }
        if ($stmt->rowCount() == 0)
            return null;
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function setTag(string $libelle, string $desc){
        $db = self::config();
        $idTag = self::calcIdTag();
        $query = "INSERT INTO tags (idTag, labelTag, descriptionTag) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTag, $libelle, $desc]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la création du tag");
        }
    }

    public static function creerTagViaTouite(string $contentTouite, string $username) : void
    {
        $db = self::config();
        $idTouite = GestionTouite::calcIdTouite();
        $tab = self::checkTag($contentTouite);

        $query = "INSERT INTO touites (idTouite, contentTouite, dateTouite) VALUES (?, ?, ?)";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite, $contentTouite, date("Y-m-d H:i:s")]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la création du touite");
        }

        $query = "INSERT INTO publierPar (idTouite, idUser) VALUES (?, ?)";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite, GestionUser::getUserByUsername($username)['idUser']]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la création du touite");
        }

        foreach ($tab as $tag) {
            $idTag = self::getTagByLabel($tag)['idTag'];
            if ($idTag == null) {
                self::setTag($tag, "");
                $idTag = self::getTagByLabel($tag)['idTag'];
            }
            $query = "INSERT INTO utiliserTag (idTouite, idTag) VALUES (?, ?)";
            $stmt = $db -> prepare($query);
            $res = $stmt -> execute([$idTouite, $idTag]);
            if (!$res) {
                throw new \PDOException("Erreur lors de la création du touite");
            }
        }
    }

    public static function calcIdTag()
    {
        $db = self::config();
        $query = "SELECT max(idTag) FROM tags";
        $stmt = $db->prepare($query);
        $res = $stmt->execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération du tag");
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['max(idTag)'] + 1;
    }


}