<?php
require_once('../../config/database.php');
if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as  $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    extract($_POST);

    $reponse = [];
    try {
        if ($id_agenceSource == $id_agenceDestination):
            $reponse = [
                'status' => 0,
                'message' => "Impossible d'envoyer le transfert au meme poste..."
            ];
        else:
            $ajout = ModeleClasse::add("transfert_fond", $_POST);
            if (!$ajout) {
                $reponse = [
                    'status' => 1,
                    'message' => "Transfert de fond effectuer avec success"
                ];
            } else {
                $reponse = [
                    'status' => 0,
                    'message' => "Une erreur s'est produite lors du TF!"
                ];
            }
        endif;
        echo json_encode($reponse, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnees reçu");
}
