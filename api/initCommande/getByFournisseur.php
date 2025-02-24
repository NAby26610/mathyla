<?php
require_once('../../config/database.php'); // Inclusion du fichier de configuration de la base de données

// Vérification si un ID de fournisseur est bien reçu dans la requête GET
if (isset($_GET['id'])) {
    $id_fournisseur = $_GET['id']; // Récupération de l'ID du fournisseur depuis l'URL

    try {
        // Récupérer la dernière commande d'achat (initCommande) du fournisseur, triée par ordre décroissant
        $initCommande = ModeleClasse::getoneByNameDesc("initCommande", "id_fournisseur", $id_fournisseur);

        // Vérifier si une commande d'achat a été trouvée pour ce fournisseur
        if ($initCommande) {
            // Récupérer les informations du fournisseur
            $fournisseur = ModeleClasse::getone('fournisseur', $id_fournisseur);

            // Récupérer tous les paniers d'achat liés à cet initCommande
            $panierCommandes = ModeleClasse::getallbyName("panierCommande", "id_initCommande", $initCommande['id']);

            // Liste des articles commandés
            $articles = [];
            foreach ($panierCommandes as $panier) {
                $article = ModeleClasse::getone('article', $panier['id_article']);
                if ($article) {
                    $articles[] = [
                        "id" => $article["id"],
                        "reference" => $article["reference"],
                        "designation" => $article["designation"] ?? "Non spécifiée",
                        "datePeremption" => $article["datePeremption"] ?? "Non spécifiée",
                        "prix" => $article["puInitial"] ?? "Non spécifié",
                        "quantite" => $panier["quantite"] ?? "Non spécifié",
                        "prix_vente_initial" => $panier["pvIntial"] ?? "Non spécifié"
                    ];
                }
            }

            // Construction de l'objet de réponse
            $result = [
                "initCommande_id" => $initCommande["id"], // ID de l'achat initial
                "statut" => $initCommande["statut"] ?? "Non spécifié", // Statut de l'achat
                "fournisseur" => [
                    "id" => $fournisseur["id"] ?? null,
                    "raison_sociale" => $fournisseur["raison_sociale"] ?? "Non spécifiée",
                    "representant" => $fournisseur["representant"] ?? "Non spécifié",
                    "adresse" => $fournisseur["adresse"] ?? "Non spécifiée",
                    "contact" => $fournisseur["telephone"] ?? "Non spécifié",
                    "pays" => $fournisseur["pays"] ?? "Non spécifié"
                ],
                "articles" => $articles, // Liste des articles commandés
            ];

            // Retourner la réponse en JSON avec un statut de succès
            echo json_encode(["status" => 1, "data" => $result], JSON_PRETTY_PRINT);
        } else {
            // Aucun initCommande trouvé pour ce fournisseur
            echo json_encode(["status" => 0, "message" => "Aucune commande trouvée pour ce fournisseur"], JSON_PRETTY_PRINT);
        }
    } catch (\Throwable $th) {
        // Gestion des erreurs et affichage du message d'exception
        echo json_encode(["status" => 0, "message" => $th->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    // Si aucun ID de fournisseur n'est fourni dans la requête GET
    echo json_encode(["status" => 0, "message" => "Aucun identifiant de fournisseur fourni"], JSON_PRETTY_PRINT);
}
