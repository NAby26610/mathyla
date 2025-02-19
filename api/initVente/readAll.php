<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datainitVentes = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les initVentes triés par ordre décroissant
        $read = ModeleClasse::getallDESC("initVente");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'entite
                $entite = ModeleClasse::getoneByname('id', 'entite', $data['id_entite']);
                $client = ModeleClasse::getoneByname('id', 'client', $data['id_client']);
                
                // Construire un objet pour le iNITvente avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_entite" => $entite["id"],
                    "id_client" => $client["id"],
                    "statut" => $data["statut"] ?? null,
                    "created_at" => $data["created_at"] ?? null,  
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  
                ];

                
                array_push($datainitVentes, $objet);
            }
        } else {
            echo json_encode('Aucun initVente trouvé');
            exit;
        }

        // Retourner les initVentes sous forme de JSON
        echo json_encode($datainitVentes, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
