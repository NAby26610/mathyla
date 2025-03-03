<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    extract($_GET);
    try {
        if ($id_agence != 'null'):
            $affectationUser = ModeleClasse::getoneByname('id_utilisateur', 'affectations', $id_user);
            $agenceUser = ModeleClasse::getoneByname('id', 'agences', $affectationUser['id_agence']);
            $zoneUser = ModeleClasse::getoneByname('id', 'zones', $agenceUser['id_zone']);
            $deviseUser = ModeleClasse::getoneByname('id', 'devise', $zoneUser['id_devise']);
            $libelleDeviseUser = $deviseUser['libelle'];
        else:
            $libelleDeviseUser = 'GNF';
        endif;

        // Date du jour bien recuperer
        $today = _Aujourdhui();
        $response = [];
        // Envoie
        if ($id_agence != 'null')
            $Envoie = ModeleClasse::getallbyName('transfert', 'created_by', $id_user);
        else
            $Envoie = ModeleClasse::getall('transfert');
        foreach ($Envoie as $data):
            $createdAt = dateConvert($data['created_at']);
            if ($createdAt == $today):
                $Objet = [
                    'libelle' => "Envoie d'Argent",
                    'montant' => formatNumber2($data['montant'] + $data['frais']),
                    'montantRetrait' => formatNumber2($data['montantRetrait']),
                    'nomEnvoyeur' => $data['nomEnvoyeur'],
                    'telEnvoyeur' => $data['telEnvoyeur'],
                    'nomDestinataire' => $data['nomDestinataire'],
                    'statut' => $data['statut'],
                ];
                array_push($response, $Objet);
            endif;
        // ----------------------------------------------------------------            
        endforeach;

        if ($id_agence != 'null'):
            // Retrait
            $Retrait = ModeleClasse::getallbyName('transfert', 'modify_by', $id_user);
            foreach ($Retrait as $data):
                $createdAt = dateConvert($data['created_at']);
                if ($createdAt == $today)
                    $Objet = [
                        'libelle' => "Retrait d'Argent",
                        'montant' => formatNumber2($data['montant'] + $data['frais']),
                        'montantRetrait' => formatNumber2($data['montantRetrait']),
                        'nomEnvoyeur' => $data['nomEnvoyeur'],
                        'telEnvoyeur' => $data['telEnvoyeur'],
                        'nomDestinataire' => $data['nomDestinataire'],
                        'statut' => $data['statut'],
                    ];
                array_push($response, $Objet);
            // ----------------------------------------------------------------            
            endforeach;
        endif;

        // Retourner les caisses sous forme de JSON
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
