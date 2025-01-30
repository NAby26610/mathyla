<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataTransfert = [];
    try {
        // Récupérer le transfert spécifique par son ID
        $transfertId = $_GET['id'];
        $transfert = ModeleClasse::getone("transfert", $transfertId);
        
        if ($transfert):
            // Construire l'objet transfert à retourner
            $objet = [
                "id" => $transfert["id"],
                "id_zone" => $transfert["id_zone"],
                "nomEnvoyeur" => $transfert["nomEnvoyeur"],
                "telEnvoyeur" => $transfert["telEnvoyeur"],
                "nomDestinataire" => $transfert["nomDestinataire"],
                "telDestinataire" => $transfert["telDestinataire"],
                "piece" => $transfert["piece"],
                "montantEnvoyer" => $transfert["montantEnvoyer"],
                "frais" => $transfert["frais"],
                "codeTransfert" => $transfert["codeTransfert"],
                "etatTransfert" => $transfert["etatTransfert"],
                "statut" => $transfert["statut"],
                "commentaire" => $transfert["commentaire"] ?? null,
                "created_at" => $transfert["created_at"],
                "updated_at" => $transfert["updated_at"],
               
            ];

            // Retourner l'objet transfert sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Transfert non trouvé');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
