<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $dataCaisse = [];

    try {
        $read = ModeleClasse::getall("caisse");

        if ($read):
            foreach ($read as $data):
                $typeOperation=ModeleClasse::getoneByname('id','typeOperation',$data['id_typeOperation']);
                $objet = [
                    "id" => $data["id"] ?: null,
                     "montant"=>$data['montant'],
                     "modeRegler"=>$data['modeRegler'],
                     "statut"=>$data['statut'] 
                    
                ];
                array_push($dataCaisse, $objet);
            endforeach;
            echo json_encode($dataCaisse, JSON_PRETTY_PRINT);
        else:
            echo json_encode(['status' => 0, 'message' => 'Aucune ligne trouvée'], JSON_PRETTY_PRINT);
        endif;
    } catch (\Throwable $th) {
        echo json_encode(['status' => 0, 'message' => $th->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Aucune donnée reçue'], JSON_PRETTY_PRINT);
}
