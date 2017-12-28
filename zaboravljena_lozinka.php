<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$greska = "";
$povratna_inf = "";
$zastavica = 0;
foreach ($_POST as $key => $value) {
    $korime = $_POST["korIme"];
    $sql_upit_korisnika = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime LIKE'" . $korime . "'";
    $test = $mysql->selectDB($sql_upit_korisnika);
    if (empty($test)) {
        $greska = "Korisnicko ime ne postoji!" . "<br>";
    } else {
        $greska = "";
    }


    $sql_upit_lozinka = "SELECT lozinka FROM korisnik WHERE korisnicko_ime LIKE '$korime'";
    $rezultat = $mysql->selectDB($sql_upit_lozinka);
    $lozinka = "";
    while ($row = $rezultat->fetch_assoc()) {
        $lozinka = $row["lozinka"];
    }
    $salt = sha1(time());
    $nova_lozinka = sha1($salt . "--" . $lozinka);
    $sql_script_new_pw = "UPDATE korisnik SET lozinka = '" . $nova_lozinka . "' WHERE korisnicko_ime LIKE '" . $korime . "'";
    $new = $mysql->updateDB($sql_script_new_pw);

    $sql_upit_mail = "SELECT email FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
    $rez = $mysql->selectDB($sql_upit_mail);
    $mail = "";
    while ($row = $rez->fetch_assoc()) {
        $mail = $row["email"];
    }
    $mail_to = $mail;
    $mail_from = "From: WebDiP_2017@foi.hr";
    $mail_subject = "Nova Lozinka";
    $mail_body = "Vaša nova lozinka izgleda ovako: " . $nova_lozinka;

    if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
        $povratna_inf = "Vaša nova lozinka vam je poslana na vaš E-mail!" . "<br>";
        $zastavica = 1;
    } else {
        $povratna_inf = "Problem prilikom generiranja nove lozinke!" . "<br>";
    }
}

if($zastavica == 1) {
    header("Location: prijava.php");
}

$mysql->zatvoriDB();
?>



<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Prijava</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Prijava" />
        <meta name="kljucne_rijeci" content="projekt, prijava, taxi-prijevoz" />
        <meta name="datum_izrade" content="25.05.2017." />
        <meta name="autor" content="Antonio Glešić" />
        <link rel="stylesheet" type="text/css" href="css/antglesic.css">
    </head>
    <body id="login-page">
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
                    <a href="prijava.php" class="active">Prijava</a>  
                </li>
                <li>
                    <a href="registracija.php">Registracija</a>  
                </li>
                <li>
                    <a href="o_autoru.html">O Autoru</a>  
                </li>
                <li>
                    <a href="dokumentacija.html">Dokumentacija</a>  
                </li>
            </ul>
        </nav>
        <script language="php"> </script>
        <section>
            <p>
                <?php
                if (isset($greska)) {
                    echo $greska;
                }
                if (isset($povratna_inf)) {
                    echo $povratna_inf;
                }
                ?>
            </p>
            <h2 class="figcaption">Prazno mjesto</h2>
            <form id="prijava" name="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <label for="korIme">Korisničko Ime</label>
                <input type="text" id="korIme" name="korIme" placeholder="Korisničko ime" maxlength="20" size="25"><br>

                <input id="button" type="submit" name="submit" value="UNESI"><br>
            </form>
        </section>
        <footer id="footer">
            <figure class="foot1">
                <img src="slike/HTML5.png" alt="HTML 5 validator" width="50" usemap="#mapa2" />
                <map name="mapa2">
                    <area href="https://validator.w3.org/check?uri=http://barka.foi.hr/WebDiP/2016/zadaca_04/antglesic/prijava.php" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
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