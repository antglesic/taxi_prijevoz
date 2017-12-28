<?php
include("baza.class.php");
include("sesija.class.php");

if (Sesija::dajKorisnika() != NULL) {  //provjera postoji li sesija
    //echo "Sesija postoji!" . "<br>";
    header('Location: index.php?odjava=0');
}



$mysql = new Baza();
$mysql->spojiDB();
$response;
$aktivac_kod;
$zastavica = 0;
$zastavica1 = 0;
$povratna_informacija1 = "";
$povratna_informacija2 = "";

if ($mysql->pogreskaDB()) {
    echo "Problem kod upita za bazu podataka!";
    exit;
}

foreach ($_POST as $key => $value) {

    $gr = "";    //ime   empty
    $gr1 = "";   //prez  empty
    $gr2 = "";   //korimeempty
    $gr3 = "";   //email empty
    $gr4 = "";   //loz1  empty
    $gr5 = "";   //loz2  empty
    $gr6 = "";   //ime   symbol
    $gr7 = "";   //prez  symbol
    $gr8 = "";    //korimesymbol
    $gr9 = "";   //email symbol
    $gr10 = "";    //loz1  symbol
    $gr11 = "";    //loz2  symbol
    $gr12 = "";     //loz1  strlen5
    $gr13 = "";     //loz1  format
    $gr14 = "";     //loz1==loz2
    $gr15 = "";     //email format
    $gr16 = "";     //email postoji u bazi
    $gr17 = "";     //korime postoji u bazi
    $gr18 = "";     //recaptcha

    $ime = $_POST["ime"];
    $prezime = $_POST["prez"];
    $korime = $_POST["korIme"];
    $email = $_POST["email"];
    $lozinka1 = $_POST["lozinka1"];
    $lozinka2 = $_POST["lozinka2"];

    if (empty($_POST["ime"])) {
        $gr = "[Ime] - ne smije biti prazno!" . "<br>";
        $zastavica++;
    } else {
        $gr = "";
    }
    if (empty($_POST["prez"])) {
        $gr1 = "[Prezime] - ne smije biti prazno!" . "<br>";
        $zastavica++;
    } else {
        $gr1 = "";
    }
    if (empty($_POST["korIme"])) {
        $gr2 = "[Korisnicko ime] - ne smije biti prazno!" . "<br>";
        $zastavica++;
    } else {
        $gr2 = "";
    }
    if (empty($_POST["email"])) {
        $gr3 = "[E-mail] - ne smije biti prazno!" . "<br>";
        $zastavica++;
    } else {
        $gr3 = "";
    }
    if (empty($_POST["lozinka1"])) {
        $gr4 = "[Lozinka] - ne smije biti prazno!" . "<br>";
        $zastavica++;
    } else {
        $gr4 = "";
    }
    if (empty($_POST["lozinka2"])) {
        $gr5 = "[Ponovljena lozinka] - ne smije biti prazno!" . "<br>";
        $zastavica++;
    } else {
        $gr5 = "";
    }

    if (strpos($ime, '!') or strpos($ime, ',') or strpos($ime, '(') or strpos($ime, ')') or strpos($ime, '[') or strpos($ime, ']') or strpos($ime, '{') or strpos($ime, '}') or strpos($ime, '\\') or strpos($ime, '/') or strpos($ime, '\'') or strpos($ime, '#') or strpos($ime, '\"') or strpos($ime, '?')) {
        $gr6 = "[Ime] - sadrzi simbol!" . "<br>";
        $zastavica++;
    } else {
        $gr6 = "";
    }
    if (strpos($prezime, '!') or strpos($prezime, ',') or strpos($prezime, '(') or strpos($prezime, ')') or strpos($prezime, '[') or strpos($prezime, ']') or strpos($prezime, '{') or strpos($prezime, '}') or strpos($prezime, '\\') or strpos($prezime, '/') or strpos($prezime, '\'') or strpos($prezime, '#') or strpos($prezime, '\"') or strpos($prezime, '?')) {
        $gr7 = "[Prezime] - sadrzi simbol!" . "<br>";
        $zastavica++;
    } else {
        $gr7 = "";
    }
    if (strpos($korime, '!') or strpos($korime, ',') or strpos($korime, '(') or strpos($korime, ')') or strpos($korime, '[') or strpos($korime, ']') or strpos($korime, '{') or strpos($korime, '}') or strpos($korime, '\\') or strpos($korime, '/') or strpos($korime, '\'') or strpos($korime, '#') or strpos($korime, '\"') or strpos($korime, '?')) {
        $gr8 = "[Korisnicko ime] - sadrzi simbol!" . "<br>";
        $zastavica++;
    } else {
        $gr8 = "";
    }
    if (strpos($email, '!') or strpos($email, ',') or strpos($email, '(') or strpos($email, ')') or strpos($email, '[') or strpos($email, ']') or strpos($email, '{') or strpos($email, '}') or strpos($email, '\\') or strpos($email, '/') or strpos($email, '\'') or strpos($email, '#') or strpos($email, '\"') or strpos($email, '?')) {
        $gr9 = "[E-mail] - sadrzi simbol!" . "<br>";
        $zastavica++;
    } else {
        $gr9 = "";
    }
    if (strpos($lozinka1, '!') or strpos($lozinka1, ',') or strpos($lozinka1, '(') or strpos($lozinka1, ')') or strpos($lozinka1, '[') or strpos($lozinka1, ']') or strpos($lozinka1, '{') or strpos($lozinka1, '}') or strpos($lozinka1, '\\') or strpos($lozinka1, '/') or strpos($lozinka1, '\'') or strpos($lozinka1, '#') or strpos($lozinka1, '\"') or strpos($lozinka1, '?')) {
        $gr10 = "[Lozinka] - sadrzi simbol!" . "<br>";
        $zastavica++;
    } else {
        $gr10 = "";
    }
    if (strpos($lozinka2, '!') or strpos($lozinka2, ',') or strpos($lozinka2, '(') or strpos($lozinka2, ')') or strpos($lozinka2, '[') or strpos($lozinka2, ']') or strpos($lozinka2, '{') or strpos($lozinka2, '}') or strpos($lozinka2, '\\') or strpos($lozinka2, '/') or strpos($lozinka2, '\'') or strpos($lozinka2, '#') or strpos($lozinka2, '\"') or strpos($lozinka2, '?')) {
        $gr11 = "[Ponovljena lozinka] - sadrzi simbol!" . "<br>";
        $zastavica++;
    } else {
        $gr11 = "";
    }
    if (strlen($lozinka1) < 5) {
        $gr12 = "[Lozinka] - nije dovoljno duga (5)" . "<br>";
        $zastavica++;
    } else {
        $gr12 = "";
    }
    if (preg_match("/^(?=(?:.*[A-Z]){2,})(?=(?:.*[a-z]){2,})(?=(?:.*[0-9]){1,})\S{5,15}$/", $lozinka1) == false) {
        $gr13 = "[Lozinka] - nije dobar format!" . "<br>";
        $zastavica++;
    } else {
        $gr13 = "";
    }
    if ($lozinka2 !== $lozinka1) {
        $gr14 = "[Ponovljena lozinka] - nije jednaka prvoj lozinki!" . "<br>";
        $zastavica++;
    } else {
        $gr14 = "";
    }
    if (preg_match("/[a-z0-9]+[_a-z0-9\.-]*[a-z0-9]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})/", $email) == false) {
        $gr15 = "[E-mail] - nije dobar format!" . "<br>";
        $zastavica++;
    } else {
        $gr15 = "";
    }

    $check_email = $mysql->selectDB("SELECT email FROM korisnik WHERE email = '$email' ");
    if (mysqli_num_rows($check_email) > 0) {
        $gr16 = "[E-mail] - postoji vec u bazi!" . "<br>";
        $zastavica++;
    } else {
        $gr16 = "";
    }

    $check_korime = $mysql->selectDB("SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = '$korime'");
    if (mysqli_num_rows($check_korime) > 0) {
        $gr17 = "[Korisnicko ime] - postoji vec u bazi!" . "<br>";
        $zastavica++;
    } else {
        $gr17 = "";
    }

    require_once('reCAPTCHA/recaptchalib.php');
    $secret = "6Ldvah8UAAAAABk4wTihD_mNl4JlYLgZmUXlDP1R";
    $captcha = $_POST['g-recaptcha-response'];
    if (!empty($captcha)) {
        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldvah8UAAAAABk4wTihD_mNl4JlYLgZmUXlDP1R&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
        if ($response == true) {
            $gr18 = "";
        } else {
            $zastavica++;
            $gr18 = "[reCAPTCHA] - nije validirano" . "<br>";
        }
    } else {
        $gr18 = "[reCAPTCHA] - da niste mozda robot?" . "<br>";
        $zastavica++;
    }
}

