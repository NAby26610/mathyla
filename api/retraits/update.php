<?php
require_once '../../config/database.php';

$postdata = json_decode(file_get_contents("php://input"), true);

if (!empty($_POST) && isset($_POST['id'])) {
    $retraits = $_POST['retraits'];
    unset($_POST['retraits']);
    try {
        extract($_POST);

        if (ModeleClasse::update($retraits, $_POST, $id)) {
            $reponse = [
                'status' => 1,
                'message' => "Mise à jour effectuée avec succès"
            ];
        } else {
            $reponse = [
                'status' => 0,
                'message' => "Une erreur s'est produite lors de la mise à jour"
            ];
        }

        echo json_encode($reponse, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode([
            'status' => 0,
            'message' => "Erreur interne du serveur : " . $th->getMessage()
        ], JSON_PRETTY_PRINT);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 0,
        'message' => "Veuillez fournir des données valides, y compris un ID"
    ], JSON_PRETTY_PRINT);
}
