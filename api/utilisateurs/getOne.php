<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $datautilisateur = [];
    try {
        // Récupérer l'utilisateur spécifique par son ID
        // $utilisateurId = $_GET['id'];
        extract($_GET);
        $utilisateur = ModeleClasse::getone("utilisateurs", $id);

        if ($utilisateur):
            $Affectation = ModeleClasse::getoneByname('id_utilisateur', 'affectations', $utilisateur['id']);
            if ($Affectation):
                $AG = ModeleClasse::getoneByname('id', 'agences', $Affectation['id_agence']);
                $zone = ModeleClasse::getoneByname('id', 'zones', $AG['id_zone']);
            endif;
            $privilege = 0;
            if ($utilisateur['roles'] == 'admin')
                $privilege = 1;
            else
                $privilege = 0;
            // Construire l'objet utilisateur à retourner
            $objet = [
                "id" => $utilisateur["id"],
                "Agence" => $AG["libelle"] ?? 'Aucune Affectationߠ',
                "nom" => $utilisateur["nom"],
                "prenom" => $utilisateur["prenom"],
                "telephone" => $utilisateur["telephone"],
                "roles" => $utilisateur["roles"],
                "email" => $utilisateur["email"],
                "privilege" => $privilege,
                "adresse" => $utilisateur["adresse"] ?? null,
                "zone" => $zone["libelle"] ?? null,
                "created_at" => $utilisateur["created_at"],
                "created_by" => $utilisateur["created_by"] ?? null,
                "modify_at" => $utilisateur["modify_at"],
            ];

            // Retourner l'objet utilisateur sous forme de JSON sans le mot de passe
            echo json_encode($objet, true);
        else:
            echo json_encode('Utilisateur non trouvé');
        endif;
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
