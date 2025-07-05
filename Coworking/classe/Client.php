<?php
require_once 'Utilisateur.php';

class Client extends Utilisateur {
    protected int $id;
    private bool $actif;

    public function __construct($nom, $username, $prenom, $adresse, $CIN, $email, $password, $actif) {
        parent::__construct($nom, $username, $prenom, $adresse, $CIN, $email, $password);
        $this->actif = $actif;
    }

    public function save() {
        parent::save(); // Insère d'abord dans Utilisateur
        $pdo = Database::connect();
        $lastId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO Client (id, actif) VALUES (?, ?)");
        return $stmt->execute([$lastId, $this->actif]);
    }

    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public static function getClientByID($id) : ?Client{
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT u.*, c.actif FROM utilisateur u JOIN client c ON u.id = c.id WHERE c.id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($data) {
            $client = new Client(
                $data['nom'], $data['username'], $data['prenom'], $data['adresse'], $data['CIN'], $data['email'], 
                $data['password'], $data['actif']
            );
            $client -> setId(intval($data['id']));
            return $client;
        }
    
        return null; // Retourne null si aucun client n'est trouvé
    }
        // Récupérer tous les clients
        public static function getAllClients(): array {
            $pdo = Database::connect();
            $stmt = $pdo->query("
            SELECT u.* FROM utilisateur u 
            JOIN client c ON  c.id= u.id
        ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Mettre à jour les informations d'un client
        public static function updateClient(int $id, array $data): bool {
            // D'abord mettre à jour la table Utilisateur
            $utilisateurUpdate = parent::updateUtilisateur($id, $data);
            
            // Si des champs spécifiques au client doivent être mis à jour
            if (isset($data['actif'])) {
                $pdo = Database::connect();
                $stmt = $pdo->prepare("UPDATE Client SET actif = ? WHERE id = ?");
                return $stmt->execute([$data['actif'], $id]) && $utilisateurUpdate;
            }
            
            return $utilisateurUpdate;
        }
    
        // Bloquer un client
        public static function blockClient(int $id): bool {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("UPDATE utilisateur SET blocked = 1 WHERE id = ?");
            return $stmt->execute([$id]);
        }
    
        // Débloquer un client
        public static function unblockClient(int $id): bool {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("UPDATE utilisateur SET blocked = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }

?>