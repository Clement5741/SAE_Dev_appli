<?php

namespace App\classes\action;
use App\classes\Touite\GestionImage;
use App\classes\Touite\GestionTouite;

class touiterAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET'){
            $html .= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Touite</title>
    <link rel="stylesheet" href="src/classes/css/creationTouite.css">
</head>
<body>
<h1>Créer votre Touite : </h1>
<main>
<form action="" method="post" enctype="multipart/form-data">
    <label for="contenu">Contenu : </label><br>
    <textarea name="contenu" rows="10" clos="40"></textarea><br><br>
    <label for="image">Image : </label><br>
    <input type="file" name="image" id="image"><br><br>
    <input type="submit" value="Poster">
</form>
END;
        }
        else {
            session_start();
            if (!isset($_SESSION['user'])) {
                header('Location: index.php');
            }else{
                header('Location: index.php?action=seConnecterAction');
            }
            $contenu = filter_var($_POST['contenu'], FILTER_SANITIZE_STRING);
            $idTouite = GestionTouite::setTouite($contenu, $_SESSION['user']);

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                GestionImage::uploadImage($_FILES['image'], $idTouite);
                throw new \PDOException("Erreur lors de l'insertion de l'image");
            }


        }
        return $html;
    }
}



