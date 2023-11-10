<?php

namespace App\classes\action;
use App\classes\Touite\GestionTag;

class afficherToutLesTag extends Action
{

    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        $html = '';
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
        }

        $res = GestionTag::getTags();

        $txt = '
        <br>
        <form action="" method="post">
        <label for="Chercher">Chercher un tag : </label><br>
        <input type="text" name="Chercher" id="Chercher" required>
        <input type="submit" value="Chercher">
        </form>';

        if (isset($_POST['Chercher'])) {
            $searchTerm = $_POST['Chercher'];
            $results = GestionTag::searchTag($searchTerm);

            $txt .= '<p>Résultats de la recherche : </p>';
            $txt .= '<div class="tags-container">';
            if ($results == null) {
                $txt .= 'Aucun résultat';
            }
            foreach ($results as $result) {
                $txt .= "<a href=\"?action=touiteTagAction?tag=" . $result['labelTag'] ."&page=enstag\">#" . $result['labelTag'] . "</a>".'<br>';
            }
            $txt .= '</div>';
        }

        $txt .= '<p><strong>Liste des tags : </strong></p>';
        $txt .= '<div class="tags-container">';
        foreach ($res as $val){
            $txt .= $val['idTag'];
            $txt .=': ';
            $txt .= "<a href=\"?action=touiteTagAction&tag=" . $val['labelTag'] ."&page=enstag\">#" . $val['labelTag'] . "</a>";
            $txt .='<br>';
        }
        $txt .= '</div>';
        $html .= <<<END
<!DOCTYPE html>
<html>
<head>
    <title>Détail du Tweet</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="src/classes/css/affichage_tweet.css">
</head>
<body>
<div class="tweet-container">
    <a href="index.php?action=seConnecterAction" class="back-button">&#8592;</a>
    <main>
    $txt
</main>
</div>
</body>
</html>
END;
        return $html;
    }
}




