<?php
class Database {
    private static $host = "localhost";
    private static $dbname = "coworking";
    private static $username = "root";
    private static $password = "";
    private static $port = "3308"; 
    private static $conn = null;

    public static function connect() {
        if (self::$conn == null) {
            try {
                self::$conn = new PDO("mysql:host=" . self::$host . ":" . self::$port . ";dbname=" . self::$dbname, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>