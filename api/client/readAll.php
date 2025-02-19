<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $dataclient = [];
    try {
        $read = ModeleClasse::getallDESC("client");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet à retourner
                $objet = [
                    "id" => $data["id"],
                    "libelle" => $data["libelle"],
                    "adresse" => $data["adresse"],
                    "ville" => $data["ville"],
                    "pays" => $data["pays"],
                    "telephone" => $data["telephone"],
                    "created_at" => $data["created_at"]
                ];
                array_push($dataclient, $objet);
            endforeach;
        else:
            echo json_encode('Aucun client trouvé');
        endif;

        echo json_encode($dataclient, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
