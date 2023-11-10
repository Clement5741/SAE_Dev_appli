<?php

namespace App\classes\action;
use App\classes\Touite\GestionTag;
use App\classes\Touite\GestionUser;

class affichageTagsAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        $html = '';
        $txt = '';
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
        }

        $id = GestionUser::getIdByUsername($_SESSION['user']);

        echo '<p><strong>Vos tags : </strong></p>';
        echo '<div class="tags-container">';
        $t = GestionTag::abonnementsTag($id);
        if ($t == null) {
            $txt .= 'Vous n\'avez pas de tags';
        }else {
            foreach ($t as $val) {
                $txt .= "<a href=\"?action=touiteTagAction&tag=" . $val['labelTag'] . "&page=vostags\">#" . $val['labelTag'] . "</a>";
                $txt .= '<br>';
            }
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
    <a href="?action=affichageProfilAction" class="back-button">&#8592;</a> 
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



