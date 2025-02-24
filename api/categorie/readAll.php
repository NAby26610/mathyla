<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datacategories = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les categories triés par ordre décroissant
        $read = ModeleClasse::getallDESC("categorie");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'entite
                $entite = ModeleClasse::getoneByname('id', 'entite', $data['id_entite']);
                
                // Construire un objet pour le categorie avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_entite" => $entite["id"],
                    "libelle" => $data["libelle"],
                    "entite" => $entite["reference"] ?? null, 
                    "codeEntite" => $entite["codeEntite"] ?? null,
                    "created_at" => $data["created_at"] ?? null,  // Date de création du categorie
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de mise à jour du categorie
                ];

                // Ajouter l'objet construit à la liste des categories
                array_push($datacategories, $objet);
            }
        } else {
            echo json_encode('Aucun categorie trouvé');
            exit;
        }

        // Retourner les categories sous forme de JSON
        echo json_encode($datacategories, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
