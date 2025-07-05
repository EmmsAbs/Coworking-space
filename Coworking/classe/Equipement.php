<?php
require_once '../classe/Database.php';

class Equipement {
    private int $id;
    private string $nom;

    public function __construct(int $id, string $nom) {
        $this->id = $id;
        $this->nom = $nom;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

        // Récupérer tous les équipements
    public static function getAllEquipements(): array {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM equipement");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un équipement par ID
    public static function getEquipementById(int $id): ?Equipement {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM equipement WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Equipement($data['id'], $data['nom']);
        }
        return null;
    }

    // Enregistrer un nouvel équipement
    public function save(): bool {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO equipement (nom) VALUES (?)");
        return $stmt->execute([$this->nom]);
    }

    // Modifier un équipement
    public function update(): bool {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE equipement SET nom = ? WHERE id = ?");
        return $stmt->execute([$this->nom, $this->id]);
    }

    // Supprimer un équipement
    public static function delete(int $id): bool {
        $pdo = Database::connect();
        // Vérifier si l'équipement est associé à une salle
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM salle_equipement WHERE equipement_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            return false;
        } 
        // L'équipement peut être supprimé
        $stmt = $pdo->prepare("DELETE FROM equipement WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
        
    }

}