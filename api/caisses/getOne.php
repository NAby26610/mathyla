<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataCaisse = [];
    try {
        // Récupérer la caisse spécifique par son ID
        $caisseId = $_GET['id'];
        $caisse = ModeleClasse::getone("caisses", $caisseId);
        
        if ($caisse):
            // Construire l'objet caisse à retourner
            $objet = [
                "id" => $caisse["id"],
                "id_transfert" => $caisse["id_transfert"] ?? null,
                "id_retrait" => $caisse["id_retrait"] ?? null,
                "id_agence" => $caisse["id_agence"],
                "id_depense" => $caisse["id_depense"] ?? null,
                "montant" => $caisse["montant"],
                "typeOperation" => $caisse["typeOperation"],
                "statut" => $caisse["statut"],
                "created_at" => $caisse["created_at"],
                "updated_at" => $caisse["updated_at"],
                
            ];

            // Retourner l'objet caisse sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Caisse non trouvée');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
