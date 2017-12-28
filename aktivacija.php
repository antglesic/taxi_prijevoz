<?php
include("baza.class.php");
include("sesija.class.php");

$mysql = new Baza();
$mysql->spojiDB();
$korime = $_GET["korIme"];
$aktivac_kod = $_GET["key"];
$mode = $_GET["mode"]; //mode=1 registracija | mode=2 prijava
$povratna_informacija = "";
$uloga = "";



if ($mode == 1) {
    $sql_potraga = "SELECT korisnik_id FROM korisnik WHERE korisnicko_ime LIKE " . " '$korime' AND aktivac_kod LIKE " . " '$aktivac_kod' ";
    $result = $mysql->selectDB($sql_potraga);
    if (mysqli_num_rows($result) > 0) {
        $sql_aktivacija = "UPDATE korisnik SET status = '1-aktiviran' WHERE korisnicko_ime='" . $korime . "'";
        $mysql->selectDB($sql_aktivacija);
        $povratna_informacija = "Uspjesno ste aktivirali korisnicki racun!" . "<br>";
        header("Location: prijava.php");
        exit;
    } else {
        $povratna_informacija = "Problem prilikom aktivacije racuna!" . "<br>";
    }
}
if ($mode == 2) {
    $sql_upit_uloga = "SELECT naziv_uloge FROM uloge, korisnik WHERE uloge.uloge_id LIKE korisnik.korisnik_id AND korisnik.korisnicko_ime LIKE " . $korime;
    $sql_potraga = "SELECT korisnik_id FROM korisnik WHERE korisnicko_ime LIKE " . " '$korime' AND aktivac_kod LIKE " . " '$aktivac_kod' ";
    $result = $mysql->selectDB($sql_potraga);
    $results = $mysql->selectDB($sql_upit_uloga);

    while ($row = $results->fetch_assoc()) {
        $uloga = $row["naziv_uloge"];
    }

    if (mysqli_num_rows($result) > 0) {
        $sql_prijava = "UPDATE korisnik SET ulogiran = '" . 1 . "' WHERE korisnicko_ime = '" . $korime . "'";
        $mysql->selectDB($sql_prijava);
        setcookie("foi_a_taxi_prijevoz", $kor_ime, time()+72);
        $trenutno_cisto = date('Y-m-d G:i:s');
        $dogadaj = "Prijavljen korisnik " . $kor_ime;
        $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('$trenutno_cisto','$dogadaj')";
        $rez = $mysql->updateDB($sql_script_dnevnik);
        $povratna_informacija = "Prijavljeni ste!" . "<br>";
        if ($uloga === "Administrator") {
            header("Location: administrator/index.php?odjava=0");
        }
        if ($uloga === "Moderator") {
            header("Location: moderator/index.php?odjava=0");
        }
        if ($uloga === "Registrirani korisnik") {
            header("Location: korisnik/index.php?odjava=0");
        }
    } else {
        $povratna_informacija = "Problem prilikom prijave!" . "<br>";
    }
}


$mysql->zatvoriDB();
?>


<!DOCTYPE HTML>
<html lang="hr">
    <head>
        <title>Aktivacija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Dnevnik" />
        <meta name="kljucne_rijeci" content="zadaća 4, Aktivacija" />
        <meta name="datum_izrade" content="30.04.2017." />
        <meta name="autor" content="Antonio Glešić" />
        <link rel="stylesheet" type="text/css" href="css/antglesic.css">
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
                    <a href="otkljucavanje_korisnika.php">Otključavanje</a>  
                </li>
                <li>
                    <a href="novi_proizvod.php">Novi proizvod</a>  
                </li>
                <li>
                    <a href="dnevnik.php" >Dnevnik</a>  
                </li>
                <li>
                    <a href="aktivacija.php?korIme=asd&key=0&mode=1" class="active">Aktivacija</a>  
                </li>
                <li>
                    <a href="prijava.php">Prijava</a>  
                </li>
                <li>
                    <a href="registracija.php">Registracija</a>  
                </li>
            </ul>
        </nav>
        <p>
            <?php
            if (isset($povratna_informacija)) {
                echo $povratna_informacija;
            }
            ?>
        </p>
        <footer id="footer">
            <figure class="foot1">
                <img src="slike/HTML5.png" alt="HTML 5 validator" width="50" usemap="#mapa2" />
                <map name="mapa2">
                    <area href="https://validator.w3.org/check?uri=http://barka.foi.hr/WebDiP/2016/zadaca_04/antglesic/aktivacija.php" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
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
