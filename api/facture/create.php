<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $facture = $_POST['facture'];
    unset($_POST['facture']);

    try {
        // Creation d'une facture
        
            $ajout_partenaire = ModeleClasse::add(" $facture", $_POST);
            if ($ajout_partenaire) {
                $message = "facture ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de facture.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
