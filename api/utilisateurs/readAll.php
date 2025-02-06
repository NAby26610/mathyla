<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datautilisateurs = [];
    try {
        // Récupérer tous les utilisateurs triés par ordre décroissant
        $read = ModeleClasse::getallDESC("utilisateurs");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet utilisateurs à retourner
                $objet = [
                    "id" => isset($data["id"]) ? $data["id"] : null,
                    "nom" => isset($data["nom"]) ? $data["nom"] : null,
                    "prenom" => isset($data["prenom"]) ? $data["prenom"] : null,
                    "email" => isset($data["email"]) ? $data["email"] : null,
                    "telephone" => isset($data["telephone"]) ? $data["telephone"] : null,
                    "adresse" => isset($data["adresse"]) ? $data["adresse"] : null,
                    "roles" => isset($data["roles"]) ? $data["roles"] : null,
                    "created_at" => isset($data["created_at"]) ? $data["created_at"] : null,
                    "created_by" => isset($data["created_by"]) ? $data["created_by"] : null
                ];
                if ($data['roles'] != 'admin')
                    array_push($datautilisateurs, $objet);
            endforeach;
        else:
            echo json_encode('Aucun utilisateur trouvé');
        endif;

        echo json_encode($datautilisateurs, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
