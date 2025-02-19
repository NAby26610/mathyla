<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $article = $_POST['article'];
    unset($_POST['article']);

    try {
        // Creation d'une article
        
            $ajout_partenaire = ModeleClasse::add(" $article", $_POST);
            if ($ajout_partenaire) {
                $message = "article ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de article.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
