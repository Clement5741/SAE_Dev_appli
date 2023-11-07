<?php

class Authentification{

    public static function authenticate($identifiant, $password) {
        try {
            $db = ConnectionFactory::makeConnection();

            $query = "SELECT password_hash FROM users WHERE username = ?;";

            $stmt = $db->prepare($query);
            $stmt->execute([$identifiant]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user) {
                if (password_verify($password, $user['password_hash'])) {
                    echo 'Connection réussie';
                } else {
                    echo 'Mot de passe incorrect';
                }
            } else{
                echo 'identifiant incorrect';
            }
        } catch (PDOException $e) {
            throw new PDOException("Erreur de base de données");
        }
    }

    public static function register(string $identifiant, string $nom, string $prenom, string $email, string $password) : bool{

        $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        try {
            $db = ConnectionFactory::makeConnection();
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données");
        }

        $query_username = "SELECT * FROM users WHERE username = ?";
        $stmt = $db->prepare($query_username);
        $stmt->execute([$identifiant]);
        if($stmt->fetch()){
            throw new Exception("compte deja existant");
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

            echo 'Votre compte a été créé';
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'inscription" . $e->getMessage());
        }
        return true;
    }

}

?>