if ($zastavica == 0) {
    //$rand = substr(str_shuffle("abcd", 0, 2));
    foreach ($_POST as $key => $value) {
        $salt = sha1(time());
        $lozinka = $_POST["lozinka1"];
        //$salt = sha1("datum_registracije"); //Za projekt umjesto time() treba uzeti datum registracije iz baze podataka za korisnika [OVO JE SAMO ZA OBRAZAC PRIJAVA, A ZA REGISTRACIJU KORISTI TIME(0);
        //$lozinka = $_POST["lozinka"];
        $kriptirana_lozinka = sha1($salt . "--" . $lozinka);
        //if($kriptirana_lozinka == $red["lozinka"]);
        $slano_vrime = sha1(date("F j, Y, g:i a"));
        $slano_korime = sha1($korime);
        $aktivac_kod = sha1($slano_vrime . "--" . $slano_korime);

        $sql_script = "INSERT INTO `korisnik`( `uloga`, `status`, `ulogiran`, `aktivac_kod`, `koraci`, `ime`, `prezime`, `korisnicko_ime`, `email`, `lozinka`, `lozinkaKript`) VALUES ('" . '3' . "', '" . '0 - neaktivan' . "', '" . '0' . "', '" . $aktivac_kod . "', '" . $_POST["koraci"] . "', '" . $_POST["ime"] . "', '" . $_POST["prez"] . "', '" . $_POST["korIme"] . "', '" . $_POST["email"] . "', '" . $_POST["lozinka1"] . "', '" . $kriptirana_lozinka . "')";

        $mysql->updateDB($sql_script);
        $zastavica1 = 1;
    }
} else {
    $zastavica1 = 0;
}


