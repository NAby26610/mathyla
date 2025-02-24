<?php
require_once('../../config/database.php');

$reponse = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    extract($_GET);

    $Verification_inExist = ModeleClasse::getoneByNameDesc('initCommande', 'id_fournisseur', $id);
    if ($Verification_inExist):
        if ($Verification_inExist['statut'] == 'en cours'):
            $reponse = [
                'status' => 1,
                'message' => "Continuer la commande deja en cours...",
                'data' => $Verification_inExist['id']
            ];
        else:
            $reponse = [
                'status' => 0,
                'message' => "Veillez placer une nouvelle commande...",
                'data' => $Verification_inExist['id']
            ];
        endif;
    else: // Aucune init commande (1er init commande)
        $reponse = [
            'status' => 0,
            'message' => "Veillez placer une nouvelle commande...",
            'data' => 0
        ];
    endif;
    // Retourner la réponse JSON
    echo json_encode($reponse);
} else {
    // Si aucune donnée n'est reçue
    echo json_encode(['status' => 0, 'message' => "Aucune donnée reçue."]);
}
