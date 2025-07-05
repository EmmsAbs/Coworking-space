<?php
require_once '../classe/Database.php';
require_once '../classe/Salle.php';
require_once '../classe/Client.php';
require_once '../classe/Reservation.php';

class Evenement {
    private int $id;
    private string $nom;
    private string $description;
    private string $image;
    private float $tarif;
    private int $participants;
    private DateTime $dateDebut;
    private DateTime $dateFin;
    private Salle $salle;
    private array $clients = [];

    public function __construct(
        int $id, string $nom, string $description, string $image, float $tarif, 
        int $participants, DateTime $dateDebut, DateTime $dateFin, Salle $salle
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
        $this->tarif = $tarif;
        $this->participants = $participants;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->salle = $salle;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getDescription(): string { return $this->description; }
    public function getImage(): string { return $this->image; }
    public function getTarif(): float { return $this->tarif; }
    public function getParticipants(): int { return $this->participants; }
    public function getDateDebut(): DateTime { return $this->dateDebut; }
    public function getDateFin(): DateTime { return $this->dateFin; }
    public function getSalle(): Salle { return $this->salle; }
    public function getClients(): array {return $this->clients;}


    // Setters
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setImage(string $image): void { $this->image = $image; }
    public function setTarif(float $tarif): void { $this->tarif = $tarif; }
    public function setParticipants(int $participants): void { $this->participants = $participants; }
    public function setDateDebut(DateTime $dateDebut): void { $this->dateDebut = $dateDebut; }
    public function setDateFin(DateTime $dateFin): void { $this->dateFin = $dateFin; }
    public function setSalle(Salle $salle): void { $this->salle = $salle; }
    public function ajouterClient(Client $client): void {$this->clients[] = $client;}
    public function clearClients() { $this->clients = [];}

    public function save(): bool {
        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("INSERT INTO evenement (nom, \tdescription, \timage, tarif, participants, dateDebut, dateFin, salle_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $this->nom,
                $this->description,
                $this->image,
                $this->tarif,
                $this->participants,
                $this->dateDebut->format('Y-m-d H:i:s'),
                $this->dateFin->format('Y-m-d H:i:s'),
                $this->salle->getId()
            ]);
            $this->id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO reservation (salle_id, dateDebut, dateFin) VALUES (?, ?, ?)");
            $stmt->execute([
                $this->salle->getId(),
                $this->dateDebut->format('Y-m-d H:i:s'),
                $this->dateFin->format('Y-m-d H:i:s'),
            ]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function reserverEvenement(int $evenementId, int $salleId, int $clientId, DateTime $dateDebut, DateTime $dateFin): bool {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO evenement (evenement_id, salle_id, client_id, dateDebut, dateFin, heureDebut, heureFin) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $evenementId,
            $salleId,
            $clientId,
            $dateDebut->format('Y-m-d H:i:s'),
            $dateFin->format('Y-m-d H:i:s'),
        ]);
    }

