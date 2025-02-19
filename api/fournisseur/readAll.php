<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datafournisseur = [];
    try {
        $read = ModeleClasse::getallDESC("fournisseur");
        if ($read):
            foreach ($read as $data):
                // Construire l'objet à retourner
                $objet = [
                    "id" => $data["id"],
                    "raison_sociale" => $data["raison_sociale"],
                    "representant" => $data["representant"], // Retirer l'espace ici
                    "pays" => $data["pays"],
                    "adresse" => $data["adresse"],
                    "telephone" => $data["telephone"],
                    "created_at" => $data["created_at"]
                ];
                array_push($datafournisseur, $objet);
            endforeach;
        else:
            echo json_encode('Aucun fournisseur trouvé');
        endif;

        echo json_encode($datafournisseur, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
