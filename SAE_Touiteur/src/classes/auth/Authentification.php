<?php

namespace App\classes\auth;
session_start();

use App\classes\db\ConnectionFactory;
use App\classes\exception\AuthException;
use PDO;
use PDOException;


class Authentification
{

    public static function authenticate(string $identifiant, string $mdp)
    {
        try {
            ConnectionFactory::setConfig('db.config.ini');
            $db = ConnectionFactory::makeConnection();

            // On vérifie s'il y a un @ dans l'identifiant
            if (strpos($identifiant, '@') !== false) {
                $query = "SELECT * FROM users WHERE email = ?";
            } else {
                $query = "SELECT * FROM users WHERE username = ?";
            }

            $stmt = $db->prepare($query);
            $stmt->execute([$identifiant]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new AuthException("Identifiant inconnu");
            }

            if (password_verify($mdp, $user['password_hash'])) {
                $_SESSION['user'] = $identifiant;
                echo 'Connection réussie';
                return;
            } else {
                throw new AuthException("Mot de passe incorrect");
            }

        } catch (PDOException $e) {
            throw new ("Erreur de base de données");
        }
    }

    public static function logout() : string
    {
        session_destroy();
        return "Vous avez bien été déconnecté";
    }

    public static function isLogged() : bool
    {
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkPasswordStrength(string $pass, int $minimumLength): bool {
        $length = (strlen($pass) > $minimumLength); // longueur minimale
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $special = preg_match("#[\W]#", $pass); // au moins un car. spécial
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule
        if (!$length || !$digit || !$special || !$lower || !$upper)return false;
        return true;
    }

    public static function register(string $identifiant, string $nom, string $prenom, string $email, string $password): bool
    {
        if(self::checkPasswordStrength($password, 8)) {

            $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);

            try {
                ConnectionFactory::setConfig('db.config.ini');
                $db = ConnectionFactory::makeConnection();
            } catch (PDOException $e) {
                throw new AuthException("Erreur de connexion à la base de données");
            }

            $query_username = "SELECT * FROM users WHERE username = ?";
            $stmt = $db->prepare($query_username);
            $stmt->execute([$identifiant]);
            if ($stmt->fetch()) {
                throw new AuthException("compte deja existant");
            }

            // On récupère l'identifiant max dans la table Users
            $max = "select max(idUser) from Users;";
            $resultset = $db->prepare($max);
            $resultset->execute();
            $row = $resultset->fetch(PDO::FETCH_ASSOC);
            $idUser = $row['max(idUser)'] + 1;

            try {
                $query = "insert into Users (idUser, username,name,firstname,email,password_hash) 
                          values ($idUser,?,?,?,?,?);";
                $stmt = $db->prepare($query);
                $stmt->execute([$identifiant, $nom, $prenom, $email, $hash]);
            } catch (PDOException $e) {
                throw new AuthException("Erreur lors de l'inscription" . $e->getMessage());
            }
        }else{
            throw new AuthException("mot de passe incorrect : il doit faire 9 car minimum, dont un digit, un car. spécial, une minuscule et une majuscule");
        }
        return true;
    }

}

