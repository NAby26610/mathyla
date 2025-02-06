<?php
require_once('../../config/database.php');
if (isset($_GET)) {
    extract($_GET);
    try {
        // SESSION
        $Session = ModeleClasse::getoneByname('id', 'utilisateurs', $id);
        if ($Session):
            // $accessNumbersArray = explode(',', $Session['access_number']); // Convertir en tableau
            $OBJ_USER = array(
                "id" => $Session['id'],
                "prenom" => $Session['prenom'],
                "nom" => $Session['nom'],
                "email" => $Session['email'],
                "adresse" => $Session['adresse'],
                "telephone" => $Session['telephone']
                // "access_number" => $accessNumbersArray, // Liste des accès
                // "statut" => intval($Session['statut']),
            );
            echo json_encode($OBJ_USER, true);
        else:
            echo json_encode([], true);
        endif;
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
}
