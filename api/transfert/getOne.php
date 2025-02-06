<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataTransfert = [];
    try {
        // Récupérer le transfert spécifique par son ID
        $transfertId = $_GET['id'];
        $transfert = ModeleClasse::getone("transfert", $transfertId);
        $zone = ModeleClasse::getoneByname('id', 'zones', $transfert['id_zone']);
        $devise = ModeleClasse::getoneByname('id', 'devise', $zone['id_devise']);
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
                "montantEnvoyer" => formatNumber2($transfert["montantEnvoyer"]),
                "frais" => formatNumber2($transfert["frais"]),
                "montantRetrait" => formatNumber2($data['montantRetrait']) . ' ' . $devise['libelle'] ?? 0,
                "codeTransfert" => $transfert["codeTransfert"],
                "etatTransfert" => $transfert["etatTransfert"],
                "statut" => $transfert["statut"],
                "commentaire" => $transfert["commentaire"] ?? null,
                "created_at" => $transfert["created_at"],
                "created_by" => $transfert["created_by"] ?? null,
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
