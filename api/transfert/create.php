<?php
require_once('../../config/database.php');
if (isset($_POST) && !empty($_POST)) {
    $response = [];

    foreach ($_POST as  $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    if ($_POST['deductFrais'] == true) {
        $_POST['deductFrais'] = 1;
        $_POST['montant'] -= $_POST['frais'];
    } else {
        $_POST['deductFrais'] = 0;
    }
    // NET A RECEVOIR
    if (!empty($_POST['netARecevoir']))
        $_POST['montantRetrait'] = floatval($_POST['netARecevoir']);
    unset($_POST['netARecevoir']);
    extract($_POST);
    try {
        $ajout = ModeleClasse::add("transfert", $_POST);
        if (!$ajout):
            $response = [
                'status' => 1,
                'message' => 'Transfert ajoutée avec succès...',
            ];
        else :
            $response = [
                'status' => 0,
                'message' => 'Échec lors du transfert.',
            ];
        endif;
        echo json_encode($response);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnees reçu");
}
