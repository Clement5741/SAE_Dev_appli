<?php

namespace App\classes\action;
class DefaultAction extends Action{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $html = <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de Home</title>
    <link rel="stylesheet" type="text/css" href="src/classes/css/accueil.css">
</head>
<body>
<div class="header">
    <img src="src/classes/Images/logo.png" alt="logo">
    <h1>Touiter</h1>

</div>
<div class="profiles-container">
    <div class="profile">
        <a href="?action=pageDefaultAction"><div class="profile-button">Défault</div></a>
        <p>Par défault</p>
    </div>
    <div class="profile">
        <a href="?action=connexion"><div class="profile-button">Connexion</div></a>
        <p>Connexion</p>
    </div>
    <div class="profile">
        <a href="?action=inscription"><div class="profile-button">S'inscrire</div></a>
        <p>S'Inscrire</p>
    </div>
</div>
</body>
</html>

END;

        return $html;
    }
}
