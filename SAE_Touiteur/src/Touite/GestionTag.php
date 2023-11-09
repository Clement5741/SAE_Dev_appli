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

    public static function getidTagByLabel(string $label) : int
    {
        $db = self::config();
        $query = "SELECT idTag FROM tags WHERE labelTag = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$label]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération du tag");
        }

        $result = $stmt -> fetch(\PDO::FETCH_ASSOC);

        return (int)$result['idTag'];
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

    public static function getTagTendances() : ?array
    {
        $db = self::config();
        $query = "SELECT labelTag, count(utiliserTag.idTag) FROM utiliserTag
                  inner join tags on utiliserTag.idTag = tags.idTag
                  group by utiliserTag.idTag
                  order by count(utiliserTag.idTag) desc
                  limit 3";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des tags");
        }
        if ($stmt->rowCount() == 0)
            return null;
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }

    public static function followTag(int $iduser, int $idtag)
    {
        $db = self::config();

        $query = "INSERT INTO trackedtag (idUser,idTag) values (?,?)
                  ON DUPLICATE KEY UPDATE idUser = idUser";
        $stmt = $db->prepare($query);
        $stmt->execute([$iduser,$idtag]);
    }

    public static function abonnementsTag(int $iduser){
        $db = self::config();
        $query = "SELECT tags.labelTag FROM tags 
                  INNER JOIN trackedtag ON tags.idTag = trackedtag.idTag
                  WHERE idUser = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$iduser]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print($row['labelTag']);
            echo '<br>';
        }
        echo "</table>";
    }

    public static function searchTag($searchTerm) {
        $db = self::config();

        // Préparez une requête pour rechercher des tags correspondant au terme de recherche
        $query = "SELECT idTag, labelTag FROM Tags WHERE labelTag LIKE :searchTerm";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();

        // Récupérez les résultats de la recherche
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }


}