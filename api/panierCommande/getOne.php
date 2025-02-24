<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    extract($_GET);

    // Declaration
    $reponse = [];
    $data = [];

    try {
        $init = ModeleClasse::getoneByname('id', 'initcommande', $id);
        if ($init) {
            // Panier
            $Panier = ModeleClasse::getallbyName('paniercommande', 'id_initCommande', $init['id']);
            foreach ($Panier as $data): 
                // Article
                $ART = ModeleClasse::getoneByname('id', 'article', $data['id_article']);
                $Objet = [
                    'id' => $data['id'],
                    'quantite' => $data['quantite'],
                    // Article
                    'reference' => $ART['reference'],
                    'designation' => $ART['designation'],
                ];
                array_push($data, $Objet);
            endforeach;
            $reponse = [
                'status' => 1,
                'message' => "Liste retrouver avec success..",
                'data' => $data
            ];
        } else {
            $reponse = [
                'status' => 0,
                'message' => "Erreur lors de la requete...",
                'data' => []
            ];
        }
    } catch (\Throwable $th) {
        $reponse = [
            'status' => 0,
            'message' => $th->getMessage(),
            'data' => []
        ];
    }

    echo json_encode($reponse, JSON_PRETTY_PRINT);
} else {
    echo json_encode("Aucune donnée reçue");
}
