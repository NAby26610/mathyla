<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $dataagences = [];
    try {
        // Récupérer toutes les agences triées par ordre décroissant
        $read = ModeleClasse::getallDESC("agences");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur la zone associée à l'agence
                $zone = ModeleClasse::getoneByname('id', 'zones', $data['id_zone']);
                
                // Construire l'objet agence avec les relations
                $objet = [
                    "id" => $data["id"],
                    "zone" => $zone["libelle"] ?? null,
                    "libelle" => $data["libelle"] ?? null,
                    "telephone" => $data["telephone"] ?? null,
                    "soldeInitial" => $data["soldeInitial"] ?? 0,
                    "soldeMax" => $data["soldeMax"] ?? 0,
                    "seuil" => $data["seuil"] ?? 0,
                    "indicatif" => $data["indicatif"] ?? '+224',
                    "adresse" => $data["adresse"] ?? null,
                    "heureOuverture" => $data["heureOuverture"] ?? null,
                    "heureFermeture" => $data["heureFermeture"] ?? null,
                    "descriptions" => $data["descriptions"] ?? null,
                    "created_at" => $data["created_at"] ?? null,
                    "updated_at" => $data["updated_at"] ?? null,
                ];

                // Ajouter l'objet construit à la liste des agences
                array_push($dataagences, $objet);
            }
        } else {
            echo json_encode('Aucune agence trouvée');
            exit;
        }

        // Retourner les agences sous forme de JSON
        echo json_encode($dataagences, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
