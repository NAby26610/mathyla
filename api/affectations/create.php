<?php
require_once('../../config/database.php');

header('Content-Type: application/json');
$message = "";

if (isset($_GET) && !empty($_GET)) {
    // Sécuriser les données reçues via GET
    foreach ($_GET as $key => $value) {
        $_GET[$key] = str_secure($value);
    }

    extract($_GET);

    try {
        // Convertir les id en entiers pour éviter les problèmes de type
        $id_agence = intval($id_agence);
        $id_utilisateur = intval($id_utilisateur);

        // Vérifier si id_agence et id_utilisateur sont valides
        if (empty($id_agence) || empty($id_utilisateur)) {
            echo json_encode(["error" => "Les paramètres 'id_agence' et 'id_utilisateur' sont requis."]);
            exit;
        }

        // Vérification si l'agence existe déjà dans la base de données
        $agenceExistante = ModeleClasse::getOne("agences", "id = $id_agence");

        if (!$agenceExistante) {
            echo json_encode(["error" => "L'agence avec l'id $id_agence n'existe pas."]);
            exit;
        }

        // Vérification si l'utilisateur existe déjà dans la base de données
        $utilisateurExistant = ModeleClasse::getOne("utilisateurs", "id = $id_utilisateur");

        if (!$utilisateurExistant) {
            echo json_encode(["error" => "L'utilisateur avec l'id $id_utilisateur n'existe pas."]);
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

            if ($ajout) {
                $message = "Affectation ajoutée avec succès.";
            } else {
                $message = "Échec de l'ajout de l'affectation.";
            }
        } else {
            $message = "Cette affectation existe déjà.";
        }

        // Récupérer l'affectation spécifique correspondant à l'id_agence et id_utilisateur
        $affectation = ModeleClasse::getOne("affectations", "id_agence = $id_agence AND id_utilisateur = $id_utilisateur");

        // Vérifier si l'affectation a été trouvée
        if (!$affectation) {
            echo json_encode([ "error" => "Aucune affectation trouvée pour cet id_agence et id_utilisateur." ]);
            exit;
        }

        // Nettoyer les données en supprimant les clés indésirables (1 à 7)
        foreach ($affectation as $key => $value) {
            if (is_numeric($key)) {
                unset($affectation[$key]);
            }
        }

        // Retourner la réponse avec le message et l'affectation spécifique
        echo json_encode([ "message" => $message, "affectation" => $affectation ]);
    } catch (\Throwable $th) {
        echo json_encode(["error" => "Erreur : " . $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
}
