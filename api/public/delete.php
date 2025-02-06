<?php
require_once('../../config/database.php');

if (isset($_GET) && !empty($_GET)) :
    $id = $_GET['id'];
    $table = $_GET['table'];
    $response = [];
    try {
        if (!$delete = ModeleClasse::delete($id, $table))
            $response = [
                'status' => 1,
                'message' => 'Suppression effectuée avec succès...',
            ];
        else
            $response = [
                'status' => 0,
                'message' => 'Suppression impossible...',
            ];
        echo json_encode($response, true);
    } catch (\Throwable $th) {
        //throw $th;
        echo json_encode($th->getMessage());
    }
else:
    echo json_encode("Veillez envoyer les donnees...");
endif;
