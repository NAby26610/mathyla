<?php
require_once('../../config/database.php');

header('Content-Type: application/json');

if (isset($_POST) && !empty($_POST)) {
    // Sécuriser les données reçues via GET
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }

    $response = [];
    extract($_POST);
    try {
        // Convertir les id en entiers pour éviter les problèmes de type
        $id_agence = intval($id_agence);
        $id_utilisateur = intval($id_utilisateur);

        // Vérifier si id_agence et id_utilisateur sont valides
        if (empty($id_agence) || empty($id_utilisateur)) {
            // echo json_encode(["error" => "Les paramètres 'id_agence' et 'id_utilisateur' sont requis."]);
            // Si l'email existe déjà, on renvoie un message d'erreur
            $response = [
                'status' => 0,
                'message' => 'Les paramètres agence et utilisateur sont requis..',
            ];
            exit;
        }

        // Vérification si l'agence existe déjà dans la base de données
        $agenceExistante = ModeleClasse::getoneByname('id', 'agences', $id_agence);

        if (!$agenceExistante) {
            // echo json_encode(["error" => "L'agence avec l'id $id_agence n'existe pas."]);
            $response = [
                'status' => 0,
                'message' => 'Cette agence est manquante',
            ];
            exit;
        }

        // Vérification si l'utilisateur existe déjà dans la base de données
        $utilisateurExistant = ModeleClasse::getoneByname('id', 'utilisateurs', $id_utilisateur);

        if (!$utilisateurExistant) {
            // echo json_encode(["error" => "L'utilisateur avec l'id $id_utilisateur n'existe pas."]);
            $response = [
                'status' => 0,
                'message' => 'Utilisateur manquant',
            ];
            exit;
        }

        // Vérification de l'existence de l'affectation
        $existingAffectation = ModeleClasse::getOne("affectations", "id_agence = $id_agence AND id_utilisateur = $id_utilisateur");

        // Si l'affectation n'existe pas, créer une nouvelle affectation
        if (!$existingAffectation) {
            // Ajouter une nouvelle affectation
            $ajout = ModeleClasse::add("affectations", [
                'id_agence' => $id_agence,
                'id_utilisateur' => $id_utilisateur,
                'statut' => isset($statut) ? $statut : 'actif',  // Si statut n'est pas défini, par défaut 'actif'
            ]);

            if (!$ajout) {
                // $message = "Affectation ajoutée avec succès.";
                $response = [
                    'status' => 1,
                    'message' => 'Affectation ajoutée avec succès...',
                ];
            } else {
                // $message = "Échec de l'ajout de l'affectation.";
                $response = [
                    'status' => 0,
                    'message' => 'Échec de lors de l\'affectation.',
                ];
            }
        } else {
            // $message = "Cette affectation existe déjà.";
            $response = [
                'status' => 0,
                'message' => 'Cette affectation existe déjà',
            ];
        }

        // Récupérer l'affectation spécifique correspondant à l'id_agence et id_utilisateur
        $affectation = ModeleClasse::getOne("affectations", "id_agence = $id_agence AND id_utilisateur = $id_utilisateur");

        // Vérifier si l'affectation a été trouvée
        if (!$affectation) {
            // echo json_encode([ "error" => "Aucune affectation trouvée pour cet id_agence et id_utilisateur." ]);
            $response = [
                'status' => 0,
                'Aucune affectation trouvée pour cette agence et cet utilisateur.',
            ];
            exit;
        }

        // Nettoyer les données en supprimant les clés indésirables (1 à 7)
        foreach ($affectation as $key => $value) {
            if (is_numeric($key)) {
                unset($affectation[$key]);
            }
        }

        // Retourner la réponse avec le message et l'affectation spécifique
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(["error" => "Erreur : " . $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
}
