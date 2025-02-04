<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataRetrait = [];
    try {
        // Récupérer le retrait spécifique par son ID
        $retraitId = $_GET['id'];
        $retrait = ModeleClasse::getone("retraits", $retraitId);
        
        if ($retrait):
            // Construire l'objet retrait à retourner
            $objet = [
                "id" => $retrait["id"],
                "id_agence" => $retrait["id_agence"],
                "id_transfert" => $retrait["id_transfert"],
                "statut" => $retrait["statut"],
                "created_at" => $retrait["created_at"],
                "created_by" => $data["created_by"],
                "updated_at" => $retrait["updated_at"],
              
            ];

            // Retourner l'objet retrait sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Retrait non trouvé');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
