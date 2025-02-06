<?php
require_once('../../config/database.php');
if (isset($_GET)) {
    extract($_GET);
    $response = [];
    try {
        // SESSION
        $Affecation = ModeleClasse::getall('affectations');
        foreach ($Affecation as $data):
            // Agence 
            $Agence = ModeleClasse::getoneByname('id', 'agences', $data['id_agence']);
            // User
            $User = ModeleClasse::getoneByname('id', 'utilisateurs', $data['id_utilisateur']);
            $Zone = ModeleClasse::getoneByname('id', 'zones', $Agence['id_zone']);
            $Objet = [
                'id' => $data['id'],
                'libelle' => $Agence['libelle'],
                'zone' => $Zone['libelle'] ?? "Inconnus",
                'indicatif' => $Agence['indicatif'],
                'Utilisateur' => $User['prenom'] . ' ' . $User['nom'],
                'telephone' => $User['telephone'],
                'adresse' => $User['adresse'],
            ];
            array_push($response, $Objet);
        endforeach;
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
}
