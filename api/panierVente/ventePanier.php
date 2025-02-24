<?php
require_once('../../config/database.php');

if (isset($_GET['id_entite'])) {
    $id_entite = $_GET['id_entite'];

    try {
        // Récupérer les ventes associées à l'entité
        $initVentes = ModeleClasse::getallByNameDesc("initVente", "id_entite", $id_entite);

        // Vérification : Si aucune vente trouvée
        if (empty($initVentes)) {
            echo json_encode(["status" => 0, "message" => "Aucune vente trouvée pour cette entité"], JSON_PRETTY_PRINT);
            exit;
        }

        $ventesData = [];
        foreach ($initVentes as $init) {
            // Récupérer les paniers liés à la vente
            $paniers = ModeleClasse::getallbyName("panierVente", "id_initVente", $init['id']);
            $paniersVente = [];

            if (!empty($paniers)) {
                foreach ($paniers as $panier) {
                    // Vérifier si l'article existe
                    $article = ModeleClasse::getone("article", $panier["id_article"]);
                    $paniersVente[] = [
                        "id_panier"       => $panier['id'],
                        "reference"       => $article['reference'] ?? "Non spécifié",
                        "designation"     => $article['designation'] ?? "Non spécifié",
                        "puInitial"       => $article['puInitial'] ?? 0,
                        "qteInitiale"     => $article['qteInitiale'] ?? 0,
                        "pvInitial"       => $article['pvInitial'] ?? 0,
                        "datePeremption"  => $article['datePeremption'] ?? null,
                        "descriptions"    => $article['descriptions'] ?? "Non spécifié",
                        "statut"          => $panier['statut'] ?? 'en cours',  // Récupérer le statut du panier
                    ];
                }
            }

            // Ajouter les données de la vente avec ses paniers associés
            $ventesData[] = [
                "initVente_id" => $init['id'],
                "statut_initVente"        => $init['statut'],
                "paniers_vente" => $paniersVente,
            ];
        }

        // Retourner la réponse en JSON
        echo json_encode(["status" => 1, "data" => $ventesData], JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(["status" => 0, "message" => $th->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["status" => 0, "message" => "ID entité manquant"], JSON_PRETTY_PRINT);
}
