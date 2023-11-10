<?php

namespace App\classes\action;

use App\classes\auth\Authentification;
use App\classes\exception\AuthException;
use PDOException;

class Connexion extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $html = '';
            $html .= <<<END
            <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <link rel="stylesheet" type="text/css" href="src/classes/css/connexion.css">
                    <title>Connexion</title>
                </head>
            <body>
            <div class="header">
                <img src="src/classes/Images/logo.png" alt="logo" id="logo" >
                <h1>Connexion à Twouiter</h1>
            </div>
            <div class="connexion-form">
<form action="" method="post">
    <label for="identifiant">Identifiant ou E-mail : </label>
    <input type="text" name="identifiant" id="identifiant" required>

    <label for="password">Mot de passe : </label>
    <input type="password" name="password" id="password" required>
        
    <input type="submit" value="Se Connecter">
    
    <p><a href="recuperer_mot_de_passe.html">J\'ai oublié mon mot de passe</a></p>
</form>
</div>
            </body>
            </html>
END;

        if ($this->http_method === 'POST') {
            $identifiant = filter_var($_POST['identifiant'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            try {
                Authentification::authenticate($identifiant, $password);
                if (isset($_SESSION['user'])) {
                    //lance l'action execute de le class seConnecterAction qui affiche la page quand on est connecté
                    header('Location: index.php?action=seConnecterAction');
                }
            } catch (PDOException|AuthException $e) {
                 $html .= $e->getMessage();
            }
        }
        return $html;
    }

}
