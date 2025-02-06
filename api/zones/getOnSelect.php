<?php
require_once('../../config/database.php');

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    echo json_encode(["error" => "Données manquantes"]);
    exit;
}

$id = $_GET['id'];
$type = $_GET['type'];

$dataNational = [];
$dataInternational = [];

try {
    // Trouver l'agence affectée à l'utilisateur
    $Affectation = ModeleClasse::getoneByNameDesc('affectations', 'id_utilisateur', $id);

    if ($Affectation) {
        // Récupérer l'agence et la zone
        $Agence = ModeleClasse::getoneByname('id', 'agences', $Affectation['id_agence']);
        if ($Agence) {
            $Zone = ModeleClasse::getoneByname('id', 'zones', $Agence['id_zone']);

            if ($type === 'national') { // Transfert national
                $List = ModeleClasse::getoneByname('id', 'zones', $Agence['id_zone']);
                $devise = ModeleClasse::getoneByname('id', 'devise', $Zone['id_devise']);

                $objet = [
                    "id" => $List["id"],
                    "id_devise" => $devise["id"] ?? null,
                    "devise" => $devise["libelle"] ?? "Inconnus !",
                    "libelle" => $List["libelle"] ?? "Inconnus !",
                    "created_at" => $List["created_at"] ?? "Inconnus !",
                    "created_by" => $List["created_by"] ?? "Inconnus !",
                ];
                $dataNational[] = $objet;
            } else { // Transfert international
                $List_ = ModeleClasse::getall('zones');

                foreach ($List_ as $_zn) {
                    if ($_zn['id'] !== $Agence['id_zone']) {
                        $devise = ModeleClasse::getoneByname('id', 'devise', $_zn['id_devise']);

                        $objet = [
                            "id" => $_zn["id"],
                            "id_devise" => $devise["id"] ?? null,
                            "devise" => $devise["libelle"] ?? "Inconnus !",
                            "libelle" => $_zn["libelle"] ?? "Inconnus !",
                            "created_at" => $_zn["created_at"] ?? "Inconnus !",
                            "created_by" => $_zn["created_by"] ?? "Inconnus !",
                        ];
                        $dataInternational[] = $objet;
                    }
                }
            }
        }
    } else { // Cas d'un administrateur sans affectation
        $List = ModeleClasse::getall('zones');

        foreach ($List as $info_) {
            $devise_ = ModeleClasse::getoneByname('id', 'devise', $info_['id_devise']);

            $objet_ = [
                "id" => $info_["id"],
                "id_devise" => $devise_["id"] ?? null,
                "devise" => $devise_["libelle"] ?? "Inconnus !",
                "libelle" => $info_["libelle"] ?? "Inconnus !",
                "created_at" => $info_["created_at"] ?? "Inconnus !",
                "created_by" => $info_["created_by"] ?? "Inconnus !",
            ];
            $dataNational[] = $objet_;
            $dataInternational[] = $objet_;
        }
    }

    echo json_encode($type === "national" ? $dataNational : $dataInternational, JSON_PRETTY_PRINT);
} catch (Throwable $th) {
    echo json_encode(["error" => $th->getMessage()]);
}
