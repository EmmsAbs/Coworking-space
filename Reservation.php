<?php
require_once '../classe/Database.php';
require_once '../classe/Salle.php';
require_once '../classe/Client.php';

class Reservation {
    private $id;
    private Client $client;
    private Salle $salle; // Relation avec Type (1..1)
    private DateTime $dateDebut;
    private DateTime $dateFin;
    

    public function __construct(int $id, Client $client, Salle $salle, DateTime $dateDebut, DateTime $dateFin) {
        $this->id = $id;
        $this->client = $client;
        $this->salle = $salle;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }


    public function save() {
        $pdo = Database::connect();
        // Insérer la réservation
        $stmt = $pdo->prepare("INSERT INTO reservation (salle_id, dateDebut, dateFin, client_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $this->salle->getId(),
            $this->dateDebut->format('Y-m-d H:i:s'),
            $this->dateFin->format('Y-m-d H:i:s'),
            $this->client->getId()
        ]);
    }

    public static function getAllReservations(){

    }

    public function getReservationClientById(){
        $pdo = Database::connect();

    }

    public static function VerifierDisponibilite(DateTime $date_debut, DateTime $date_fin, int $salle_id){
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM reservation WHERE salle_id = :salle_id AND ((dateDebut < :dateFin) AND (dateFin > :dateDebut))");
        $stmt->execute([
            'salle_id' => $salle_id,
            'dateDebut' => $date_debut->format('Y-m-d H:i:s'),
            'dateFin' => $date_fin->format('Y-m-d H:i:s')
        ]);
        return $stmt->fetchAll();
    }
} 