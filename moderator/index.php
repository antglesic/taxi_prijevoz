<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

Sesija::kreirajSesiju();
if (isset($_SESSION["korisnik"])) {
    //echo "Sesija postoji!"."<br>";
    if ($_SESSION["uloga"] === "Registrirani korisnik") {
        header("Location: ../korisnik/index.php?odjava=0");
    }
    //if ($_SESSION["uloga"] === "Moderator") {
    //    header("Location: moderator/index.php?odjava=0");
    //}
    if ($_SESSION["uloga"] === "Administrator") {
        header("Location: ../administrator/index.php?odjava=0");
    }
}
$odjava = $_GET["odjava"];

if ($odjava == 1) {
    $korime = $_SESSION["korisnik"];
    Sesija::obrisiSesiju();
    $sql_script_odjava = "UPDATE korisnik SET ulogiran = '0' WHERE korisnicko_ime = '" . $korime . "'";
    $mysql->updateDB($sql_script_odjava);
    $trenutno_cisto = date('Y-m-d G:i:s');
    $dogadaj = "Odjavljen korisnik " . $korime;
    $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('$trenutno_cisto','$dogadaj')";
    $rez = $mysql->updateDB($sql_script_dnevnik);
    echo "Uspjesna odjava!" . "<br>";
    header("Location: ../index.php?odjava=0");
}


$mysql->zatvoriDB();


?>


<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Naslovnica</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Naslovnica" />
        <meta name="kljucne_rijeci" content="projekt, moderator, početna" />
        <meta name="datum_izrade" content="31.05.2017." />
        <meta name="autor" content="Antonio Glešić" />
        <link rel="stylesheet" type="text/css" href="css/antglesic.css">
    </head>
    <body>
        <header id="top">
            <figure class="header">
                <img src="slike/logo.png" width="400" height="100" alt="LOGO" usemap="#mapa1"/>
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
                    <a href="index.php?odjava=0" class="active">Naslovnica</a>  
                </li>
                <li>
                    <a href="index_kor.php?odjava=0">Funkcije korisnika</a>
                </li>
                <li>
                    <a href="index_mod.php?odjava=0">Mod tools</a>  
                </li>
                <li>
                    <a href="podrucja.php">Popis područja</a>
                </li>
                <li>
                    <a href="o_autoru.html">O autoru</a>
                </li>
                <li>
                    <a href="dokumentacija.html">Dokumentacija</a>
                </li>
                <li>
                    <a href="index.php?odjava=1">Odjava</a>
                </li>
            </ul>
        </nav>
        <h1>Dobrodošli na FOI A-Taxi-Prijevoz</h1>
        <center><img src="slike/foi_taxi.jpg" alt="foi_taxi_logo" width="450" height="375" style="align-content: center; box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);"></center>
        <footer id="footer">
            <p>Vrijeme izrade <strong>Početne stranice</strong>: <i>3 sata i 25 minuta</i></p>
            <figure class="foot1">
                <img src="slike/HTML5.png" alt="HTML 5 validator" width="50" usemap="#mapa2" />
                <map name="mapa2">
                    <area href="https://validator.w3.org/check?uri=http://barka.foi.hr/WebDiP/2016/zadaca_01/antglesic/index.html" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
                </map>
                <figcaption>HTML5 validator</figcaption>
            </figure>
            <figure class="foot1">
                <img src="slike/CSS3.png" alt="CSS3 validator" width="50" usemap="#mapa3"/>
                <map name="mapa3">
                    <area href="https://jigsaw.w3.org/css-validator/validator?uri=http://barka.foi.hr/WebDiP/2016/zadaca_01/antglesic/antglesic.css" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
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
