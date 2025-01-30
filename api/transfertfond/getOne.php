<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataTransfert = [];
    try {
        // Récupérer le transfert de fonds spécifique par son ID
        $transfertId = $_GET['id'];
        $transfert = ModeleClasse::getone("transfert_fond", $transfertId);
        
        if ($transfert):
            // Construire l'objet transfert à retourner
            $objet = [
                "id" => $transfert["id"],
                "id_agenceSource" => $transfert["id_agenceSource"],
                "id_agenceDestinataire" => $transfert["id_agenceDestinataire"],
                "montant" => $transfert["montant"],
                "id_devise" => $transfert["id_devise"] ?? null,
                "statut" => $transfert["statut"],
                "commentaire" => $transfert["commentaire"] ?? null,
                "created_at" => $transfert["created_at"],
                "updated_at" => $transfert["updated_at"],
                
            ];

            // Retourner l'objet transfert sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Transfert de fonds non trouvé');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
