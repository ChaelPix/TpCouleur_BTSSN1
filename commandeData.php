<?php
//http://10.187.52.4/~beaujardt/TPCouleur.html

$redValue = $_POST["valeurRouge"];;
$greenValue = $_POST["valeurVert"];
$blueValue = $_POST["valeurBleu"];
$peintQuantMl = $_POST["quantityChoice"];
$nomClient = $_POST["ClientName"];
$prenomClient  = $_POST["ClientSurname"];
$adresseClient = $_POST["Adresse"];

$valEnvoyer = $_POST['send'];

if ($valEnvoyer == "Envoyer") {
    print("Nom : $nomClient <br>");
    print("Prenom : $prenomClient <br>");
    print("Adresse : $adresseClient <br> <br>");
    print("Quantite : $peintQuantMl ml<br>");
    print("Quantite Bleue : $blueValue <br>");
    print("Quantite Rouge : $redValue <br>");
    print("Quantite Vert : $greenValue <br> <br>");

    //RBG en CMYK
    $red = $redValue / 255;
    $green = $greenValue / 255;
    $blue = $blueValue / 255;
    $black = 1 - max($red, $green, $blue);

    //Check if black wasn't selected
    if ($black < 1) {
        $cyan = (int)(100 * ((1 - $red - $black) / (1 - $black))) / 100;
        $yellow = (int)(100 * ((1 - $blue - $black) / (1 - $black))) / 100;
        $magenta = (int)(100 * ((1 - $green - $black) / (1 - $black))) / 100;
    } else {
        $cyan = 0;
        $yellow = 0;
        $magenta = 0;
    }
    $black = (int)(100 * $black) / 100;
    print("1) CYMK = B : $black | C : $cyan | Y : $yellow | M : $magenta <br> <br>");

    //CMYK en %
    $white = (100 - $cyan * 100) + (100 - $magenta * 100) + (100 - $yellow * 100) + (100 - $black * 100);
    $cyanPercentage = $cyan / 4;
    $magentaPercentage = $magenta / 4;
    $yellowPercentage = $yellow / 4;
    $blackPercentage = $black / 4;
    $whitePercentage = $white / 4;
    print("2) % CYMK = W : $whitePercentage | B : $blackPercentage | C : $cyanPercentage | Y : $yellowPercentage | M : $magentaPercentage <br> <br>");

    //Quantite pour chaque couleur CMYK
    $cyanQuantity = $peintQuantMl * $cyanPercentage;
    $magentaQuantity = $peintQuantMl * $magentaPercentage;
    $yellowQuantity = $peintQuantMl * $yellowPercentage;
    $blackQuantity = $peintQuantMl * $blackPercentage;
    $whiteQuantity = $peintQuantMl * $whitePercentage;
    print("2b) Quantites en ml = C $cyanQuantity ml | M $magentaQuantity ml | Y : $yellowQuantity ml | B : $blackQuantity ml <br> <br>");

    //% de blanc dans chaque couleur CMYK
    $quantityProportion = $peintQuantMl / 4;

    $cyanPercentage = 1 - ($cyanQuantity / $quantityProportion);
    $magentaPercentage = 1 - ($magentaQuantity / $quantityProportion);
    $yellowPercentage = 1 - ($yellowQuantity / $quantityProportion);
    $blackPercentage = 1 - ($blackQuantity / $quantityProportion);
    $sommePercentage = $cyanPercentage * 100 + $magentaPercentage * 100 + $yellowPercentage * 100 + $blackPercentage * 100;
    print("3) % CYMK pour Blanc = B : $blackPercentage | C : $cyanPercentage | Y : $yellowPercentage | M : $magentaPercentage <br>");
    print("Somme % : $sommePercentage <br> <br>");

    //Quantite de blanc dans chaque couleur CMYK
    $whiteForCyan = $cyanPercentage / $sommePercentage * $whiteQuantity;
    $whiteForMagenta =  $magentaPercentage / $sommePercentage * $whiteQuantity;
    $whiteForYellow = $yellowPercentage / $sommePercentage * $whiteQuantity;
    $whiteForBlack =  $blackPercentage / $sommePercentage * $whiteQuantity;
    print("4) Quantite de blanc pour : C $whiteForCyan ml | M $whiteForMagenta ml | Y $whiteForYellow ml | B $whiteForBlack ml <br>");
    $whiteQuantity = $whiteForCyan+$whiteForMagenta+$whiteForYellow+$whiteForBlack;

    if ($black == 1){
        $blackQuantity = $peintQuantMl;
        $whiteQuantity = 0;
    }
    //SaveFile
    $fileName = "$nomClient $prenomClient.txt";
    $monFich = fopen($fileName, "w");
    if (!$monFich) {
        print("NO FILE FOUND");
        exit();
    }
    $toSave = "$nomClient,$prenomClient,$adresseClient\n$whiteQuantity,$blackQuantity,$cyanQuantity,$magentaQuantity,$yellowQuantity";
    print("<br>File Saved! <br> $nomClient,$prenomClient,$adresseClient\n$whiteQuantity,$blackQuantity,$cyanQuantity,$magentaQuantity,$yellowQuantity <br><br>");
    fputs($monFich, $toSave);
    fclose($monFich);

    //-----------------------------DataBase
    //Connect to DB
    $bdd = new mysqli('10.187.52.4', 'beaujardt', 'beaujardt', 'beaujardt_b');
    if ($bdd->connect_errno) {
        print("ERROR of connexion <br>"); 
        exit();
    }
    print("Connected to DB <br>"); 

    //Check the Stock
    $requete = "select * from stock";
    $resultat=$bdd->query($requete); // requête sur le stock
    if($resultat==TRUE){
    $ligne = $resultat->fetch_assoc(); // cette méthode permet d'obtenir un tableau sur le stock
    $noir = $ligne['quantite_noir'];
    $white = $ligne['quantite_blanc'];
    $cyan = $ligne['quantite_cyan'];
    $yellow = $ligne['quantite_yellow'];
    $magenta = $ligne['quantite_magenta'];

    echo "5). $noir. $white. $cyan. $yellow. $magenta";
    }

    //Calcul remaining stocks
    $white -= $whiteQuantity;
    $noir -= $blackQuantity;
    $cyan -= $cyanQuantity;
    $yellow -= $yellowQuantity;
    $magenta -= $magentaQuantity;
    if($white < 0 || $black < 0 || $cyan < 0 || $yellow < 0 || $magenta < 0)
    {
        print("<h2> Order cancelled, no enought stocks! </h2>");
        return;
    }

    //Update Stocks
    $requete = "UPDATE `stock` SET `id_stock`=1,`quantite_noir`=$noir,`quantite_blanc`=$white,`quantite_cyan`=$cyan,`quantite_magenta`=$magenta,`quantite_yellow`=$yellow WHERE 1";
    $resultat = $bdd->query($requete);
    if ($resultat == FALSE) die("Error Update Stocks. $bdd->error;");

    //Add Client
    $query = "SELECT * FROM client WHERE nom = '$nomClient' && prenom = '$prenomClient'";
    $result = $bdd->query($query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<br> client already registered!';
        } else {
            echo '<br> new client';
            $requete = "INSERT INTO `client`(`id_client`, `nom`, `prenom`, `adresse`) VALUES (null,'$nomClient','$prenomClient','$adresseClient')";
            $resultat = $bdd->query($requete);
            if ($resultat == FALSE) die("Error Update Client. $bdd->error;");
        }
    } 
    
    //Add Commande Data
    $currentDate = date('Y-m-d H:i:s');

    $query = "SELECT * FROM client WHERE nom = '$nomClient' && prenom = '$prenomClient'";
    $resultat = $bdd->query($query);
    if ($resultat == TRUE) {
        $ligne = $resultat->fetch_assoc(); 
        $id = $ligne['id_client']; 
    } 

    $requete = "INSERT INTO `commande`(`id_commande`, `id_client`, `id_stock`, `nb_blanc`, `nb_noir`, `nb_cyan`, `nb_magenta`, `nb_yellow`, `prep`)
    VALUES (null,$id,1,$whiteQuantity,$blackQuantity,$cyanQuantity,$magentaQuantity,$yellowQuantity,'$currentDate')"; //Recup ID Client
    $resultat = $bdd->query($requete);
    if($resultat == FALSE) die("Error Update Order. $bdd->error;"); 

    print("<h2> Order Sucess! </h2>");
    $bdd->close();
}
