<?php
require_once 'Database.php';
require_once 'Salle.php';

class Type {
    private int $id;
    private string $nom;
    private array $salles = []; // Relation avec Salle (0..*)

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

    public function ajouterSalle(Salle $salle): void {
        $this->salles[] = $salle;
    }

    public function getSalles(): array {
        return $this->salles;
    }


    // Récupérer tous les Types
    public static function getAllTypes(): array {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM \ttype");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un type par ID
    public static function getTypeById(int $id): ?Type {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM \ttype WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Type($data['id'], $data['nom']);
        }
        return null;
    }

}