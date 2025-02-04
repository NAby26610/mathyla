<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataDepense = [];
    try {
        // Récupérer la dépense spécifique par son ID
        $depenseId = $_GET['id'];
        $depense = ModeleClasse::getone("depenses", $depenseId);
        
        if ($depense):
            // Construire l'objet dépense à retourner
            $objet = [
                "id" => $depense["id"],
                "id_agence" => $depense["id_agence"],
                "types" => $depense["types"],
                "montant" => $depense["montant"],
                "motif" => $depense["motif"],
                "statut" => $depense["statut"],
                "created_at" => $depense["created_at"],
                "created_by" => $data["created_by"],
                "updated_at" => $depense["updated_at"],
                
            ];

            // Retourner l'objet dépense sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Dépense non trouvée');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
