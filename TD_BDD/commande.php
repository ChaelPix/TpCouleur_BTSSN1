<?php

$xGp = $_POST["numGp"];;
$xSc = $_POST["numSc"];;
$xAv = $_POST["numAv"];;

$nomClient = $_POST["ClientName"];
$prenomClient  = $_POST["ClientSurname"];
$adresseClient = $_POST["Adresse"];

$valEnvoyer = $_POST['send'];

if ($valEnvoyer == "Soumettre") {
    
    //Connect to DB
    $bdd = new mysqli('10.187.52.4', 'beaujardt', 'beaujardt', 'beaujardt_b');
    if ($bdd->connect_errno) {
        print("ERROR of connexion <br>"); 
        exit();
    }
    print("Connected to DB <br>"); 

    //PRIX
    $requete = "SELECT prix_unitaire from produit WHERE des_produit = 'Grille-pain'";
    $resultat=$bdd->query($requete);
    $ligne = $resultat->fetch_assoc(); 
    $prixGp = $ligne['prix_unitaire'] * $xGp; 

    $requete = "SELECT prix_unitaire from produit WHERE des_produit = 'Sèche cheveux'";
    $resultat=$bdd->query($requete);
    $ligne = $resultat->fetch_assoc(); 
    $prixSc = $ligne['prix_unitaire'] * $xSc; 

    $requete = "SELECT prix_unitaire from produit WHERE des_produit = 'Aspirateur de voiture'";
    $resultat=$bdd->query($requete);
    $ligne = $resultat->fetch_assoc(); 
    $prixAv = $ligne['prix_unitaire'] * $xAv; 

    $totalPrice = $prixAv+$prixGp+$prixSc;


    //CLIENT
    $query = "SELECT * FROM client2 WHERE nom = '$nomClient' && prenom = '$prenomClient' && adresse = '$adresseClient'";
    $result = $bdd->query($query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<br> client already registered!';
        } else {
            echo '<br> new client';
            $requete = "INSERT INTO `client2`(`id_client`, `nom`, `prenom`, `adresse`) VALUES (null,'$nomClient','$prenomClient','$adresseClient')";
            $resultat = $bdd->query($requete);
            if ($resultat == FALSE) die("Error Update Client. $bdd->error;");
        }
    } 

    //Facture
    //Client id
    $query = "SELECT * FROM client2 WHERE nom = '$nomClient' && prenom = '$prenomClient'";
    $resultat = $bdd->query($query);
    if ($resultat == TRUE) {
        $ligne = $resultat->fetch_assoc(); 
        $id = $ligne['id_client']; 
    } 

    $requete = "INSERT INTO facture(id_client, reglement) VALUES ($id, $totalPrice)";

    //Article
    //Select facture Id
    $requete = "SELECT MAX(id_facture) from facture";
    $resultat=$bdd->query($requete);
    $ligne = $resultat->fetch_assoc(); 
    $facture = $ligne['MAX(id_facture)'];

    //Get code products
    $requete = "SELECT code_produit from produit";
    $resultat = $bdd->query($requete);

    $i = 0;
    foreach($resultat as $row)
    {
        $code[$i] = $row['code_produit'];
        $i = $i + 1;
    }
    
    $requete = "INSERT INTO article(id_facture, code_produit, quantite) VALUES ($facture, $code[0] ,$xGp), VALUES ($facture, $code[1] ,$xSc), VALUES ($facture, $code[2] ,$xAv)";
}
?>