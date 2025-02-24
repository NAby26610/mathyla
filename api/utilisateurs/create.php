<?php
require_once('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    $_POST['mot_de_passe'] = "81dc9bdb52d04dc20036dbd8313ed055";
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    extract($_POST);

    try {
        // Ajout des données dans la base
        $ajout = ModeleClasse::add("utilisateurs", $_POST);
        if ($ajout) {            
            $response = [
                'status' => 1,
                'message' => 'Utilisateur creer avec success...',
            ];
        } else {
            $response = [
                'status' => 1,
                'message' => 'Erreur, veillez bien verifier les champs...',
            ];
        }
        echo json_encode($message);
    } catch (\Throwable $th) {
        echo json_encode("Erreur : " . $th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
