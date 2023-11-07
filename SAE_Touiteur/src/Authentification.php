<?php

use PDO;

class Auth{

    public static function authenticate($email, $password) {
        try {
            $db = ConnectionFactory::makeConnection();

            $stmt = $db->prepare("SELECT password FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                return true;
            }
        } catch (\PDOException $e) {
            throw new \iutnc\deefy\AuthException("Erreur de base de données");
        }
    }

    public static function register(string $email, string $pass) : bool{
        if (!self::checkPasswordStrength($pass, 4)){
            throw new AuthException("Mot de passe trop faible");
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
        try {
            $db = ConnectionFactory::makeConnection();
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données");
        }

        $query_email = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($query_email);
        $res = $stmt->execute([$email]);
        if($stmt->fetch()){
            throw new Exception("compte deja existant");
        }
        try {
            $query = "INSERT INTO user (email, password) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $res = $stmt->execute([$email, $hash]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'inscription" . $e->getMessage());
        }
        return true;
    }

}

?>
