<?php
require_once 'Database.php';

class Utilisateur {
    protected int $id;
    protected $nom;
    protected $username;
    protected $prenom;
    protected $adresse;
    protected $CIN;
    protected $email;
    protected $password;

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getCIN() {
        return $this->CIN;
    }

    public function getEmail() {
        return $this->email;
    }

    public function __construct($nom, $username, $prenom, $adresse, $CIN, $email, $password) {
        $this->nom = $nom;
        $this->username = $username;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->CIN = $CIN;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function save() {
        $pdo = Database::connect();
        $sql = "INSERT INTO Utilisateur (nom, username, prenom, adresse, CIN, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->nom, $this->username, $this->prenom, $this->adresse, $this->CIN, $this->email, $this->password]);
    }

    public static function getById($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function login($usernameOrEmail, $password) {
        // Connexion à la base de données
        $pdo = Database::connect();
    
        // Vérification si l'email ou le username existe
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Vérifier d'abord si l'utilisateur est bloqué
            if ($user['blocked'] == 1) {
                return 'blocked'; // Nouveau code de retour pour utilisateur bloqué
            }
    
            if (password_verify($password, $user['password'])) {
                $stmtClient = $pdo->prepare("SELECT * FROM Client WHERE id = ?");
                $stmtClient->execute([$user['id']]);
                $client = $stmtClient->fetch(PDO::FETCH_ASSOC);
                
                // Vérification si le compte client est actif      
                if ($client) {
                    if ($client['actif'] == true) {                
                        // Créer une session pour le client
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION['user_id'] = $client['id'];
                        $_SESSION['adm'] = false;
                        return true;
                    }
                } else {
                    $stmtGest = $pdo->prepare("SELECT * FROM Gestionnaire WHERE id = ?");
                    $stmtGest->execute([$user['id']]);
                    $Gest = $stmtGest->fetch(PDO::FETCH_ASSOC);
                    if ($Gest) {
                        session_start();
                        $_SESSION['user_id'] = $Gest['id'];
                        $_SESSION['adm'] = true;
                        return true; 
                    } 
                }
            } else {
                return false; // Mauvais mot de passe
            }
        } else {
            return false; // Utilisateur non trouvé
        }
        return false;
    }
    public static function updateUtilisateur(int $id, array $data): bool {
        $pdo = Database::connect();
        $allowedFields = ['nom', 'username', 'prenom', 'adresse', 'CIN', 'email', 'password'];
        $updates = [];
        $values = [];
    
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                if ($field === 'password') {
                    $value = password_hash($value, PASSWORD_DEFAULT);
                }
                $updates[] = "$field = ?";
                $values[] = $value;
            }
        }
    
        if (empty($updates)) {
            return false;
        }
    
        $values[] = $id;
        $sql = "UPDATE Utilisateur SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public static function disconnect(){
        session_start();
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session

        // Rediriger l'utilisateur vers la page de connexion ou d'accueil
        header('Location: ../index.php');
        exit;
    }
}
?>