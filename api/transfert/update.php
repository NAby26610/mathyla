<?php
require_once '../../config/database.php';

// Lire les données JSON envoyées dans le corps de la requête
$postdata = json_decode(file_get_contents("php://input"), true);

if (!empty($_POST) && isset($_POST['id'])) {
    try {
        extract($_POST);

        // Préparer la mise à jour
        if (!ModeleClasse::update('transfert', $_POST, $id)) {
            $reponse = [
                'status' => 1,
                'message' => "Retrait confirmer avec succès"
            ];
        } else {
            $reponse = [
                'status' => 0,
                'message' => "Une erreur s'est produite lors de la mise à jour"
            ];
        }
        // Envoyer une réponse JSON
        echo json_encode($reponse, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        // Gestion des exceptions et erreurs
        http_response_code(500);
        echo json_encode([
            'status' => 0,
            'message' => "Erreur interne du serveur : " . $th->getMessage()
        ], JSON_PRETTY_PRINT);
    }
} else {
    // Réponse si les données sont invalides ou si l'ID est manquant
    http_response_code(400);
    echo json_encode([
        'status' => 0,
        'message' => "Veuillez fournir des données valides, y compris un ID"
    ], JSON_PRETTY_PRINT);
}
