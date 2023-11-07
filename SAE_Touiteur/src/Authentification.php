<?php

class Authentification{

    public static function authenticate($identifiant, $password) {
        try {
            $db = ConnectionFactory::makeConnection();

            $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

            $stmt = $db->prepare("SELECT password_hash FROM user WHERE username = :username l");
            $stmt->bindParam(':username ', $identifiant);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($hash, $user['password_hash'])) {
                return true;
                echo 'Connection réussie';
            }
        } catch (\PDOException $e) {
            throw new ("Erreur de base de données");
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
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'inscription" . $e->getMessage());
        }
        return true;
    }

}

?>
