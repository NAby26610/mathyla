<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $response = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les initCommandes triés par ordre décroissant
        $read = ModeleClasse::getall("initCommande");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'entité
                $entite = ModeleClasse::getoneByname('id', 'entite', $data['id_entite']);
                $fournisseur = ModeleClasse::getoneByname('id', 'fournisseur', $data['id_fournisseur']);

                // Construire un objet pour le initCommande avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_entite" => $entite["id"],
                    "entite" => $entite["reference"],
                    "code_entite" => $entite["codeEntite"],
                    "id_fournisseur" => $fournisseur["id"],
                    "representant" => $fournisseur["representant"],
                    "raison_sociale" => $fournisseur["raison_sociale"],
                    "telephone" => $fournisseur["telephone"],
                    "adresse" => $fournisseur["adresse"],
                    "pays" => $fournisseur["pays"],
                    "statut" => $data["statut"],
                    "created_at" => $data["created_at"] ?? null,  // Date de création du initCommande
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de mise à jour du initCommande
                ];

                // Ajouter l'objet construit à la liste des initCommandes
                array_push($response, $objet);
            }
        } else {
            // Si aucune commande n'est trouvée
            echo json_encode(['message' => 'Aucun initCommande trouvé']);
            exit;
        }

        // Retourner les données sous forme de JSON
        echo json_encode(
            $response,
            JSON_PRETTY_PRINT
        );
    } catch (\Throwable $th) {
        // En cas d'erreur
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    // Si aucune donnée n'est reçue
    echo json_encode(['message' => 'Aucune donnée reçue']);
}
