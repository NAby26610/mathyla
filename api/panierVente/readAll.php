<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datapanierVentes = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les panierVentes triés par ordre décroissant
        $read = ModeleClasse::getallDESC("panierVente");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'entite
                $article = ModeleClasse::getoneByname('id', 'article', $data['id_article']);
                $initVente = ModeleClasse::getoneByname('id', 'article', $data['id_initVente']);
                
                // Construire un objet pour le panierVente avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_article" => $article["id"],
                    "id_initVente" => $initVente["id"],
                    "statut" => $data["statut"],
                    "created_at" => $data["created_at"] ?? null,  // Date de création du panierVente
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de mise à jour du panierVente
                ];

                // Ajouter l'objet construit à la liste des panierVentes
                array_push($datapanierVentes, $objet);
            }
        } else {
            echo json_encode('Aucun panierVente trouvé');
            exit;
        }

        // Retourner les panierVentes sous forme de JSON
        echo json_encode($datapanierVentes, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
