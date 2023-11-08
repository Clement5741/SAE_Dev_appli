<?php

namespace Touite;

use BD\ConnectionFactory;
use PDO;

require_once '../BD/ConnectionFactory.php';

class GestionImage
{

    public static function config(): PDO
    {
        ConnectionFactory::setConfig('db.config.ini');
        return ConnectionFactory::makeConnection();
    }


    public static function getImage(int $idImage) : array
    {
        $db = self::config();
        $query = "SELECT * FROM images WHERE idImage = ?";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$idImage]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'image");
        }
        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

    public static function setImage(string $nomImage, string $desc, string $chemin) : void
    {
        $db = self::config();
        $query = "INSERT INTO images (nomImage, descriptionImage, cheminImage) VALUES (?, ?, ?)";
        $stmt = $db -> prepare($query);
        $res = $stmt -> execute([$nomImage, $desc, $chemin]);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'insertion de l'image");
        }
    }

}