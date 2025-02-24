<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $dataentite = [];
    try {
        $read = ModeleClasse::getallDESC("entite");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet à retourner
                $objet = [
                    "id" => $data["id"],
                    "reference" => $data["reference"],
                    "codeEntite" => $data["codeEntite"],
                    "modify_at" => $data["modify_at"],
                    "created_at" => $data["created_at"]
                ];
                array_push($dataentite, $objet);
            endforeach;
        else:
            echo json_encode('Aucune entite trouvé');
        endif;

        echo json_encode($dataentite, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
