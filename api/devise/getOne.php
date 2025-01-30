<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $datadevise = [];
    try {
        // Récupérer la devise spécifique par son ID
        $deviseId = $_GET['id'];
        $devise = ModeleClasse::getone("devise", $deviseId);
        
        if ($devise):
            // Construire l'objet devise à retourner
            $objet = [
                "id" => $devise["id"],
                "libelle" => $devise["libelle"],
                "created_at" => $devise["created_at"],
                "updated_at" => $devise["updated_at"],
                "statut" => $devise["statut"]
                // Vous pouvez ajouter ou supprimer des champs en fonction de ce que vous souhaitez exposer
            ];

            // Retourner l'objet devise sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Devise non trouvée');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
