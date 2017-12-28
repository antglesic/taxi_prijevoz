<?php
include("baza.class.php");
include("sesija.class.php");

if (Sesija::dajKorisnika() != NULL) {  //provjera postoji li sesija
    //echo "Sesija postoji!" . "<br>";
    header('Location: index.php?odjava=0');
}

$mysql = new BAZA();
$mysql->spojiDB();

/*
  var_dump($_GET);
  if(isset($_GET("kod"))) {
  echo $_GET["kod"];
  }
 */
$korime = "";
$lozinka = "";
$greska = "";
$povratna_informacija1 = "";
$povratna_informacija2 = "";
$povratna_informacija = "";
$nije_aktivan = "";
$glavna_zastava = 0;
$zastavica = 0;

foreach ($_POST as $key => $value) {
    $korime = $_POST["korIme"];
    $lozinka = $_POST["lozinka"];
}

$sql_upit_aktivan = "SELECT status FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
$sql_upit_koraci = "SELECT koraci FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
$sql_upit_lozinka = "SELECT lozinka FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
$rezultat123 = $mysql->selectDB($sql_upit_aktivan);
$status = "";
while ($row = $rezultat123->fetch_assoc()) {
    $status = $row["status"];
}
if ($status === "0-neaktivan") {
    $glavna_zastava = 1;
    $nije_aktivan = "Nije aktivan korisnik, morate ga aktivirati!" . "<br>";
}
if ($status === "0-zakljucan") {
    $glavna_zastava = 1;
    $nije_aktivan = "Racun je zakljucan, morate zatraziti da bude otkljucan!" . "<br>";
}
$result1 = $mysql->selectDB($sql_upit_lozinka);
$greska = "";
if ($glavna_zastava != 1) {
    if (mysqli_num_rows($result1) > 0) {
        $greska = "";
        $rezultat = $mysql->selectDB($sql_upit_koraci);
        while ($row = $rezultat->fetch_assoc()) {
            $rez = $row["koraci"];
        }
        if ($rez == '2') {
            $email = "";
            $aktivac_kod;
            $sql_upit_uspjesno = "SELECT korisnik_id FROM korisnik WHERE lozinka LIKE '" . $lozinka . "'";
            $sql_upit_pogresno = "SELECT pogresno FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
            $sql_upit_mail = "SELECT email FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
            $sql_upit_aktiv = "SELECT aktivac_kod FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
            $mail_rezultat = $mysql->selectDB($sql_upit_mail);
            $aktivac_rez = $mysql->selectDB($sql_upit_aktiv);
            while ($row = $mail_rezultat->fetch_assoc()) {
                $email = $row["email"];
            }
            while ($red = $aktivac_rez->fetch_assoc()) {
                $aktivac_kod = $red["aktivac_kod"];
            }
            $result2 = $mysql->selectDB($sql_upit_uspjesno);

            if (mysqli_num_rows($result2) > 0) {
                $sol = sha1($korime);
                $mail_to = $email;   //$_POST["email"];
                $mail_from = "From: WebDiP_2017@foi.hr";
                $mail_subject = "Prijava";      //$_POST["subjekt"];
                $link = "http://barka.foi.hr/WebDiP/2016/zadaca_03/antglesic/aktivacija.php" . "?korIme=" . $_POST["korIme"] . "&key=" . $aktivac_kod . "&mode=2";

                $mail_body = "Pritisnite na iduci link kako biste se prijavili na vas racun: <a href='" . $link . "'>" . $link . "</a>";
                if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                    $povratna_informacija1 = "Uspjesno ste unijeli podatke za prijavu!" . "<br>";
                    $povratna_informacija2 = "Pritisnite na iduci link kako biste se prijavili na vas racun: <a href='" . $link . "'>" . $link . "</a>";
                    $greska = "";
                } else {
                    $povratna_informacija1 = "Problem kod slanja jedinstvenog koda prijave!" . "<br>";
                    $povratna_informacija2 = "";
                }
            } else {
                $sql_upit_pogresno = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
                $result3 = $mysql->selectDB($sql_upit_pogresno);
                while ($row = $result3->fetch_assoc()) {
                    $pogresno = $row["pogresna"];
                }
                if ($pogresno < 3) {
                    $pogresno++;
                    $sql_script_pogresno = "UPDATE korisnik SET pogresna = '" . $pogresno . "' WHERE korisnicko_ime='" . $korime . "'";
                    $mysql->selectDB($sql_script_pogresno);
                    $zastavica = 0;
                } else {
                    $sql_script_zakljucan = "UPDATE korisnik SET status = '0-zakljucan' WHERE korisnicko_ime='" . $korime . "'";
                    $mysql->selectDB($sql_script_zakljucan);
                    $zastavica = 1;
                }
                if ($zastavica == 0) {
                    $greska = "Neuspjesna prijava broj (" . $pogresno . ")!" . "<br>";
                } else {
                    $greska = "Korisnicki racun je zakljucan zbog previse (3) neuspjelih pokusaja!" . "<br>";
                    
                }
            }
        } else {
            $sql_upit_uspjesno = "SELECT korisnik_id FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "' AND lozinka LIKE '" . $lozinka . "'";
            $sql_upit_pogresno = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE " . "'$korime'";
            $result2 = $mysql->selectDB($sql_upit_uspjesno);
            $kor_id = "";
            while($row = $result2->fetch_assoc()) {
                $kor_id = $row["korisnik_id"];
            }
            if (mysqli_num_rows($result2) > 0) {
                $sql_script_prijava = "UPDATE korisnik SET ulogiran = '1' WHERE korisnicko_ime = '" . $korime . "'";
                $mysql->selectDB($sql_script_prijava);
                $sql_upit_admin = "SELECT naziv_uloge, korisnicko_ime, pogresna FROM korisnik, uloge WHERE korisnik.uloga = uloge.uloge_id AND korisnik.korisnicko_ime = '" . $_POST["korIme"] . "'";
                $rezultati = $mysql->selectDB($sql_upit_admin);
                //var_dump($rezultati);
                while ($red = $rezultati->fetch_assoc()) {
                    $uloga = $red["naziv_uloge"];
                    $kor_ime = $red["korisnicko_ime"];
                    $broj_gresaka = $red["pogresna"];
                    Sesija::kreirajKorisnika($kor_ime, $uloga, $broj_gresaka);
                }
                $povratna_informacija = "Prijavljeni ste!" . "<br>";
                setcookie("foi_a_taxi_prijevoz", $kor_ime, time()+72);
                $trenutno_cisto = date('Y-m-d G:i:s');
                $dogadaj = "Prijavljen korisnik " . $kor_ime;
                $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('$trenutno_cisto','$dogadaj')";
                $rez = $mysql->updateDB($sql_script_dnevnik);
                $sql_script_evidencija = "INSERT INTO `evidencija`(`korisnik`, `akcija`) VALUES ('" . $kor_id . "','5')";
                $sql_script_bodovi = "INSERT INTO `bodovi`(`korisnik`, `broj_skupljenih_bodova`, `broj_potrosenih_bodova`) VALUES ('" . $kor_id . "', '7', '0')";
                $reze = $mysql->updateDB($sql_script_evidencija);
                $rezeze = $mysql->updateDB($sql_script_bodovi);
                if ($uloga === "Administrator") {
                    header("Location: administrator/index.php?odjava=0");
                }
                if ($uloga === "Moderator") {
                    header("Location: moderator/index.php?odjava=0");
                }
                if ($uloga === "Registrirani korisnik") {
                    header("Location: korisnik/index.php?odjava=0");
                }
                $greska = "";
            } else {
                $sql_upit_pogresno = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korime . "'";
                $result3 = $mysql->selectDB($sql_upit_pogresno);
                while ($row = $result3->fetch_assoc()) {
                    $rez1 = $row["pogresna"];
                }
                if ($rez1 < 3) {
                    $rez1++;
                    $sql_script_pogresno = "UPDATE korisnik SET pogresna = '" . $rez1 . "' WHERE korisnicko_ime='" . $korime . "'";
                    $mysql->selectDB($sql_script_pogresno);
                } else {
                    $sql_script_zakljucan = "UPDATE korisnik SET status = '0-zakljucan' WHERE korisnicko_ime='" . $korime . "'";
                    $mysql->selectDB($sql_script_zakljucan);
                }
                $greska = "Neuspjesna prijava broj (" . $rez1 . ")!" . "<br>";
            }
        }
    }
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
            <p id="greske">
                <?php
                if (isset($greska)) {
                    echo $greska;
                }
                if (isset($povratna_informacija)) {
                    echo $povratna_informacija;
                }
                if (isset($povratna_informacija1)) {
                    echo $povratna_informacija1;
                }
                if (isset($povratna_informacija2)) {
                    echo $povratna_informacija2;
                }
                if (isset($nije_aktivan)) {
                    echo $nije_aktivan;
                }
                ?>
            </p>
            <h2 class="figcaption">Prazno mjesto</h2>
            <form id="prijava" name="prijava" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <label for="korIme">Korisničko Ime</label>
                <input type="text" id="korIme" name="korIme" placeholder="Korisničko ime" maxlength="20" size="25"><br>
                <label for="lozinka">Lozinka</label>
                <input type="password" id="lozinka" name="lozinka" placeholder="Lozinka"><br>
                <label for="zapamti_me">Zapamti</label><br>
                <input id="zapamti_me" type="radio" name="zapamti_me" value="da" checked="checked">DA
                <input id="radio2" type="radio" name="zapamti_me" value="ne">NE<br><br><br>
                <input id="button" type="submit" name="submit" value="Prijava"><br>
                <a href="registracija.php">Registracija</a>
                <a href="zaboravljena_lozinka.php">Zaboravljena lozinka</a>
            </form>
            <p>
                <?php
                echo "Primjer ispravnog korisnickog imena: aglesic" . "<br>";
                echo "Primjer ispravne lozinke: aaAA1" . "<br>";
                ?>
            </p>
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
            <figure>
                <img class="vrh" src="slike/top-button.png" alt="back_to_top" width="35" height="35" usemap="#mapa4"/>
                <map name="mapa4">
                    <area href="#top" shape="circle" alt="krug" coords="18,17,16"/>
                </map>
                <figcaption class="figcaption">Povratak na vrh</figcaption>
            </figure>
        </footer>
    </body>
</html>