<?php

namespace Compte;

use BD\ConnectionFactory;
use Exception\AuthException;
use PDO;

require_once '../BD/ConnectionFactory.php';
require_once '../Exception/AuthException.php';

class Authentification
{

    public static function authenticate(string $identifiant, string $mdp)
    {
        try {
            ConnectionFactory::setConfig('db.config.ini');
            $db = ConnectionFactory::makeConnection();

            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$identifiant]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            if (password_verify($mdp, $user['password_hash'])) {
                echo 'Connection réussie';
                return true;
            } else {
                throw new AuthException("Invalid credentials");
            }


        } catch (PDOException $e) {
            throw new ("Erreur de base de données");
        }
    }

    public static function register(string $identifiant, string $nom, string $prenom, string $email, string $password): bool
    {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
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
        return true;
    }

}

