<?php
require_once('../../config/database.php');
if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as  $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    $Password = md5($_POST['codePin']);
    extract($_POST);

    $reponse = [];
    try {

        // Utilisateur 
        $User = ModeleClasse::getoneByname('id', 'utilisateurs', $created_by);
        if ($User) {
            if ($User['codePin'] == $Password):
                // Transfert
                $Transfert = ModeleClasse::getoneByname('id', 'transfert', $id_transfert);
                if ($Transfert['telDestinataire'] == $telDestinataire) :
                    unset($_POST['codePin']);
                    unset($_POST['telDestinataire']);
                    $ajout = ModeleClasse::add("retraits", $_POST);
                    if (!$ajout) {
                        $reponse = [
                            'status' => 1,
                            'message' => "Retrait effectuée avec succès"
                        ];
                    } else {
                        $reponse = [
                            'status' => 0,
                            'message' => "Une erreur s'est produite lors de la mise à jour"
                        ];
                    }
                else:
                    $reponse = [
                        'status' => 0,
                        'message' => "Numero du Destinataire incorrect !"
                    ];
                endif;
            else:
                $reponse = [
                    'status' => 0,
                    'message' => "Votre Code-Pin est incorrect !"
                ];
            endif;
        } else {
            $reponse = [
                'status' => 0,
                'message' => "Utilisateur compromis...!"
            ];
        }
        echo json_encode($reponse, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnees reçu");
}
