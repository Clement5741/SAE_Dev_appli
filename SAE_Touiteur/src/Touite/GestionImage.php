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


    public static function getImage(int $idImage): array
    {
        $db = self::config();
        $query = "SELECT * FROM images WHERE idImage = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idImage]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'image");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getImageByTouite(int $idTouite): ?array
    {
        $db = self::config();
        $query = "SELECT * FROM images 
        inner join utiliserImage on utiliserImage.idImage = images.idImage
        WHERE idTouite = ?";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'image");
        }
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function setImage(string $nomImage, string $chemin): int
    {
        $db = self::config();
        $idImage = self::calcIdImage();
        $query = "INSERT INTO images (idImage, descriptionImage, cheminImage) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idImage, $nomImage, $chemin . $idImage . '.png']);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'insertion de l'image");
        }
        return $idImage;
    }

    public static function calcIdImage()
    {
        $db = self::config();
        $query = "SELECT max(idImage) FROM images";
        $stmt = $db->prepare($query);
        $res = $stmt->execute();
        if (!$res) {
            throw new \PDOException("Erreur lors de la récupération de l'id de l'image");
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['max(idImage)'] + 1;

    }

    public static function addImage(string $idImage, int $idTouite): void
    {
        $db = self::config();
        $query = "INSERT INTO utiliserImage (idImage, idTouite, utiliserImage) VALUES (?, ?, true)";
        $stmt = $db->prepare($query);
        $res = $stmt->execute([$idImage, $idTouite]);
        if (!$res) {
            throw new \PDOException("Erreur lors de l'ajout de l'image");
        }
    }

    public static function uploadImage(array $image, string $idTouite): void
    {
        $nomImage = $image['name'];
        $chemin = '../Images/ImagesTouites/';
        $idImage = self::setImage($nomImage, $chemin);
        $chemin = $chemin . $idImage . '.png';
        move_uploaded_file($image['tmp_name'], $chemin);
        self::addImage($idImage, $idTouite);
    }

}