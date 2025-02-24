<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $dataAffectations = [];  // Initialisation du tableau vide

    try {
        // Récupérer toutes les affectations triées par ordre décroissant
        $read = ModeleClasse::getallDESC("affectation");

        if (!empty($read)) {
            foreach ($read as $data) {
                // Récupérer les informations de l'utilisateur associé
                $utilisateur = ModeleClasse::getoneByname('id', 'utilisateur', $data['id_utilisateur']);
                
                // Récupérer les informations de l'entité associée
                $entite = ModeleClasse::getoneByname('id', 'entite', $data['id_entite']);

                // Construction de l'objet affectation
                $objet = [
                    "id_affectation" => $data["id"] ?? null,
                    "utilisateur" => [
                        "id_utilisateur" => $utilisateur["id"] ?? null,
                        "nom" => $utilisateur["nom"] ?? null,
                        "prenom" => $utilisateur["prenom"] ?? null,
                        "telephone" => $utilisateur["telephone"] ?? null,
                        "privilege" => $utilisateur["privilege"] ?? null,
                        "email" => $utilisateur["email"] ?? null
                    ],
                    "entite" => [
                        "id_entite" => $entite["id"] ?? null,
                        "reference" => $entite["reference"] ?? null,
                        "codeEntite" => $entite["codeEntite"] ?? null
                    ],
                    "created_at" => $data["created_at"] ?? null,
                    "updated_at" => $data["updated_at"] ?? null
                ];

                // Ajouter l'affectation traitée à la liste
                $dataAffectations[] = $objet;
            }
        } else {
            echo json_encode(["message" => "Aucune affectation trouvée"]);
            exit;
        }

        // Retourner les affectations sous forme de JSON avec un affichage propre
        echo json_encode($dataAffectations, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>
