<?php 
require_once('../../config/database.php');

if(isset($_GET) && !empty($_GET)) :
    $id = $_GET['id'];
    $table = $_GET['table'];
    try {
        $delete = ModeleClasse::delete($id, $table);
        echo json_encode("Suppression effectuer avec success...?");
    } catch (\Throwable $th) {
        //throw $th;
        echo json_encode($th->getMessage());
    }
else:
    echo json_encode("Veillez envoyer les donnees...");
endif;