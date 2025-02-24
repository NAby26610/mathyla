<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datapaniercommande = [];
    try {
        // Récupérer tous les paniers de commande triés par ordre décroissant
        $read = ModeleClasse::getallDESC("panierCommande");
        
        if ($read):
            foreach ($read as $data):
                // Construire l'objet panierCommande à retourner
                $objet = [
                    "id" => isset($data["id"]) ? $data["id"] : null,
                    "id_initCommande" => isset($data["id_initCommande"]) ? $data["id_initCommande"] : null,
                    "id_article" => isset($data["id_article"]) ? $data["id_article"] : null,
                    "id_article" => isset($data["id_article"]) ? $data["id_article"] : null,
                    "quantite" => isset($data["quantite"]) ? $data["quantite"] : null,
                   "statut" => $data["statut"],
                    "created_at" => isset($data["created_at"]) ? $data["created_at"] : null,
                    "created_by" => isset($data["created_by"]) ? $data["created_by"] : null,
                    "modify_at" => isset($data["modify_at"]) ? $data["modify_at"] : null,
                    "modify_by" => isset($data["modify_by"]) ? $data["modify_by"] : null
                ];

                // Ajouter l'objet panierCommande au tableau final
                array_push($datapaniercommande, $objet);
            endforeach;

            // Vérifier si des paniers de commande ont été trouvés et renvoyer la réponse
            if (!empty($datapaniercommande)) {
                echo json_encode([
                    'status' => 1,             // Statut de la réponse
                    'data' => $datapaniercommande // Données récupérées
                ], JSON_PRETTY_PRINT);
            } else {
                echo json_encode([
                    'status' => 0, 
                    'message' => 'Aucun panier de commande trouvé'
                ], JSON_PRETTY_PRINT);
            }

        else:
            echo json_encode([
                'status' => 0,
                'message' => 'Aucun panier de commande trouvé'
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
