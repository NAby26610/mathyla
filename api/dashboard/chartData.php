<?php
require_once ('../../config/database.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $_chartValue = [];

        // Contrat
        $Contrat = ModeleClasse::getall('propriete');
        $_libre = 0;
        $_reservation = 0;
        $_occupation = 0;
        foreach ($Contrat as $data):
            // Toutes les propiretes
            if ($data['statut'] == 1): // Libre
                ++$_libre;
                $Objet = [
                    'libelle' => 'Libre',
                    'data' => $_libre,
                    'bgColor' => 'lightgreen',
                ];
                array_push($_chartValue, $Objet);
            elseif ($data['statut'] == 2): // Reservation
                ++$_reservation;
                $Objet = [
                    'libelle' => 'Reservation',
                    'data' => $_reservation,
                    'bgColor' => 'lightblue',
                ];
                array_push($_chartValue, $Objet);
            elseif ($data['statut'] == 3): // Occupation
                ++$_occupation;
                $Objet = [
                    'libelle' => 'Occupation',
                    'data' => $_occupation,
                    'bgColor' => 'grey',
                ];
                array_push($_chartValue, $Objet);
            endif;
        endforeach;

        echo json_encode($_chartValue);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucun _GET envoyer...");
}
