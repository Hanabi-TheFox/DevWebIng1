<?php
    require_once '..\..\database\config\connection.php';
    require_once '..\..\database\config\database.php';
    session_start();

    // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
    if(!isset($_SESSION["UTILISATEUR"])){
        header('Location:../Vue/login.php');
        exit();
    }

    if ($_POST['mdp'] != $_SESSION["UTILISATEUR"]["mdp"]) {
        $_SESSION["erreur"] = "Mot de passe incorrect";
        header('Location:../Vue/editProfil.php');
        exit();
    }

    // On récupère les informations de l'utilisateur
    if ($_POST['nom'] != "") $_SESSION["UTILISATEUR"]["nom"] = $_POST['nom'];
    if ($_POST['prenom'] != "") $_SESSION["UTILISATEUR"]["prenom"] = $_POST['prenom'];
    $OLDMAIL = $_SESSION["UTILISATEUR"]["email"];
    if($_POST['mail'] != ""){
        $_SESSION["UTILISATEUR"]["email"] = $_POST['mail'];
    }

    if ($_POST['dateNaissance'] != "") $_SESSION["UTILISATEUR"]["dateNaissance"] = $_POST['dateNaissance'];
    if ($_POST['tel'] != "") $_SESSION["UTILISATEUR"]["tel"] = $_POST['tel'];
    if ($_POST['adresse'] != "") $_SESSION["UTILISATEUR"]["adresse"] = $_POST['adresse'];
    if ($_POST['ville'] != "") $_SESSION["UTILISATEUR"]["ville"] = $_POST['ville'];
    if ($_POST['codePostal'] != "") $_SESSION["UTILISATEUR"]["codePostal"] = $_POST['codePostal'];
    if ($_POST['pays'] != "") $_SESSION["UTILISATEUR"]["pays"] = $_POST['pays'];

    // Récupération des données POST
    $nom = $_SESSION["UTILISATEUR"]["nom"];
    $prenom = $_SESSION["UTILISATEUR"]["prenom"];
    $email = $_SESSION["UTILISATEUR"]["email"];
    $dateNaissance = date('Y-m-d', strtotime($_SESSION["UTILISATEUR"]["dateNaissance"]));
    $tel = $_POST['tel'];
    //Correction pour tel
    if (isset($_POST['tel']) && is_numeric($_POST['tel'])) {
        $tel = (int)$_POST['tel'];
    } else {
        $tel = $_SESSION["UTILISATEUR"]["tel"];
    }
    $adresse = $_SESSION["UTILISATEUR"]["adresse"];
    $ville = $_SESSION["UTILISATEUR"]["ville"];
    $codePostal = $_SESSION["UTILISATEUR"]["codePostal"];
    $pays = $_SESSION["UTILISATEUR"]["pays"];


    /*

    ERREUR A LA LIGNE 63 ON NE PEUT PAS MODIF L'EMAIL CAR C'EST UNE CLEF ETRANGERE DE LA TABLE COMPTE ???

    $connexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    mysqli_begin_transaction($connexion);

    // Préparation de la requête SQL

    // Mise à jour de la table InfoCompte
    $sql = "UPDATE InfoCompte SET emailCompte = '$email', nom = '$nom', prenom = '$prenom', dateNaissance = '$dateNaissance', telephone = '$tel', adresse = '$adresse', ville = '$ville', codePostal = '$codePostal', pays = '$pays' WHERE emailCompte = '$OLDMAIL'";

    if (!mysqli_query($connexion, $sql)) {
        mysqli_rollback($connexion);
        die("Erreur lors de la mise à jour de la table InfoCompte : " . mysqli_error($connexion));
    }

    // Mise à de jour la table Compte
    $sql2 = "UPDATE Compte SET email = '$email' WHERE email = '$OLDMAIL'";

    if (!mysqli_query($connexion, $sql2)) {
        mysqli_rollback($connexion);
        die("Erreur lors de la mise à jour de la table Compte : " . mysqli_error($connexion));
    }

    // Valider la transaction
    mysqli_commit($connexion);
    */


    // Mise à jour de la table InfoCompte
    $sql = "UPDATE InfoCompte SET nom = '$nom', prenom = '$prenom', dateNaissance = '$dateNaissance', telephone = '$tel', adresse = '$adresse', ville = '$ville', codePostal = '$codePostal', pays = '$pays' WHERE emailCompte = '$OLDMAIL';";
    $result = mysqli_query($conn, $sql);


    if ($email != $OLDMAIL) {
        // Mise à jour de l'email à part
        $sql1 = "UPDATE InfoCompte SET emailCompte = '$email' WHERE emailCompte = '$OLDMAIL';";
        $result = mysqli_query($conn, $sql);

        // Mise à jour de la table Compte
        $sql2 = "UPDATE Compte SET email = '$email' WHERE email = '$OLDMAIL';";
        $result = mysqli_query($conn, $sql2);
    }


    // Mettre à jour la variable de session
    $_SESSION["UTILISATEUR"]["email"] = $email;



    $_SESSION["succes"] = "Données du profil mis à jour";
    header('Location:../Vue/profil.php');
    exit();
?>