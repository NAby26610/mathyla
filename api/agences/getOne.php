<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    try {
        // Récupérer l'agence spécifique par son ID
        $agenceId = $_GET['id'];
        $agence = ModeleClasse::getone("agences", $agenceId);
        
        if ($agence):
            // Récupérer les informations de la zone associée à l'agence
            $zone = ModeleClasse::getoneByname("id", "zones", $agence["id_zone"]);
            
            // Récupérer les informations du créateur de l'agence
            $utilisateur = ModeleClasse::getoneByname("id", "utilisateurs", $agence["created_by"]);
            
            // Concaténer nom et prénom
            $createdBy = null;
            if (!empty($utilisateur["nom"]) || !empty($utilisateur["prenom"])) {
                $createdBy = trim(($utilisateur["nom"] ?? '') . ' ' . ($utilisateur["prenom"] ?? ''));
            }

            // Construire l'objet agence à retourner
            $objet = [
                "id" => $agence["id"],
                "id_zone" => $agence["id_zone"] ?? null,
                "zone" => $zone["libelle"] ?? null,  // Nom de la zone associée
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
                "created_by" => $createdBy, // Nom et prénom concaténés
                "updated_at" => $agence["updated_at"],
            ];

            // Retourner l'objet agence sous forme de JSON
            echo json_encode($objet, JSON_PRETTY_PRINT);
        else:
            echo json_encode(["message" => "Agence non trouvée"]);
        endif;

    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
} else {
    echo json_encode(["message" => "Aucune donnée reçue"]);
}
?>
