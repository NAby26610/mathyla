<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    extract($_GET);
    $today = _Aujourdhui();
    try {
        // Récupérer le transfert spécifique par son ID (DEPOT)
        $transfert = ModeleClasse::getallbyName("transfert", 'created_by', $id);
        $totalEnvoie = 0;
        foreach ($transfert as $data):
            if (dateConvert($data['created_at']) == $today)
                $totalEnvoie += floatval($data["montant"]);
        endforeach;

        // Récupérer le transfert spécifique par son ID (RETRAIT)
        $Retrait = ModeleClasse::getallbyName("transfert", 'modify_by', $id);
        $totalRetrait = 0;
        foreach ($Retrait as $data):
            if (dateConvert($data['created_at']) == $today)
                $totalRetrait += floatval($data["montantRetrait"]);
        endforeach;

        // FOND RECU
        $TF_1 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceDestination', $id);
        $totalRecu = 0;
        foreach ($TF_1 as $data):
            if (dateConvert($data['created_at']) == $today && $data['statut'] == 'valider')
                $totalRecu += floatval($data["montant"]);
        endforeach;

        // FOND ENVOYER
        $TF_2 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceSource', $id);
        $totalEnvoyer = 0;
        foreach ($TF_2 as $data):
            if (dateConvert($data['created_at']) == $today && $data['statut'] == 'valider')
                $totalEnvoyer += floatval($data["montant"]);
        endforeach;

        $Response = [
            'envoie_du_jour' => formatNumber2($totalEnvoie),
            'retrait_du_jour' => formatNumber2($totalRetrait),
            'fondEntrant' => formatNumber2($totalRecu),
            'fondSortant' => formatNumber2($totalEnvoyer),
        ];


        echo json_encode($Response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
