<?php
require_once '../classe/Database.php';
require_once '../classe/Type.php';
require_once '../classe/Equipement.php';

class Salle {
    private int $id;
    private string $nom;
    private string $description;
    private int $capacite;
    private string $image;
    private float $price;
    private Type $type; // Relation avec Type (1..1)
    private array $equipements = []; // Relation avec Equipement (0..*)

    public function __construct(int $id, string $nom, string $description, int $capacite, string $image, float $price,Type $type) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->capacite = $capacite;
        $this->image = $image;
        $this->price = $price;
        $this->type = $type;
        $type->ajouterSalle($this); // Associer la salle à son type
    }


    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom(string $nom) {
        $this->nom = $nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = $description;
    }

    public function getCapacite() {
        return $this->capacite;
    }

    public function setCapacite(int $capacite) {
        $this->capacite = $capacite;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage(string $image) {
        $this->image = $image;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice(float $price) {
        $this->price = $price;
    }

    public function getType() {
        return $this->type;
    }

    public function setType(Type $type) {
        $this->type = $type;
    }

    public function ajouterEquipement(Equipement $equipement): void {
        $this->equipements[] = $equipement;
    }

    public function clearEquipements() {
        $this->equipements = [];
    }

    public function getEquipements(): array {
        return $this->equipements;
    }

    public function save(): bool {
        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("INSERT INTO salle (nom, \tdescription, capacite, \timage, price, type_id) VALUES (?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$this->nom, $this->description, $this->capacite, $this->image, $this->price, $this->type->getId()])) {
                // Récupérer l'ID de la salle nouvellement insérée
                $this->id = $pdo->lastInsertId();
        
                // Associer les équipements à la salle
                foreach ($this->equipements as $equipement) {
                    $stmt = $pdo->prepare("INSERT INTO salle_equipement (salle_id, equipement_id) VALUES (:salle_id, :equipement_id)");
                    $stmt->bindParam(':salle_id', $this->id, PDO::PARAM_INT);
                    $stmt->bindParam(':equipement_id', $equipement->getId(), PDO::PARAM_INT);
                    $stmt->execute();
                }

                 // Si tout s'est bien passé, valider la transaction
                 $pdo->commit();
                return true;
            }
        }catch (Exception $e) {
            // En cas d'erreur, annuler toutes les modifications
            $pdo->rollBack();
            return false;
        }
    }

    // Récupérer tous les Types
    public static function getAllSalles(): array {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT salle.*, type.nom AS type_nom FROM salle JOIN type ON salle.type_id = type.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un type par ID
    public static function getSalleById(int $id): ?Salle {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM salle WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            // Récupérer le Type séparément
            $type = Type::getTypeById($data['type_id']);
            if (!$type) {
                return null;
            }
            $salle = new Salle($data['id'], $data['nom'],$data['description'],$data['capacite'],$data['image'], $data['price'], $type);

            // Récupérer les équipements et les associer à la salle
            $equipements = self::getEquipementsForSalle($id);
            foreach ($equipements as $equipementData) {
                $equipement = new Equipement($equipementData['id'], $equipementData['nom']);
                $salle->ajouterEquipement($equipement);
            }

            return $salle;
        }
        return null;
    }

    //  Récupérer les équipements d'une salle
    public static function getEquipementsForSalle(int $salleId): array {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT equipement.* FROM equipement 
            JOIN salle_equipement ON equipement.id = salle_equipement.equipement_id
            WHERE salle_equipement.salle_id = :salle_id
        ");
        $stmt->bindParam(':salle_id', $salleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer une salle avec transaction sécurisée
    public static function delete(int $id): bool {
        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();

            // Supprimer les équipements liés
            $stmt = $pdo->prepare("DELETE FROM salle_equipement WHERE salle_id = :salle_id");
            $stmt->bindParam(':salle_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Supprimer la salle
            $stmt = $pdo->prepare("DELETE FROM salle WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    // Mettre à jour une salle avec transaction sécurisée
    public function update(): bool {
        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();

            // Mise à jour de la salle
            $stmt = $pdo->prepare("UPDATE salle SET nom = ?, \tdescription = ?, capacite = ?, \timage = ?, price = ?, type_id = ? WHERE id = ?");
            $stmt->execute([$this->nom, $this->description, $this->capacite, $this->image, $this->price, $this->type->getId(), $this->getId()]);

            // Supprimer les anciens équipements liés
            $stmt = $pdo->prepare("DELETE FROM salle_equipement WHERE salle_id = ?");
            $stmt->execute([$this->id]);

            // Réinsérer les nouveaux équipements
            foreach ($this->equipements as $equipement) {
                $stmt = $pdo->prepare("INSERT INTO salle_equipement (salle_id, equipement_id) VALUES (?, ?)");
                $stmt->execute([$this->id, $equipement->getId()]);
            }

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

}