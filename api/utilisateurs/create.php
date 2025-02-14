<?php
require_once('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    extract($_POST);

    try {
        // Ajout des données dans la base
        $ajout = ModeleClasse::add("utilisateurs", $_POST);
        if ($ajout) {
            $message = "Enregistrement avec succès";
        } else {
            $message = "Échec de l'enregistrement";
        }
        echo json_encode($message);
    } catch (\Throwable $th) {
        echo json_encode("Erreur : " . $th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
