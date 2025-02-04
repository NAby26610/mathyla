<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataZone = [];
    try {
        // Récupérer la zone spécifique par son ID
        $zoneId = $_GET['id'];
        $zone = ModeleClasse::getone("zones", $zoneId);
        
        if ($zone):
            // Construire l'objet zone à retourner
            $objet = [
                "id" => $zone["id"],
                "id_devise" => $zone["id_devise"] ?? null,  // ID de la devise associée
                "libelle" => $zone["libelle"],
                "created_at" => $zone["created_at"],
                "created_by" => $zone["created_by"],
                "updated_at" => $zone["updated_at"],
                
            ];

            // Retourner l'objet zone sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Zone non trouvée');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
