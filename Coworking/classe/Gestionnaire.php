<?php
require_once 'Utilisateur.php';

class Gestionnaire extends Utilisateur {
    protected int $id;
    private string $type;

    public function __construct($nom, $username, $prenom, $adresse, $CIN, $email, $password, $type) {
        parent::__construct($nom, $username, $prenom, $adresse, $CIN, $email, $password);
        $this->type = $type;
    }

    public function save() {
        parent::save(); // Insère d'abord dans Utilisateur
        $pdo = Database::connect();
        $lastId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO Gestionnaire (id, \ttype) VALUES (?, ?)");
        return $stmt->execute([$lastId, $this->type]);
    }

}
?>