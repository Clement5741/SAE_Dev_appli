<?php

namespace App\classes\action;
use App\classes\Touite\GestionUser;

class affichageAbonnementAction extends Action
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
        $txt .= '<p><strong>Vos abonnements : </strong></p>';
        $txt .= '<div class="abo-container">';
        $abonnements = GestionUser::abonnementsUser($id);

        foreach ($abonnements as $abonnement) {
            foreach ($abonnement as $abo) {
                // Modify the line below according to how you want to display each item in the array
                $txt .= '<div>' . $abo . '</div>';
            }
        }

        $txt .= '</div>';
        $html .= <<<END
<!DOCTYPE html>
<html>
<head>
    <title>Vos abonnements</title>
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



