<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datacaisse = [];
    try {
        $read = ModeleClasse::getallDESC("caisse");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet à retourner
                $objet = [
                    "id" => $data["id"],
                    "id_typeOperation" => $data["id_typeOperation"],
                    "montant" => $data["montant"],
                    "modeRegler" => $data["modeRegler"],
                    "statut" => $data["statut"],
                    "created_by" => $data["created_by"],
                    "created_at" => $data["created_at"]
                ];
                array_push($datacaisse, $objet);
            endforeach;
        else:
            echo json_encode('Aucun caisse trouvé');
        endif;

        echo json_encode($datacaisse, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
