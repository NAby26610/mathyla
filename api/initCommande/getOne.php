<?php
// Inclusion du fichier de configuration de la base de données
require_once('../../config/database.php');

// Vérification si un identifiant (id) est fourni dans la requête GET
if (isset($_GET['id'])) {
    try {
        $id_fournisseur = $_GET['id']; // Récupération de l'ID du fournisseur depuis l'URL

        // Récupération des informations du fournisseur
        $fournisseur = ModeleClasse::getone("fournisseur", $id_fournisseur);

        if ($fournisseur) {
            // Récupération des commandes liées à ce fournisseur
            $commandes = ModeleClasse::getallbyName("initCommande", "id_fournisseur", $id_fournisseur);

            $articles = []; // Tableau pour stocker les articles liés à ce fournisseur

            if ($commandes) {
                foreach ($commandes as $commande) {
                    // Récupération des paniers de commande liés à cette commande
                    $panierCommandes = ModeleClasse::getallbyName("panierCommande", "id_initCommande", $commande["id"]);

                    if ($panierCommandes) {
                        foreach ($panierCommandes as $panier) {
                            // Récupération des informations de l'article
                            $article = ModeleClasse::getone("article", $panier["id_article"]);

                            if ($article) {
                                // Vérifier si l'article existe déjà dans le tableau des articles
                                $exists = false;
                                foreach ($articles as $existingArticle) {
                                    if ($existingArticle['id'] === $article['id']) {
                                        $exists = true;
                                        break;
                                    }
                                }

                                // Si l'article n'existe pas déjà, l'ajouter au tableau
                                if (!$exists) {
                                    $articles[] = [
                                        "id" => $article["id"],
                                        "reference" => $article["reference"],
                                        "designation" => $article["designation"],
                                        "puInitial" => $article["puInitial"],
                                        "qteInitiale" => $article["qteInitiale"],
                                        "pvInitial" => $article["pvInitial"],
                                        "date_peremption" => $article["datePeremption"],
                                        "created_at" => $article["created_at"],
                                        "updated_at" => $article["modify_at"]
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            

            // Construction de l'objet de réponse JSON
            $objet = [
                "id" => $fournisseur["id"],
                "raison_sociale" => $fournisseur["raison_sociale"],
                "representant" => $fournisseur["representant"],
                "adresse" => $fournisseur["adresse"],
                "telephone" => $fournisseur["telephone"],
                "pays" => $fournisseur["pays"],
                "articles" => $articles
            ];

            // Envoi de la réponse JSON avec un statut de succès
            echo json_encode(["status" => 1, "data" => $objet], JSON_PRETTY_PRINT);
        } else {
            // Aucun fournisseur trouvé pour l'ID donné
            echo json_encode(["status" => 0, "message" => "Fournisseur non trouvé"], JSON_PRETTY_PRINT);
        }
    } catch (\Throwable $th) {
        // Gestion des erreurs en renvoyant un message d'exception
        echo json_encode(["status" => 0, "message" => $th->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    // Si aucun ID n'a été fourni dans la requête GET
    echo json_encode(["status" => 0, "message" => "Aucune donnée reçue"], JSON_PRETTY_PRINT);
}
?>
