<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $transactions = $_POST['transactions'];
    unset($_POST['transactions']);

    try {
        // Creation d'une transactions
        
            $ajout_partenaire = ModeleClasse::add(" $transactions", $_POST);
            if ($ajout_partenaire) {
                $message = "transactions ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de transactions.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