if ($zastavica1 == 1) {
    if ($zastavica == 0) {

        $mail_to = $email;   //$_POST["email"];
        $mail_from = "From: WebDiP_2017@foi.hr";
        $mail_subject = "Aktivacija registracije";      //$_POST["subjekt"];
        $link = "http://barka.foi.hr/WebDiP/2016/zadaca_03/antglesic/projekt/aktivacija.php" . "?korIme=" . $_POST["korIme"] . "&key=" . $aktivac_kod . "&mode=1";

        $mail_body = "Pritisnite na iduci link kako biste aktivirali vas racun: <a href='" . $link . "'>" . $link . "</a>";   //$_POST["tekst"];

        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            $povratna_informacija1 = "Uspjesno ste registrirali vas racun!" . "<br>";
            $povratna_informacija2 = "Pritisnite na iduci link kako biste aktivirali vas racun: <a href='" . $link . "'>" . $link . "</a>";
        } else {
            $povratna_informacija1 = "Problem kod registracije korisnickog racuna!" . "<br>";
            $povratna_informacija2 = "";
        }
        
        $trenutno_cisto = date('Y-m-d G:i:s');
        $dogadaj = "Registriran korisnik " . $korime;
        $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('$trenutno_cisto','$dogadaj')";
        $mysql->updateDB($sql_script_dnevnik);

    }
}


$mysql->zatvoriDB();
?>


