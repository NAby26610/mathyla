<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialisation de la réponse
    $response = [];
    // Vérifier si les paramètres requis sont présents
    if (!isset($_POST['id_user'], $_POST['startDate'], $_POST['endDate'])) {
        echo json_encode(["error" => "Paramètres manquants"]);
        exit;
    }
    extract($_POST);
    try {
        // Récupération de l'agence de l'utilisateur
        $Affectation = ModeleClasse::getoneByNameDesc('affectations', 'id_utilisateur', $id_user);
        $AgenceUser = ModeleClasse::getoneByNameDesc('agences', 'id', $Affectation['id_agence']);
        // Récupération des F.E
        $FondEntrant = ModeleClasse::getallbyName('transfert_fond', 'id_agenceSource', $AgenceUser['id']);
        foreach ($FondEntrant as $data) {
            $createdAt = dateConvert($data['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt >= $startDate && $createdAt <= $datandDate) {
                $OBJET = [
                    'id' => $data['id'],
                    'created_at' => $data['created_at'],
                    'statut' => $data['statut'],
                ];
                array_push($response, $OBJET);
            }
        }
        // Récupération des F.S
        $FondSortant = ModeleClasse::getallbyName('transfert_fond', 'id_agenceDestination', $id_agence);
        foreach ($FondSortant as $data) {
            $createdAt = dateConvert($data['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt >= $startDate && $createdAt <= $datandDate) {
                $OBJET = [
                    'id' => $data['id'],
                    'created_at' => $data['created_at'],
                    'statut' => $data['statut'],
                ];
                array_push($response, $OBJET);
            }
        }
        

        // Retour Apis
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
