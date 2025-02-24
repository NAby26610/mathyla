<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $datautilisateur = [];
    try {
        // Récupérer les utilisateur spécifique par son ID
        $utilisateurId = $_GET['id'];
        $utilisateur = ModeleClasse::getone("utilisateur", $utilisateurId);
        
        if ($utilisateur):
            // Construire l'objet utilisateur à retourner
            $objet = [
                "id" => $utilisateur["id"],
                "nom" => $utilisateur["nom"],
                "prenom" => $utilisateur["prenom"],
                "telephone" => $utilisateur["telephone"],
                "privilege" => $utilisateur["privilege"],
                "email" => $utilisateur["email"],
                "adresse" => $utilisateur["adresse"],
                "ville" => $utilisateur["ville"],
                "pays" => $utilisateur["pays"]
                
            ];

            echo json_encode($objet, true);
        else:
            echo json_encode('utilisateur non trouvé');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