<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Registracija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Registracija" />
        <meta name="kljucne_rijeci" content="projekt, registracija, taxi-prijevoz" />
        <meta name="datum_izrade" content="25.05.2017." />
        <meta name="autor" content="Antonio Glešić" />
        <style type="text/css">
            form {
                width: 16.6667%;
                padding: 8% 0 0;
                margin: auto;
            }
            form {
                font-family: "Lucida Sans Unicode, Lucida Grande, sans-serif";
                position: relative;
                z-index: 1;
                background: #FFFFFF;
                max-width: 360px;
                width: calc(15%*5);
                margin: 0 auto 100px;
                padding: 45px;
                text-align: center;
                box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }

            form input:focus {
                border-bottom: 1px solid crimson;
                border-top: 1px solid crimson;
                border-right: 1px solid crimson;
                border-left: 1px solid crimson;
            }

            form .button {
                font-family: "Lucida Sans Unicode, Lucida Grande, sans-serif";
                text-transform: uppercase;
                outline: 0;
                background: crimson;
                width: 100%;
                border: 0;
                padding: 15px;
                color: #FFFFFF;
                font-size: 14px;
                cursor: pointer;
            }
            form .button:hover,form .button:active,form .button:focus {
                background: indianred;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="css/antglesic.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <header>
            <figure class="header">
                <img src="slike/logo.png" width="400" height="100" alt="LOGO" usemap="#mapa1" style="background-color: crimson"/>
                <map name="mapa1">
                    <area href="index.html" shape="rect" alt="pravokutnik" coords="0,0,200,100" target="a" />
                    <area href="#sadrzaj" shape="rect" alt="pravokutnik2" coords="200,0,400,100" />
                </map>
                <figcaption class="figcaption">Logo</figcaption>
            </figure>
        </header>
        <nav>
            <ul>
                <li>
                    <a href="index_novi.php?odjava=0">Naslovnica</a>  
                </li>
                <li>
                    <a href="podrucja.php">Popis Područja</a>  
                </li>
                <li>
                    <a href="prijava.php">Prijava</a>  
                </li>
                <li>
                    <a href="registracija.php" class="active">Registracija</a>  
                </li>
                <li>
                    <a href="o_autoru.html">O Autoru</a>  
                </li>
                <li>
                    <a href="dokumentacija.html">Dokumentacija</a>  
                </li>
            </ul>
        </nav>
        <p id="greske">
            <?php
            if (isset($gr)) {   //ime
                echo $gr;
            }
            if (isset($gr6)) {  //ime
                echo $gr6;
            }
            if (isset($gr1)) {  //prez
                echo $gr1;
            }
            if (isset($gr7)) {  //prez
                echo $gr7;
            }
            if (isset($gr2)) {  //korime
                echo $gr2;
            }
            if (isset($gr8)) {  //korime
                echo $gr8;
            }
            if (isset($gr17)) {  //korime
                echo $gr17;
            }
            if (isset($gr3)) {  //mail
                echo $gr3;
            }
            if (isset($gr9)) {  //mail
                echo $gr9;
            }
            if (isset($gr15)) { //mail
                echo $gr15;
            }
            if (isset($gr16)) {  //mail
                echo $gr16;
            }
            if (isset($gr4)) {  //loz1
                echo $gr4;
            }
            if (isset($gr10)) { //loz1
                echo $gr10;
            }
            if (isset($gr12)) {  //loz1
                echo $gr12;
            }
            if (isset($gr13)) {  //loz1
                echo $gr13;
            }
            if (isset($gr5)) {  //loz2
                echo $gr5;
            }
            if (isset($gr11)) { //loz2
                echo $gr11;
            }
            if (isset($gr14)) {  //loz2
                echo $gr14;
            }
            if (isset($gr18)) { //reCAPTCHA
                echo $gr18;
            }
            if (isset($povratna_informacija1)) {
                echo $povratna_informacija1;
            }
            if (isset($povratna_informacija2)) {
                echo $povratna_informacija2;
            }
            ?>
        </p>
        <section>
            <h2 class="figcaption">Prazno mjesto</h2>
            <form method="POST" id="registracija" name="registracija" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <label for="ime">Ime: </label>
                <input type="text" id="ime" name="ime" size="15" maxlength="15" placeholder="Ime" autofocus="autofocus"><br>
                <label for="prez">Prezime: </label>
                <input type="text" id="prez" name="prez" size="25" maxlength="25" placeholder="Prezime"><br>
                <label for="korIme">Korisničko ime: </label>
                <input type="text" id="korIme" name="korIme" size="15" maxlength="15" placeholder="Korisničko ime"><br>
                <label for="email">Email adresa: </label>
                <input type="email" id="email" name="email" size="35" maxlength="35" placeholder="npr. antglesic@foi.hr"><br>
                <label for="lozinka1">Lozinka: </label>
                <input type="password" id="lozinka1" name="lozinka1" size="15" maxlength="15"  placeholder="Lozinka"><br>
                <label for="lozinka2">Ponovi pozinku: </label>
                <input type="password" id="lozinka2" name="lozinka2" size="15" maxlength="15"  placeholder="Lozinka"><br>
                <label for="koraci1">Koraci prijave: </label><br>
                <input id="koraci1" type="radio" name="koraci" value="1" checked="checked">1
                <input id="koraci2" type="radio" name="koraci" value="2">2<br><br><br>                
                <div class="g-recaptcha" data-sitekey="6Ldvah8UAAAAAJTic4Cx0zONX3Ql0ELigUltwD_H"></div><br>
                <input class="button" type="submit" id="submit" value="Registriraj se" style="background: crimson;"><br>
            </form>
        </section>
        <footer id="footer">
            <figure class="foot1">
                <img src="slike/HTML5.png" alt="HTML 5 validator" width="50" usemap="#mapa2" />
                <map name="mapa2">
                    <area href="https://validator.w3.org/check?uri=http://barka.foi.hr/WebDiP/2016/zadaca_04/antglesic/registracija.php" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
                </map>
                <figcaption>HTML5 validator</figcaption>
            </figure>
            <figure class="foot1">
                <img src="slike/CSS3.png" alt="CSS3 validator" width="50" usemap="#mapa3"/>
                <map name="mapa3">
                    <area href="https://jigsaw.w3.org/css-validator/validator?uri=http://barka.foi.hr/WebDiP/2016/zadaca_04/antglesic/css/antglesic.css" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
                </map>
                <figcaption>CSS3 validator</figcaption>
            </figure>
            <address class="foot2">
                <strong>Kontakt:</strong> <a href="mailto:antglesic@foi.hr">Antonio Glešić</a>
            </address>
            <p class="foot2"><small>&copy;</small> 2017. A. Glešić</p>
        </footer>
    </body>
</html>