    public static function getReservationsForEvenement(int $evenementId): array {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM evenement_salle_client WHERE evenement_id = ?");
        $stmt->execute([$evenementId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllEvenements(): array {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM evenement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getEvenementById(int $id): ?Evenement {
        $pdo = Database::connect();
        // Modifions la requête pour récupérer toutes les informations nécessaires
        $stmt = $pdo->prepare("
        SELECT * FROM evenement e WHERE e.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        

        if (!$result) return null;
        // Créer l'objet Salle avec tous les paramètres requis
        $salle = Salle::getSalleById(intval($result['salle_id']));
        if (!$salle) return null;
        
        $event = new Evenement(
            $result['id'],
            $result['nom'],
            $result['description'],
            $result['image'],
            $result['tarif'],
            $result['participants'],
            new DateTime($result['dateDebut']),
            new DateTime($result['dateFin']),
            $salle
        );

        // Récupérer les clients et les associer un evenement
        $clients = self::getEventClients($id);
        foreach ($clients as $clientData) {
            $client = Client::getClientByID(intval($clientData['id']));
            $event->ajouterClient($client);
        }
        
        return $event;
    }
    
      //  Récupérer les Client d'un evenement
    public static function getEventClients(int $eventId): array {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
        SELECT client.* FROM client 
        JOIN participation ON client.id = participation.client_id
        WHERE participation.evenement_id = :event_id
        ");
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(): bool {
        $pdo = Database::connect();
        try {
            $stmt = $pdo->prepare("UPDATE evenement SET nom = ?, description = ?, image = ?, tarif = ?, participants = ?, dateDebut = ?, dateFin = ?, salle_id = ? WHERE id = ?");
            return $stmt->execute([
                $this->nom,
                $this->description,
                $this->image,
                $this->tarif,
                $this->participants,
                $this->dateDebut->format('Y-m-d H:i:s'),
                $this->dateFin->format('Y-m-d H:i:s'),
                $this->salle->getId(),
                $this->id
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function delete(int $id): bool {
        $pdo = Database::connect();
        try {
            $stmt = $pdo->prepare("DELETE FROM evenement WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    public static function ajouterParticipation($evenementId, $clientId) {
        try {
            $pdo = Database::connect();
            
            // 1. Vérifier si l'événement existe
            $stmt = $pdo->prepare("SELECT * FROM evenement WHERE id = ?");
            $stmt->execute([$evenementId]);
            $evenement = $stmt->fetch();
            
            if (!$evenement) {
                error_log("Événement non trouvé: ID " . $evenementId);
                return false;
            }
            
            // 2. Vérifier si le client existe
            $stmt = $pdo->prepare("SELECT * FROM client WHERE id = ?");
            $stmt->execute([$clientId]);
            $client = $stmt->fetch();
            
            if (!$client) {
                error_log("Client non trouvé: ID " . $clientId);
                return false;
            }
            
            // 3. Vérifier si le client participe déjà
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM participation WHERE evenement_id = ? AND client_id = ? AND active = 1");
            $stmt->execute([$evenementId, $clientId]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                error_log("Participation existante: Evenement ID: $evenementId, Client ID: $clientId");
                return false;
            }
            
            // 4. Vérifier si l'événement n'est pas complet
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM participation WHERE evenement_id = ? AND active = 1");
            $stmt->execute([$evenementId]);
            $participantCount = $stmt->fetchColumn();
            
            if ($participantCount >= $evenement['participants']) {
                error_log("Événement complet: " . $evenementId);
                return false;
            }
            
            // 5. Ajouter la participation dans une transaction
            $pdo->beginTransaction();
            
            try {
                // Si une participation inactive existe déjà, la réactiver
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM participation WHERE evenement_id = ? AND client_id = ? AND active = 0");
                $stmt->execute([$evenementId, $clientId]);
                $inactiveExists = $stmt->fetchColumn() > 0;
                
                if ($inactiveExists) {
                    $stmt = $pdo->prepare("UPDATE participation SET active = 1, date_inscription = NOW() WHERE evenement_id = ? AND client_id = ?");
                    $success = $stmt->execute([$evenementId, $clientId]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO participation (evenement_id, client_id, date_inscription, active) VALUES (?, ?, NOW(), 1)");
                    $success = $stmt->execute([$evenementId, $clientId]);
                }
                
                if ($success) {
                    $pdo->commit();
                    error_log("Participation ajoutée avec succès: Evenement ID: $evenementId, Client ID: $clientId");
                    return true;
                } else {
                    $pdo->rollBack();
                    error_log("Échec de l'insertion de la participation");
                    return false;
                }
            } catch (PDOException $e) {
                $pdo->rollBack();
                error_log("Erreur lors de l'insertion: " . $e->getMessage());
                return false;
            }
            
        } catch (PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }
    public static function getListeParticipants($evenementId) {
        $pdo = Database::connect();
        $sql = "SELECT u.id, u.nom, u.email, p.date_inscription 
            FROM Utilisateur u
            JOIN Participation p ON u.id = p.client_id
            WHERE p.evenement_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$evenementId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function supprimerParticipation($evenementId, $clientId) {
        try {
            $pdo = Database::connect();
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("DELETE FROM participation WHERE evenement_id = ? AND client_id = ?");
            $success = $stmt->execute([$evenementId, $clientId]);
            
            if ($success) {
                // Mettre à jour le nombre de participants
                $stmt = $pdo->prepare("UPDATE evenement SET participants = participants - 1 WHERE id = ?");
                $stmt->execute([$evenementId]);
            }
            
            $pdo->commit();
            return $success;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
}    


