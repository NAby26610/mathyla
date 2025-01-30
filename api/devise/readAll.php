<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datadevise = [];
    try {
        // Récupérer tous les devise triés par ordre décroissant
        $read = ModeleClasse::getallDESC("devise");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet devise à retourner
                $objet = [
                    "id" => isset($data["id"]) ? $data["id"] : null,
                    "libelle" => isset($data["libelle"]) ? $data["libelle"] : null,  
                    "statut" => isset($data["roles"]) ? $data["roles"] : null,
                    "created_at" => isset($data["created_at"]) ? $data["created_at"] : null,
                    "updated_at" => isset($data["updated_at"]) ? $data["updated_at"] : null  // Vérifier si 'updated_at' existe
                ];
                array_push($datadevise, $objet);
            endforeach;
        else:
            echo json_encode('Aucun utilisateur trouvé');
        endif;

        echo json_encode($datadevise, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
