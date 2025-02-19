<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $dataarticles = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les articles triés par ordre décroissant
        $read = ModeleClasse::getallDESC("article");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'categorie
                $categorie = ModeleClasse::getoneByname('id', 'categorie', $data['id_categorie']);
                
                
                // Construire un objet pour le article avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_categorie" => $categorie["id"],
                    "categorie_libelle" => $categorie["libelle"],  // Changement du nom de la clé pour éviter la duplication
                    "reference" => $data["reference"] ?? null, 
                    "puInitial" => $data ["puInitial"] ?? null,
                    "qteInitiale" => $data ["qteInitiale"] ?? null,
                    "pvInitial" => $data ["pvInitial"] ?? null,
                    "datePeremption" => $data["datePeremption"] ?? null,
                    "descriptions" => $data["descriptions"] ?? null,
                    "designation" => $data["designation"] ?? null,
                    "created_at" => $data["created_at"] ?? null,  // Date de création du article
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de mise à jour du article
                ];

                // Ajouter l'objet construit à la liste des articles
                array_push($dataarticles, $objet);
            }
        } else {
            echo json_encode('Aucun article trouvé');
            exit;
        }

        // Retourner les articles sous forme de JSON
        echo json_encode($dataarticles, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
