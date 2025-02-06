<?php
require_once('../../config/database.php');
$message = "";
if (isset($_POST) && !empty($_POST)) {
    // Sécurisation des données
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }

    // Sécurisation et hachage du mot de passe
    $_POST['mot_de_passe'] = md5("MMS-1234");
    $_POST['codePin'] = md5("MMS-1234");
    extract($_POST);

    $response = [];

    // Vérification si l'email existe déjà
    try {
        $existingEmail = ModeleClasse::getoneByname('email', 'utilisateurs', $email);

        if ($existingEmail) {
            // Si l'email existe déjà, on renvoie un message d'erreur
            $response = [
                'status' => 0,
                'message' => 'Un utilisateur avec cet e-mail existe déjà.',
            ];
        } else {
            // Si l'email n'existe pas, on procède à l'ajout
            $ajout = ModeleClasse::add("utilisateurs", $_POST);
            if (!$ajout) {
                $response = [
                    'status' => 1,
                    'message' => 'Enregistré avec succès.',
                ];
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Échec de l\'enregistrement.',
                ];
            }
        }

        // Retourner la réponse en format JSON
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode("Erreur : " . $th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue.");
}
