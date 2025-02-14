<?php
require_once('../../config/database.php');
if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as  $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    extract($_POST);
    $response = [];
    try {
        $ajout = ModeleClasse::add("transactions", $_POST);
        if (!$ajout):
            $response = [
                'status' => 1,
                'message' => 'Operation ajoutée avec succès...',
            ];
        else :
            $response = [
                'status' => 0,
                'message' => 'Erreur pendant l\'operation...',
            ];
        endif;
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnees reçu");
}
