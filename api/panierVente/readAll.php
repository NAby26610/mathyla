<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datapaniervente = [];
    try {
        // Récupérer tous les paniers de vente triés par ordre décroissant
        $read = ModeleClasse::getallDESC("panierVente");

        if ($read):
            foreach ($read as $data):
                // Construire l'objet panierVente à retourner
                $objet = [
                    "id" => isset($data["id"]) ? $data["id"] : null,
                    "id_initVente" => isset($data["id_initVente"]) ? $data["id_initVente"] : null,
                    "id_article" => isset($data["id_article"]) ? $data["id_article"] : null,
                    "statut" => isset($data["statut"]) ? $data["statut"] : null,
                    "created_at" => isset($data["created_at"]) ? $data["created_at"] : null,
                    "created_by" => isset($data["created_by"]) ? $data["created_by"] : null,
                    "modify_at" => isset($data["modify_at"]) ? $data["modify_at"] : null,
                    "modify_by" => isset($data["modify_by"]) ? $data["modify_by"] : null
                ];

                // Ajouter l'objet panierVente au tableau final
                array_push($datapaniervente, $objet);
            endforeach;

            // Vérifier si des paniers de vente ont été trouvés et renvoyer la réponse
            if (!empty($datapaniervente)) {
                echo json_encode([
                    'status' => 1,             // Statut de la réponse
                    'data' => $datapaniervente // Données récupérées
                ], JSON_PRETTY_PRINT);
            } else {
                echo json_encode([
                    'status' => 0, 
                    'message' => 'Aucun panier de vente trouvé'
                ], JSON_PRETTY_PRINT);
            }

        else:
            echo json_encode([
                'status' => 0,
                'message' => 'Aucun panier de vente trouvé'
            ], JSON_PRETTY_PRINT);
        endif;

    } catch (\Throwable $th) {
        // Capturer toutes les exceptions et renvoyer le message d'erreur
        echo json_encode([
            'status' => 0,
            'message' => $th->getMessage()
        ], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode([
        'status' => 0,
        'message' => 'Aucune donnée reçue'
    ], JSON_PRETTY_PRINT);
}
?>
