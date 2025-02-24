<?php
require_once '../../config/database.php';

$postdata = json_decode(file_get_contents("php://input"), true);

if (isset($_POST)) {
    $reponse = [];
    foreach ($_POST as  $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    try {
        $_POST['mot_de_passe'] = md5($_POST['mot_de_passe']);
        $_POST['codePin'] = md5($_POST['codePin']);
        extract($_POST);
        // Verification

        $User = ModeleClasse::getoneByname('id', 'utilisateurs', $id);
        if ($User['mot_de_passe'] == md5($ancienMDP) && $User['codePin'] == md5($ancienCP)):
            unset($_POST['ancienMDP']);
            unset($_POST['ancienCP']);

            if (!ModeleClasse::update('utilisateurs', $_POST, $id)) {
                $reponse = [
                    'status' => 1,
                    'message' => "Mise à jour effectuée avec succès"
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
                'message' => "Utilisateur Inconnus"
            ];
        endif;
        echo json_encode($reponse, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode([
            'status' => 0,
            'message' => "Erreur interne du serveur : " . $th->getMessage()
        ], JSON_PRETTY_PRINT);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 0,
        'message' => "Veuillez fournir des données valides, y compris un ID"
    ], JSON_PRETTY_PRINT);
}
