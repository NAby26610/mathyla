<?php
require_once ('../../config/database.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {

        $_Biens = 0;
        $_Type = 0;
        $_Libre = 0;
        $_Occuper = 0;
        $_Reserver = 0;

        // Liste des proprietes
        $Propriete = ModeleClasse::getall("propriete");
        foreach ($Propriete as $data):
            ++$_Biens;
            // Libre
            if ($data['statut'] == 1)
                ++$_Libre;
            // Occuper
            if ($data['statut'] == 2)
                ++$_Occuper;
            // Reserver
            if ($data['statut'] == 3)
                ++$_Reserver;
        endforeach;

        // Liste des type de proprietes
        $TPropriete = ModeleClasse::getall("typepropriete");
        foreach ($TPropriete as $data):
            ++$_Type;
        endforeach;

        $Objet = [
            'id' => 1,
            '_Biens' => $_Biens,
            '_Type' => $_Type,
            '_Libre' => $_Libre,
            '_Occuper' => $_Occuper,
            '_Reserver' => $_Reserver,
        ];
        echo json_encode($Objet);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucun _GET envoyer...");
}
