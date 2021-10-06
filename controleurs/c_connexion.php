<?php

/**
 * Gestion de la connexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
    case 'demandeConnexion':
        include 'vues/v_connexion.php';
        break;
    case 'valideConnexion':
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
        $type;
        $visiteur = $pdo->getInfosVisiteur($login);
        $comptable = $pdo->getInfoComptable($login, $mdp);
        $email = $visiteur['email'];
        $code = rand(1000, 9999);
        $pdo->setCodeA2F($id, $code);
        mail($email, '[GSB-AppliFrais]Code de verification', "Code : " . $code);
        include 'vues/v_a2f.php';
        if (!password_verify($mdp, $pdo->getMdpVisiteur($login))) {
            if (is_array($comptable)) {
                $id = $comptable['id'];
                $nom = $comptable['nom'];
                $prenom = $comptable['prenom'];
                connecterComptable($id, $nom, $prenom);
                header('Location: index.php');
            } else {
                $id = $visiteur['id'];
                $nom = $visiteur['nom'];
                $prenom = $visiteur['prenom'];
                connecterVisiteur($id, $nom, $prenom);
                header('Location: index.php');
            }
        } else {
            ajouterErreur('Login ou mot de passe incorrect');
            include 'vues/v_erreurs.php';
            include 'vues/v_connexion.php';
            break;
        }
    case 'valideA2FConnexion':
        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
        if ($pdo->getCodeVisiteur($_SESSION['idVisiteur']) !== $code) {
            ajouterErreur('Code de vérification incorrect');
            include 'vues/v_erreurs.php';
            include 'vues/v_code2facteurs.php';
        } else {
            connecterA2F($code);
            header("location:index.php");
        }
        break;

    default:
        include 'vues/v_connexion.php';
        break;
}
