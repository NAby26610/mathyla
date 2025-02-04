<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataAgence = [];
    try {
        // Récupérer l'agence spécifique par son ID
        $agenceId = $_GET['id'];
        $agence = ModeleClasse::getone("agences", $agenceId);
        
        if ($agence):
            // Construire l'objet agence à retourner
            $objet = [
                "id" => $agence["id"],
                "id_zone" => $agence["id_zone"] ?? null,
                "zone" => $agence["libelle"] ?? null,  // ID de la zone associée
                "libelle" => $agence["libelle"],
                "telephone" => $agence["telephone"],
                "soldeInitial" => $agence["soldeInitial"],
                "soldeMax" => $agence["soldeMax"],
                "seuil" => $agence["seuil"],
                "indicatif" => $agence["indicatif"],
                "adresse" => $agence["adresse"] ?? null,
                "heureOuverture" => $agence["heureOuverture"] ?? null,
                "heureFermeture" => $agence["heureFermeture"] ?? null,
                "descriptions" => $agence["descriptions"] ?? null,
                "created_at" => $agence["created_at"],
                "created_by" => $data["created_by"] ?? null,
                "updated_at" => $agence["updated_at"],
            ];

            // Retourner l'objet agence sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Agence non trouvée');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
