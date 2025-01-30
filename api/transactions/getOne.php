<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $dataTransaction = [];
    try {
        // Récupérer la transaction spécifique par son ID
        $transactionId = $_GET['id'];
        $transaction = ModeleClasse::getone("transactions", $transactionId);
        
        if ($transaction):
            // Construire l'objet transaction à retourner
            $objet = [
                "id" => $transaction["id"],
                "id_agence" => $transaction["id_agence"],
                "id_devise" => $transaction["id_devise"] ?? null,  // ID de la devise associée
                "montant" => $transaction["montant"],
                "typeTransaction" => $transaction["typeTransaction"],
                "created_at" => $transaction["created_at"],
                "updated_at" => $transaction["updated_at"],
                
            ];

            // Retourner l'objet transaction sous forme de JSON
            echo json_encode($objet, true);
        else:
            echo json_encode('Transaction non trouvée');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
