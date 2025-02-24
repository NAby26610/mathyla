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
    // CODE DU TRANSFERT
    $_POST['codeTransfert'] = "MMS".date('dm').rand(1000, 9999);
    extract($_POST);
    try {
        $ajout = ModeleClasse::add("transfert", $_POST);
        if (!$ajout):
            Nimba_SMS($telEnvoyeur, "BIENVENUE CHEZ MATHYLA - MULTI_SERVICE, Votre code de transfert est le : " . $_POST['codeTransfert']);
            $response = [
                'status' => 1,
                'message' => 'Transfert envoyer avec success...',
            ];
        else :
            $response = [
                'status' => 0,
                'message' => 'Echec lors du transfert.',
            ];
        endif;
        echo json_encode($response);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnees reçu");
}
