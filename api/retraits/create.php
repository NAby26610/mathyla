<?php
require_once('../../config/database.php');
if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as  $key => $value) {
        $_POST[$key] = str_secure($_POST[$key]);
    }
    $Password = md5($_POST['codePin']);
    extract($_POST);

    $reponse = [];
    try {

        // Utilisateur 
        $User = ModeleClasse::getoneByname('id', 'utilisateurs', $created_by);
        $Affectation = ModeleClasse::getoneByNameDesc('affectations', 'id_utilisateur', $created_by);
        $_SOLDE = $Affectation['soldeOuverture'];
        if ($User) {
            if ($User['codePin'] == $Password):

                // CONTROLE DU SOLDE
                // OPERATION =================================================================
                // Récupérer le transfert spécifique par son ID (DEPOT)
                $transfert = ModeleClasse::getallbyName("transfert", 'created_by', $Affectation['id_utilisateur']);
                $gainsEnvoie = 0;
                foreach ($transfert as $data):
                    // Calcul du gains
                    $gainsEnvoie += ($data['frais'] * 35) / 100;
                    $_SOLDE += ($data['montant'] + $gainsEnvoie);
                endforeach;

                // Récupérer le transfert spécifique par son ID (RETRAIT)
                $Retrait = ModeleClasse::getallbyName("transfert", 'modify_by', $Affectation['id_utilisateur']);
                $gainsRetrait = 0;
                foreach ($Retrait as $data):
                    if ($data['id_zone'] == $zone['id'] || $zone['id_devise'] == $devise['id']):
                        $calc = (($data['frais'] * 35) / 100);
                        $gainsRetrait += $calc;
                    else:
                        // LA ZONE EST DIFFERENT, ET LA DEVISE DEIFFERENT 
                        if ($zone['id_devise'] == 1) { // GNF - FCFA
                            $calc = (($r['frais'] * 35) / 100);
                            $gainsRetrait += $calc / $r['taux_du_jour'];
                        } elseif ($zone['id_devise'] == 2) { // FCFA - GNF
                            $calc = (($r['frais'] * 35) / 100);
                            $gainsRetrait += $calc * $r['taux_du_jour'];
                        }
                    endif;
                    $_SOLDE += $gainsRetrait;
                    $_SOLDE -= $data['montantRetrait'];
                endforeach;

                // FOND RECU
                $TF_1 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceDestination', $Affectation['id_agence']);
                foreach ($TF_1 as $data):
                    $_SOLDE += $data['montant'];
                endforeach;

                // FOND ENVOYER
                $TF_2 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceSource', $Affectation['id_agence']);
                foreach ($TF_2 as $data):
                    $_SOLDE -= $data['montant'];
                endforeach;
                // CONTROLE DU SOLDE

                $Transfert = ModeleClasse::getoneByname('id', 'transfert', $id_transfert);
                // Transfert
                if ($Transfert['montantRetrait'] <= $_SOLDE):
                    if ($Transfert['telDestinataire'] == $telDestinataire) :
                        unset($_POST['codePin']);
                        unset($_POST['telDestinataire']);
                        $ajout = ModeleClasse::add("retraits", $_POST);
                        if (!$ajout) {
                            $reponse = [
                                'status' => 1,
                                'message' => "Retrait effectuée avec succès"
                            ];
                        } else {
                            $reponse = [
                                'status' => 0,
                                'message' => "Une erreur s'est produite lors de la mise à jour"
                            ];
                        }
                    else:
                        $reponse = [
                            'status' => 0,
                            'message' => "Numero du Destinataire incorrect !"
                        ];
                    endif;
                else:
                    $reponse = [
                        'status' => 0,
                        'message' => "SOLDE INSUFFISANT POUR CE RETRAIT !"
                    ];
                endif;
            else:
                $reponse = [
                    'status' => 0,
                    'message' => "Votre Code-Pin est incorrect !"
                ];
            endif;
        } else {
            $reponse = [
                'status' => 0,
                'message' => "Utilisateur compromis...!"
            ];
        }
        echo json_encode($reponse, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnees reçu");
}
