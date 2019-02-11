<?php
// Permet de verifier la norme du serveur pour les retours à la ligne
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", "francois.danet@hotmail.fr")){
	$passage_ligne = "\r\n";
}else{
	$passage_ligne = "\n";
}

//Message d'erreur envoyé à l'utilisateur.
$messageErreur=" Vous n'avez pas remplis tous les champs !</br>".$passage_ligne.
        "                  ou                     </br> ".$passage_ligne.
        " Vous n'avez pas cochez la vérification google (CAPTCHA) !".$passage_ligne." </br> Veuillez recommencer ! ";
       

//Expediteur
$EmailFrom = "francois.danet@hotmail.fr";
//Destinataire
$EmailTo   = "francois.danet@hotmail.fr";
//Object
$Subject   = "Contact site internet";
//Récupe des éléments du formulaire.
$Name      = Trim(stripslashes($_POST['Name']));
$Tel       = Trim(stripslashes($_POST['Tel']));
$Email     = Trim(stripslashes($_POST['Email']));
$Message   = Trim(stripslashes($_POST['Message']));
//permet de couper les longs text pour avoir un retour a la ligne .
$message   = wordwrap($message, 70, "\r\n");
//Boulean pour savoir si le mail est valide (tous les champs sont remplies)
$validationOK = true;

//Condition qui verifie si le formulaire est bien remplie
if ($Message==null or $Email==null or $Tel==null or $Name==null){
    // validation
    $validationOK=false;
    echo($messageErreur);
    print "<meta http-equiv=\"refresh\" content=\"5;URL=index.html#contact\">";
    exit;
 }


//=====Création du header de l'e-mail.
$header = "From: \"François DANET\"<francois.danet@hotmail.fr>".$passage_ligne;
$header.= "Reply-to: \".$Email.\" <".$Email.">".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "X-Priority: 3".$passage_ligne;
$header.= "Content-Type: multipart/mixed;".$passage_ligne." ".$passage_ligne;

//=====Corps du mail : 
$Body = "";
$Body .= "Name: " .$passage_ligne;
$Body .= htmlspecialchars($Name) .$passage_ligne;
$Body .= "Tel: " .$passage_ligne;
$Body .= htmlspecialchars($Tel) .$passage_ligne;
$Body .= "Email: " .$passage_ligne;
$Body .= htmlspecialchars($Email) .$passage_ligne;
$Body .= "Message: " .$passage_ligne;
$Body .= htmlspecialchars($Message) .$passage_ligne;
$Body .= "Date: ".date("r (T)")." \r\n";



// redirect to success page
/*if ($success){
    print "<meta http-equiv=\"refresh\" content=\"0;URL=index.html\">";
}
else{
    print "<meta http-equiv=\"refresh\" content=\"0;URL=error.html\">";
}
*/


 // Captcha : clé privé.

    $secret = "6Lff_lYUAAAAAIZu3Or7neu6iZT8_iuVH7pQ27PK";

    // Paramètre renvoyé par le recaptcha

    $response = $_POST['g-recaptcha-response'];

    // On récupère l'IP de l'utilisateur

    $remoteip = $_SERVER['REMOTE_ADDR'];

    

    $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 

        . $secret

        . "&response=" . $response

        . "&remoteip=" . $remoteip ;

    

    $decode = json_decode(file_get_contents($api_url), true);

    if (!$validationOK){
        $decode['success'] = false;
        echo ($messageErreur);
        print "<meta http-equiv=\"refresh\" content=\"5;URL=index.html#contact\">";
    }

    if ($decode['success'] == true) {
        $success = mail($EmailTo, $Subject, $Body);
    	
        // C'est un humain

    }
    else {
        echo($messageErreur);
        print "<meta http-equiv=\"refresh\" content=\"2;URL=index.html#contact\">";
    }


    if ($success){
       echo("<html>");
       echo("<head >");
         echo("<title>Formulaire envoyé ! </title>");
         echo("<link rel='stylesheet' type='text/css' href='css/style.css'>");
       echo("</head>");
       echo("<body>");
        echo("<p></p>");
        echo("</br>");
        echo("<a class='preloaderRedirection black'>Veuillez patienter ....  </br>vous allez être redirigé automatiquement </a>");
        echo("</br>");
        echo("<div class='preloaderRedirection'>");
		echo("<img src='images/loader.svg' alt='Preloader image'>");
	    echo("</div>");
        echo("En cas d'erreur : "); 
        echo("<a href='http://francois-danet.fr/' class='preloaderRedirection'>Cliquez-ici !</a>");
        //  echo("<table>");
        //      echo("<tr>");
        //         echo("<th>Personne</th><th>Jour</th><th>Mois</th><th>Année</th>");
        //      echo("</tr>");
        //      echo("<tr>");
        //         echo("<td>Josiane</td><td>3</td><td>Août</td><td>1970</td>");
        //      echo("</tr>");
        //      echo("<tr>");
        //         echo("<td>Emma</td><td>26</td><td>Août</td><td>1973</td>");
        //     echo("</tr>");
        //  echo("</table>");
       echo("</body>");
       echo("</html>");
        print "<meta http-equiv=\"refresh\" content=\"5;URL=index.html\">";
    }else{
        $errorMessage = error_get_last()['message'];
        echo ($errorMessage);
        print "<meta http-equiv=\"refresh\" content=\"8;URL=index.html#contact\">";
    }





?>