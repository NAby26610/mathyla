<?php
require_once('../../config/database.php');

// Ajouter la fonction pour calculer la commission
function calculerCommission($id_transfert)
{
    global $connect;

    // Requête SQL pour récupérer les frais du transfert spécifié
    $req = $connect->prepare("SELECT frais FROM transfert WHERE id = :id_transfert");
    $req->bindParam(':id_transfert', $id_transfert, PDO::PARAM_INT);
    $req->execute();

    // Récupérer le résultat et calculer la commission
    $result = $req->fetch();

    // Si des frais sont trouvés, calculer la commission
    if ($result && isset($result['frais'])) {
        $frais = $result['frais'];

        // Calcul des commissions
        $commissionDepot = $frais * 0.35; // 35% pour le dépôt
        $commissionRetrait = $frais * 0.35; // 35% pour le retrait
        $commissionEntreprise = $frais * 0.30; // 30% pour l'entreprise

        // Retourner un tableau avec les différentes commissions
        return [
            'commission_depot' => $commissionDepot,
            'commission_retrait' => $commissionRetrait,
            'commission_entreprise' => $commissionEntreprise
        ];
    } else {
        // Si aucun frais n'est trouvé, retourner un tableau avec 0
        return [
            'commission_depot' => 0,
            'commission_retrait' => 0,
            'commission_entreprise' => 0
        ];
    }
}

if (isset($_GET)) {
    $dataagences = [];
    try {
        // Récupérer toutes les agences triées par ordre décroissant
        $read = ModeleClasse::getallDESC("agences");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur la zone associée à l'agence
                $zone = ModeleClasse::getoneByname('id', 'zones', $data['id_zone']);

                // UTILISATEUR
                $agent = 'Pas affecter';
                $agentNumber = 'Pas affecter';
                $reqUser = ModeleClasse::getoneByNameDesc('affectations', 'id_agence', $data['id']);
                if ($reqUser && $reqUser['id_utilisateur']):
                    $User = ModeleClasse::getoneByname('id', 'utilisateurs', $reqUser['id_utilisateur']) ?? [];
                    $agent = $User['prenom'] . ' ' . $User['nom'];
                    $agentNumber = $User['telephone'];
                else:
                    $reqUser = [];
                endif;

                // Récupérer les informations de transfert pour calculer la commission (exemple avec un ID de transfert fictif)
                // Tu peux adapter cette partie pour récupérer l'ID du transfert associé à chaque agence
                $id_transfert = 1; // Remplacer par l'ID du transfert correspondant à l'agence
                $commissions = calculerCommission($id_transfert);

                // Construire l'objet agence avec les relations
                $objet = [
                    "id" => $data["id"],
                    "zone" => $zone["libelle"] ?? null, // Zone associée à l'agence
                    "libelle" => $data["libelle"] ?? null,
                    "telephone" => $data["telephone"] ?? null,
                    "soldeInitial" => $data["soldeInitial"] ?? 0,
                    "soldeMax" => $data["soldeMax"] ?? 0,
                    "seuil" => $data["seuil"] ?? 0,
                    "indicatif" => $data["indicatif"] ?? '+224',
                    "adresse" => $data["adresse"] ?? null,
                    "heureOuverture" => $data["heureOuverture"] ?? null,
                    "heureFermeture" => $data["heureFermeture"] ?? null,
                    "descriptions" => $data["descriptions"] ?? null,
                    "created_at" => $data["created_at"] ?? null,
                    "created_by" => $data["created_by"] ?? null,
                    "updated_at" => $data["updated_at"] ?? null,
                    // Agent
                    "agent" => $agent,
                    "agentNumber" => $agentNumber,

                    // Ajouter les commissions calculées
                    "commission_depot" => $commissions['commission_depot'],
                    "commission_retrait" => $commissions['commission_retrait'],
                    "commission_agence" => $commissions['commission_entreprise']
                ];

                // Ajouter l'objet construit à la liste des agences
                array_push($dataagences, $objet);
            }
        } else {
            echo json_encode('Aucune agence trouvée');
            exit;
        }

        // Retourner les agences sous forme de JSON
        echo json_encode($dataagences, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
