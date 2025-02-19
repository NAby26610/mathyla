<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datautilisateur = [];
    try {
        // Récupérer tous les utilisateur triés par ordre décroissant
        $read = ModeleClasse::getallDESC("utilisateur");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet utilisateur à retourner
                $objet = [
                    "id" => isset($data["id"]) ? $data["id"] : null,
                    "nom" => isset($data["nom"]) ? $data["nom"] : null,  
                    "prenom" => isset($data["prenom"]) ? $data["prenom"] : null,  
                    "email" => isset($data["email"]) ? $data["email"] : null,
                    "telephone" => isset($data["telephone"]) ? $data["telephone"] : null,
                    "adresse" => isset($data["adresse"]) ? $data["adresse"] : null,
                    "pays" => isset($data["pays"]) ? $data["pays"] : null,
                    "ville" => isset($data["ville"]) ? $data["ville"] : null,
                    "privilege" => isset($data["privilege"]) ? $data["privilege"] : null,
                    "created_at" => isset($data["created_at"]) ? $data["created_at"] : null,
                    "created_by" => isset($data["created_by"]) ? $data["created_by"] : null
                ];
                array_push($datautilisateur, $objet);
            endforeach;
        else:
            echo json_encode('Aucun utilisateur trouvé');
        endif;

        echo json_encode($datautilisateur, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
