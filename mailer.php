<?php

$EmailFrom = "francois.danet1@gmail.com";
$EmailTo = "francois.danet1@gmail.com";
$Subject = "Contact site internet";
$Name = Trim(stripslashes($_POST['Name']));
$Tel = Trim(stripslashes($_POST['Tel']));
$Email = Trim(stripslashes($_POST['Email']));
$Message = Trim(stripslashes($_POST['Message']));



// validation
$validationOK=true;
if (!$validationOK) {
    print "<meta http-equiv=\"refresh\" content=\"0;URL=error.html\">";
    exit;
}

// prepare email body text
$Body = "";
$Body .= "Name: ";
$Body .= $Name;
$Body .= "\n";
$Body .= "Tel: ";
$Body .= $Tel;
$Body .= "\n";
$Body .= "Email: ";
$Body .= $Email;
$Body .= "\n";
$Body .= "Message: ";
$Body .= $Message;
$Body .= "\n";

// send email

// redirect to success page
/*if ($success){
    print "<meta http-equiv=\"refresh\" content=\"0;URL=index.html\">";
}
else{
    print "<meta http-equiv=\"refresh\" content=\"0;URL=error.html\">";
}
*/


 // Ma clé privée

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

    if ($Message==null or $Email==null or $Tel==null or $Name==null){
   $decode['success'] = false;
}

    if ($decode['success'] == true) {
    	$success = mail($EmailTo, $Subject, $Body, "From: <$EmailFrom>");
    	print "<meta http-equiv=\"refresh\" content=\"0;URL=index.html\">";
        // C'est un humain

    }
    else {
        echo" Vous n'avez pas remplis tous les champs !";
        echo"                  ou                      "; 
        echo" Vous n'avez pas cochez la vérification google (CAPTCHA) !";
        // C'est un robot ou le code de vérification est incorrecte
    }
?>