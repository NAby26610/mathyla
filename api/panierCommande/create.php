<?php
require_once('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    try {
        // Creation d'une panierCommande
        $ajout_partenaire = ModeleClasse::add("paniercommande", $_POST);
        if ($ajout_partenaire) {
            $reponse = [
                'status' => 1,
                'message' => "Article ajouter avec success.."
            ];
        } else {
            $reponse = [
                'status' => 0,
                'message' => "Erreur lors de l'ajout..."
            ];
        }
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
