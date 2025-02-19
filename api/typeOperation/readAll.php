<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datatypeOperation = [];
    try {
        // Récupérer tous les typeOperation triés par ordre décroissant
        $read = ModeleClasse::getallDESC("typeOperation");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet typeOperation à retourner
                $objet = [
                    "id" => isset($data["id"]) ? $data["id"] : null,
                    "libelle" => isset($data["libelle"]) ? $data["libelle"] : null,  
                    "nature" => isset($data["nature"]) ? $data["nature"] : null,  
                    "created_at" => isset($data["created_at"]) ? $data["created_at"] : null,
                    "created_by" => isset($data["created_by"]) ? $data["created_by"] : null
                ];
                array_push($datatypeOperation, $objet);
            endforeach;
        else:
            echo json_encode('Aucun typeOperation trouvé');
        endif;

        echo json_encode($datatypeOperation, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
