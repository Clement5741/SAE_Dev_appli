<?php
namespace Touite;

use BD\ConnectionFactory;
use PDO;

require_once '../BD/ConnectionFactory.php';
require_once 'GestionUser.php';
require_once 'GestionTag.php';

class GestionTouite
{

    public static function config(): PDO
    {
        ConnectionFactory::setConfig('db.config.ini');
        return ConnectionFactory::makeConnection();
    }

    public static function getTouites() : array
    {
        $db = self::config();
        $query = "SELECT * FROM touites";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des touites");
        }
        return $stmt -> fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getTouite(int $idTouite) : array
    {
        $db = self::config();
        $query = "SELECT * FROM touites WHERE idTouite = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération du touite");
        }
        return $stmt -> fetch(\PDO::FETCH_ASSOC);
    }

    public static function getTouitesByUser(int $idUser) : array
    {
        $db = self::config();
        $query = "SELECT * FROM touites 
         inner join publierPar on publierPar.idTouite = touites.idTouite
         WHERE idUser = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idUser]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des touites");
        }
        return $stmt -> fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getTouitesByTag(int $idTag) : array
    {
        $db = self::config();
        $query = "SELECT * FROM utiliserTag WHERE idTag = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTag]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des touites");
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function setTouite(string $contentTouite, string $username) : void
    {
        $db = self::config();
        $idTouite = self::calcIdTouite();
        $tab = self::checkTag($contentTouite);

        $query = "INSERT INTO touites (idTouite, contentTouite, dateTouite) VALUES (?, ?, ?)";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite, $contentTouite, date('Y-m-d H:i:s')]);
        $idUser = GestionUser::getUserByUsername($username)['idUser'];
        $query2 = "INSERT INTO publierPar (idTouite, idUser) VALUES (?, ?)";
        $stmt2 = $db -> prepare($query2);
        $res2 = $stmt2 -> execute([$idTouite, $idUser]);

        if ($tab != null){
            $query3 = "INSERT INTO utiliserTag (idTag, idTouite) VALUES (?, ?)";
            $stmt3 = $db -> prepare($query3);
            foreach ($tab as $value){
                echo $value;
                $idTag = GestionTag::getTagByLabel($value)['idTag'];
                $res3 = $stmt3 -> execute([$idTag, $idTouite]);
                if (!$res3) {
                    throw new \PDOException("Erreur lors de l'ajout du tag");
                }
            }
        }

        if (!$res2 || !$res) {
            throw new \PDOException("Erreur lors de l'ajout du touite");
        }
    }

    public static function deleteTouite(int $idTouite) : void
    {
        $db = self::config();
        $query = "DELETE FROM touites WHERE idTouite = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la suppression du touite");
        }
    }

    public static function calcIdTouite() : int{
        // On récupère l'identifiant max dans la table Touite
        $db = self::config();
        $max = "select max(idTouite) from Touites;";
        $resultset = $db->prepare($max);
        $resultset->execute();
        $row = $resultset->fetch(PDO::FETCH_ASSOC);
        return $row['max(idTouite)'] + 1;
    }

    // Corriger le bug de la création de tag dans la création de touite (si on crée un tag, il ne s'affiche pas dans la table utiliserTag)
    // Corriger le bug quand on créé 2 tags dans le même touite (le 2ème tag n'est pas ajouté dans la table utiliserTag)

    public static function checkTag(string $contenu) : array{
        $tab = explode(" ", $contenu);
        $tab2 = [];
        foreach ($tab as $value){
            if (substr($value, 0, 1) == "#"){
                $value = substr($value, 1);
                $tab2[] = $value;
                if (GestionTag::getTagByLabel($value) == null){
                    GestionTag::setTag($value, $value);
                }
            }
        }
        return $tab2;
    }

    public static function addImage(int $idTouite, string $image) : void
    {
        $db = self::config();
        $query = "INSERT INTO utiliserImage (idImage, idTouite, utiliserImage) VALUES (?, ?, true)";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$image, $idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'ajout de l'image");
        }
    }

    public static function getIdUserByTouite(int $idTouite) : int
    {
        $db = self::config();
        $query = "SELECT idUser FROM publierpar WHERE idTouite = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'idUser");
        }
        return $stmt -> fetch(\PDO::FETCH_ASSOC)['idUser'];
    }

    public static function likerTouite(int $idTouite) : void
    {
        $db = self::config();
        $query = "UPDATE touites SET nbLike = nbLike + 1 WHERE idTouite = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'ajout du like");
        }
    }

    public static function dislikerTouite(int $idTouite) : void
    {
        $db = self::config();
        $query = "UPDATE touites SET nbLike = nbLike - 1 WHERE idTouite = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'ajout du dislike");
        }
    }



}