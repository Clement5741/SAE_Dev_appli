<?php
namespace App\classes\auth;
use Compte\Authentification;
require_once 'Authentification.php';

if (Authentification::isLogged()) {
    header('Location: ../HTML/accueil.html');
    // On le déconnecte
    Authentification::logout();
}