<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    try {
        // Récupérer le retrait spécifique par son ID
        $retraitId = $_GET['id'];
        $retrait = ModeleClasse::getone("retraits", $retraitId);
        
        if ($retrait):
            // Récupérer les informations du créateur du retrait
            $utilisateur = ModeleClasse::getoneByname("id", "utilisateurs", $retrait["created_by"]);

            // Concaténer nom et prénom
            $createdBy = null;
            if (!empty($utilisateur["nom"]) || !empty($utilisateur["prenom"])) {
                $createdBy = trim(($utilisateur["nom"] ?? '') . ' ' . ($utilisateur["prenom"] ?? ''));
            }

            // Construire l'objet retrait à retourner
            $objet = [
                "id" => $retrait["id"],
                "id_agence" => $retrait["id_agence"],
                "id_transfert" => $retrait["id_transfert"],
                "statut" => $retrait["statut"],
                "created_at" => $retrait["created_at"],
                "created_by" => $createdBy,
                "updated_at" => $retrait["updated_at"],
            ];

            // Retourner l'objet retrait sous forme de JSON
            echo json_encode($objet, JSON_PRETTY_PRINT);
        else:
            echo json_encode(["message" => "Retrait non trouvé"]);
        endif;

    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
} else {
    echo json_encode(["message" => "Aucune donnée reçue"]);
}
?>
