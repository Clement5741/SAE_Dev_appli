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

    public static function getTouites(): array
    {
        $db = self::config();
        $query = "SELECT * FROM touites
        inner join publierPar on publierPar.idTouite = touites.idTouite
        inner join users on users.idUser = publierPar.idUser
        ORDER BY touites.dateTouite DESC
        ";
        $stmt = $db->prepare($query);
        $res = $stmt->execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des touites");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTouite(int $idTouite): array
    {
        $db = self::config();
        $query = "SELECT * FROM touites
        inner join publierPar on publierPar.idTouite = touites.idTouite
        inner join users on users.idUser = publierPar.idUser
        WHERE touites.idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération du touite");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTouitesByUser(int $idUser): array
    {
        $db = self::config();
        $query = "SELECT * FROM touites
        inner join publierPar on publierPar.idTouite = touites.idTouite
        inner join users on users.idUser = publierPar.idUser
        WHERE publierPar.idUser = ?
        ";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idUser]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des touites");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTouitesByTag(string $labelTag): array
    {
        $db = self::config();
        $query = "SELECT * FROM utiliserTag 
         inner join touites on touites.idTouite = utiliserTag.idTouite
         inner join tags on tags.idTag = utiliserTag.idTag
         WHERE labelTag = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$labelTag]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération des touites");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function setTouite(string $contentTouite, string $username): int
    {
        $db = self::config();
        $idTouite = self::calcIdTouite();
        $tab = self::checkTag($contentTouite);

        $query = "INSERT INTO touites (idTouite, contentTouite, dateTouite) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite, $contentTouite, date('Y-m-d H:i:s')]);
        $idUser = GestionUser::getUserByUsername($username)['idUser'];
        $query2 = "INSERT INTO publierPar (idTouite, idUser) VALUES (?, ?)";
        $stmt2 = $db->prepare($query2);
        $res2 = $stmt2->execute([$idTouite, $idUser]);

        if ($tab != null) {
            $query3 = "INSERT INTO utiliserTag (idTag, idTouite) VALUES (?, ?)";
            $stmt3 = $db->prepare($query3);
            foreach ($tab as $value) {
                echo $value;
                $idTag = GestionTag::getTagByLabel($value)['idTag'];
                $res3 = $stmt3->execute([$idTag, $idTouite]);
                if (!$res3) {
                    throw new \PDOException("Erreur lors de l'ajout du tag");
                }
            }
        }

        if (!$res2 || !$res) {
            throw new \PDOException("Erreur lors de l'ajout du touite");
        }
        return $idTouite;
    }

    public static function deleteTouite(int $idTouite): void
    {
        $db = self::config();
        $query = "DELETE FROM touites WHERE idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la suppression du touite");
        }
    }

    public static function calcIdTouite(): int
    {
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

    public static function checkTag(string $contenu): array
    {
        $tab = explode(" ", $contenu);
        $tab2 = [];
        foreach ($tab as $value) {
            if (substr($value, 0, 1) == "#") {
                $value = substr($value, 1);
                $tab2[] = $value;
                if (GestionTag::getTagByLabel($value) == null) {
                    GestionTag::setTag($value, $value);
                }
            }
        }
        return $tab2;
    }


    public static function getIdUserByTouite(int $idTouite): int
    {
        $db = self::config();
        $query = "SELECT idUser FROM publierpar WHERE idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'idUser");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC)['idUser'];
    }

    public static function likerTouite(int $idTouite): void
    {
        $db = self::config();
        $query = "UPDATE touites SET nbLike = nbLike + 1 WHERE idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'ajout du like");
        }
    }

    public static function dislikerTouite(int $idTouite): void
    {
        $db = self::config();
        $query = "UPDATE touites SET nbLike = nbLike - 1 WHERE idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'ajout du dislike");
        }
    }

    public static function getScoreMoyenTouite(string $idTouite): int
    {
        $db = self::config();
        $query = "SELECT notePerti FROM Touites WHERE idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération du score moyen");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC)['notePerti'];
    }

    public static function afficherContenuTouiteAvecLienTag(string $contenu, int $idTouite): string
    {
        $tab = explode(" ", $contenu);
        $tab2 = [];
        foreach ($tab as $value) {
            if (substr($value, 0, 1) == "#") {
                $value = substr($value, 1);
                $tab2[] = $value;
            }
        }
        foreach ($tab2 as $value) {
            $contenu = str_replace("#" . $value, "<a href=\"touiteTag.php?tag=" . $value ."&page=affichage&touite=" . $idTouite . "\">#" . $value . "</a>", $contenu);
        }
        return $contenu;
    }



}