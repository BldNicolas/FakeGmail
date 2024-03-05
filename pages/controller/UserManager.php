<?php
namespace pages\controller;

use PDO;
use PDOException;

class UserManager
{
    const dbAddress = "localhost";
    const dbUser = "root";
    const dbPassword = "";
    const dbName = "fakegmail";

    /**
     * Check if the database exist
     * @param string $dbName Name of the database to check
     * @return bool True if existed, false if not
     */
    static function checkDBExists(string $dbName): bool
    {
        try {
            $connexion = new PDO("mysql:host=".self::dbAddress, self::dbUser, self::dbPassword);
            $connexion->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'";
            $result = $connexion->query($sql);
            return $result->rowCount() > 0;
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>showNotification('$error', 'error');</script>";
            return false;
        }
    }

    /**
     * Check if the table exist
     * @param string $tableName Table of the database to check
     * @param string $dbName Name of the database
     * @return bool
     */
    static function checkTableExists(string $tableName, string $dbName): bool
    {
        try {
            $connexion = new PDO("mysql:host=".self::dbAddress.";dbname=$dbName", self::dbUser, self::dbPassword);
            $connexion->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '$tableName' AND TABLE_SCHEMA = '$dbName'";
            $result = $connexion->query($sql);
            return $result->rowCount() > 0;
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo "<script>showNotification('$error', 'error');</script>";
            return false;
        }
    }


    /**
     * Create the database
     * @param string $dbName Name of the database
     * @return void
     */
    static function createDB(string $dbName)
    {
        if (!UserManager::checkDBExists($dbName)) {
            try {
                $connexion = new PDO("mysql:host=".self::dbAddress, self::dbUser, self::dbPassword);
                $connexion->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "CREATE DATABASE $dbName";
                $connexion->exec($sql);
            } catch (PDOException $e) {
                $error = $e->getMessage();
                echo "<script>showNotification('$error', 'error');</script>";
            }
        }
    }

    /**
     * Create the tables of the database
     * @param string $tableName Name of the table
     * @param string $dbName Name of the database
     * @return void
     */
    static function createTable(string $tableName, string $dbName)
    {
        if (!UserManager::checkTableExists($tableName, $dbName)) {
            try {
                $connexion = new PDO("mysql:host=".self::dbAddress.";dbname=$dbName", self::dbUser, self::dbPassword);
                $connexion->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "CREATE TABLE $tableName (
                id INT AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                mail VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL
            )";
                $connexion->exec($sql);
            } catch (PDOException $e) {
                $error = $e->getMessage();
                echo "<script>showNotification('$error', 'error');</script>";
            }
        }
    }

    /**
     * Get the data, check it and store it in the database
     * @return void
     */
    static function signUp()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userLastName = trim($_POST["user_last_name"]);
            $userFirstName = trim($_POST["user_first_name"]);
            $userMail = trim($_POST["user_mail"]);
            $userPassword = trim($_POST["user_password"]);

            if (empty($userLastName) || empty($userFirstName) || empty($userMail) || empty($userPassword)) {
                echo "<script>showNotification('Tous les champs sont obligatoires.', 'error');</script>";
                return;
            }

            if (!filter_var($userMail, FILTER_VALIDATE_EMAIL)) {
                echo "<script>showNotification('Veuillez rentrer une adresse mail valide.', 'error');</script>";
                return;
            }

            try {
                $connexion = new PDO("mysql:host=".self::dbAddress.";dbname=".self::dbName, self::dbUser, self::dbPassword);
                $connexion->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $checkRequest = $connexion->prepare("SELECT id FROM user WHERE mail = ?");
                $checkRequest->bindParam(1, $userMail);
                $checkRequest->execute();

                if ($checkRequest->rowCount() > 0) {
                    echo "<script>showNotification('Cette adresse mail est déjà renseignée, veuillez vous connectez.', 'error');</script>";
                    return;
                }
            } catch (PDOException $e) {
                $error = $e->getMessage();
                echo "<script>showNotification('$error', 'error');</script>";
                return;
            }

            $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

            try {
                $request = $connexion->prepare("INSERT INTO user (last_name, first_name, mail, password) VALUES (?, ?, ?, ?)");
                $request->bindParam(1, $userLastName);
                $request->bindParam(2, $userFirstName);
                $request->bindParam(3, $userMail);
                $request->bindParam(4, $hashedPassword);
                $request->execute();

                $_SESSION['user_created'] = 'true';
                $_SESSION['user_last_name'] = $userLastName;
                $_SESSION['user_first_name'] = $userFirstName;
                $_SESSION['user_mail'] = $userMail;

                header("Location: connexion.php");
            } catch (PDOException $e) {
                $error = $e->getMessage();
                echo "<script>showNotification('$error', 'error');</script>";
            }
        }
    }

    /**
     * Get the data and compare it with the database
     * @return void
     */
    static function logIn()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userEmail = trim($_POST["user_mail"]);
            $userPassword = trim($_POST["user_password"]);
        }
        if (isset($userEmail, $userPassword)) {
            if (UserManager::checkLogIn($userEmail, $userPassword)) {
                echo "<script>showNotification('Connexion réussie.', 'success');</script>";
            } else {
                echo "<script>showNotification('Identifiants incorrects.', 'error');</script>";
            }
        }

    }

    /**
     * Check user login credentials
     * @param string $email User email
     * @param string $password User password
     * @return bool True if credentials are valid, false otherwise
     */
    static function checkLogIn(string $email, string $password): bool
    {
        try {
            $connexion = new PDO("mysql:host=".self::dbAddress.";dbname=".self::dbName, self::dbUser, self::dbPassword);
            $connexion->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $request = $connexion->prepare("SELECT * FROM user WHERE mail = ?");
            $request->bindParam(1, $email);
            $request->execute();

            if ($request->rowCount() > 0) {
                $userData = $request->fetch(PDO::FETCH_ASSOC);
                return password_verify($password, $userData['password']);
            }

            return false;
        } catch (PDOException $e) {
            echo "<script>showNotification('Une erreur est survenue, veuillez réessayer plus tard.', 'error');</script>";
            return false;
        }
    }
}