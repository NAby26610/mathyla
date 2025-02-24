<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $reponse = [];
    $data = [];

    if ($id) {
        try {
            $init = ModeleClasse::getoneByname('id', 'initcommande', $id);
            if ($init) {
                // Récupération des articles du panier
                $Panier = ModeleClasse::getallbyName('paniercommande', 'id_initCommande', $init['id']);

                // Récupération des informations du fournisseur
                $fournisseur = ModeleClasse::getoneByname('id', 'fournisseur', $init['id_fournisseur']);

                // Préparer les données du fournisseur (seulement les infos importantes)
                $infoFournisseur = $fournisseur ? [
                    'Raison_sociale' => $fournisseur['raison_sociale'],
                    'Representant' => $fournisseur['representant'],
                    'Telephone' => $fournisseur['telephone'],
                    'Adresse' => $fournisseur['adresse'],
                    'Pays' => $fournisseur['pays'],
                ] : null;

                foreach ($Panier as $item) {
                    $ART = ModeleClasse::getoneByname('id', 'article', $item['id_article']);
                    $Objet = [
                        'id' => $item['id'],
                        'quantite' => $item['quantite'],
                        'reference' => $ART ? $ART['reference'] : 'N/A',
                        'designation' => $ART ? $ART['designation'] : 'N/A',
                        'prix_unitaire_initiale' => $ART ? $ART['puInitial'] : 'N/A',
                        'quantité_initiale' => $ART ? $ART['qteInitiale'] : 'N/A',
                        'prix_de_vente_initial' => $ART ? $ART['pvInitial'] : 'N/A',
                        'date_de_peremption' => $ART ? $ART['datePeremption'] : 'N/A',
                        
                    ];
                    array_push($data, $Objet);
                }

                $reponse = [
                    'status' => 1,
                    'message' => "Données récupérées avec succès.",
                    'fournisseur' => $infoFournisseur,
                    'articles' => $data
                ];
            } else {
                $reponse = [
                    'status' => 0,
                    'message' => "Aucune commande trouvée avec cet ID.",
                    'data' => []
                ];
            }
        } catch (\Throwable $th) {
            $reponse = [
                'status' => 0,
                'message' => "Erreur lors de la requête : " . $th->getMessage(),
                'data' => []
            ];
        }
    } else {
        $reponse = [
            'status' => 0,
            'message' => "Le paramètre 'id' est manquant dans la requête.",
            'data' => []
        ];
    }

    echo json_encode($reponse, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["status" => 0, "message" => "Méthode non autorisée. Utilisez GET."], JSON_PRETTY_PRINT);
}
