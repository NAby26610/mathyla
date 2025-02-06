<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datazones = [];
    try {
        // Récupérer toutes les zones triées par ordre décroissant
        $read = ModeleClasse::getallDESC("zones");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur la devise associée à la zone
                $devise = ModeleClasse::getoneByname('id', 'devise', $data['id_devise']);
                
                // Construire l'objet zones avec les relations
                $objet = [
                    "id" => $data["id"],
                    "devise" => $devise["libelle"] ?? null,  // Récupérer l'ID de la devise
                    "libelle" => $data["libelle"] ?? null,
                    "created_at" => $data["created_at"] ?? null,
                    "created_by" => $data["created_by"] ?? null,                
                ];
                array_push($datazones, $objet);
            }
        } else {
            echo json_encode('Aucune zone trouvée');
            exit;
        }

        echo json_encode($datazones, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
