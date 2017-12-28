<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$korisnik = Sesija::dajKorisnika();
$sql_upit_kor = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnik . "'";
$rez1 = $mysql->selectDB($sql_upit_kor);
$kor_id = "";
while ($row = $rez1->fetch_assoc()) {
    $kor_id = $row["korisnik_id"];
}

$sql_upit_prijevoz = "SELECT prijevoz.prijevoz_id, prijevoz.opis, rezervacija.korisnik, povratna_informacija.tekst FROM prijevoz, rezervacija, povratna_informacija WHERE prijevoz.odraden LIKE '1' AND rezervacija.korisnik LIKE '" . $kor_id . "' AND povratna_informacija.tekst IS NULL";
$rez2 = $mysql->selectDB($sql_upit_prijevoz);

$select = "<select id='prijevoz' name='prijevoz'>";
while ($row = $rez2->fetch_assoc()) {
    $prikaz = $row['opis'];
    $select .= "<option value='" . $row['prijevoz_id'] . "'>" . $prikaz . "</option>";
}
$select .= "</select>";

$prijevoz = "";
$povratna = "";
$zastavica = 0;
foreach ($_POST as $key => $value) {
    $zastavica = 1;
    $prijevoz = $_POST["prijevoz"];
    $povratna = $_POST["povratna"];
}

if ($zastavica == 1) {
    $sql_script_povratna = "INSERT INTO `povratna_informacija`(`korisnik`, `prijevoz`, `tekst`) VALUES ('" . $kor_id . "', '" . $prijevoz . "', '" . $povratna . "')";
    $trenutno_cisto = date('Y-m-d G:i:s');
    $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('" . $trenutno_cisto . "', 'Korisnik " . $korisnik . " je unio povratnu informaciju za prijevoz " . $prijevoz . "')";
    $sql_script_evidencija = "INSERT INTO `evidencija`(`korisnik`, `akcija`) VALUES ('" . $kor_id . "', '4')";
    $sql_script_bodovi = "INSERT INTO `bodovi`(`korisnik`, `broj_skupljenih_bodova`) VALUES ('" . $kor_id . "', '7')";
    $re1 = $mysql->updateDB($sql_script_povratna);
    $re2 = $mysql->updateDB($sql_script_dnevnik);
    $re3 = $mysql->updateDB($sql_script_evidencija);
    $re4 = $mysql->updateDB($sql_script_bodovi);
}


$mysql->zatvoriDB();
?>

<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Prijevoz - povratna</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Prijevoz povratna informacija" />
        <meta name="kljucne_rijeci" content="projekt, registrirani korisnik, prijevoz - povratna informacija" />
        <meta name="datum_izrade" content="04.06.2017." />
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
                    <a href="index.php?odjava=0">Naslovnica</a>  
                </li>
                <li>
                    <a href="odabir_podrucja.php">Rezervacija</a>
                </li>
                <li>
                    <a href="evidencija_bodova.php">Bodovi</a>  
                </li>
                <li>
                    <a href="povratna.php" class="active">Povratna informacija</a>  
                </li>
                <li>
                    <a href="kuponi.php">Kuponi</a>
                </li>
                <li>
                    <a href="index.php?odjava=1">Odjava</a>
                </li>
            </ul>
        </nav>
        <script language="php"> </script>
        <section>
            <form id="novi" class="novi_proizvod" name="podrucje" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <h2 class="figcaption">Prazno mjesto</h2>
                <label for="podrucje">Područje:</label>
                <p id="prijevozi">
                    <?php
                    echo $select;
                    ?>
                </p><br><br>
                <label for="povratna">Povratna informacija:</label>
                <input id="povratna" name="povratna" type="text" /><br><br>
                <input id="button" class="button" type="submit" name="submit" value="POŠALJI"><br>
            </form>
        </section>
        <footer id="footer">
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