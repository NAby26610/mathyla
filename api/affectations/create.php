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

        // Vérification de l'existence de l'affectation
        // $existingAffectation = ModeleClasse::getOne("affectations", "id_agence = $id_agence AND id_utilisateur = $id_utilisateur");
        $existingAffectation = ModeleClasse::getoneByname2Clause('id_agence',"affectations", $id_agence, 'statut', 'actif');
        $existingUserInAffectation = ModeleClasse::getoneByname2Clause('id_utilisateur',"affectations", $id_utilisateur, 'statut', 'actif');
        // Si l'affectation n'existe pas, créer une nouvelle affectation
        if (!$existingAffectation && !$existingUserInAffectation) {
            // Ajouter une nouvelle affectation
            $ajout = ModeleClasse::add('affectations', $_POST);

            if (!$ajout) {
                $message_ = 'Bonjour, ' . $utilisateurExistant['prenom'] . ' ' . $utilisateurExistant['nom'] . ', Votre compte en tant que gerant a ete creer chez Mathyla-Transfert, connectez-vous a l\'adresse : https://matyla.spa-dev.com) avec les access suivant : ' . $utilisateurExistant['telephone'] . ' | MPD: 1234';
                // $message = "Affectation ajoutée avec succès.";
                // Nimba_SMS($utilisateurExistant['telephone'], $message_);
                $response = [
                    'status' => 1,
                    'message' => 'Agence affecter avec succès...',
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
                'message' => 'Une affectation existe déjà pour cette agence...',
            ];
        }

        // Retourner la réponse avec le message et l'affectation spécifique
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(["error" => "Erreur : " . $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
}
