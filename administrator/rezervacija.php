<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$podrucje = $_GET["pod"];
$sql_upit_podrucje = "SELECT podrucje.naziv_podrucja, korisnik.ime, korisnik.prezime FROM podrucje, korisnik WHERE podrucje_id LIKE '" . $podrucje . "' AND korisnik.korisnik_id LIKE podrucje.moderator";
$pd = "";
$naziv_podrucja = "";
$ime = "";
$prezime = "";
$rezultat = $mysql->selectDB($sql_upit_podrucje);
while ($row1 = $rezultat->fetch_assoc()) {
    $naziv_podrucja = $row1["naziv_podrucja"];
    $ime = $row1["ime"];
    $prezime = $row1["prezime"];
}
$pd = "Odabrali ste podrucje: " . $naziv_podrucja . "    za koje je zasluzan: " . $ime . " " . $prezime . "<br>";

$sql_upit_ulice = "SELECT * FROM ulica WHERE podrucje LIKE '" . $podrucje . "'";
$result = $mysql->selectDB($sql_upit_ulice);

$select = "<select id='ulica' name='ulica'>";
while ($row = $result->fetch_assoc()) {
    $prikaz = $row['adresa_ulice'];
    $select .= "<option value='" . $row['ulica_id'] . "'>" . $prikaz . "</option>";
}
$select .= "</select>";

$korisnik = Sesija::dajKorisnika();
$ulica = "";
$datum = "";
$vrijeme = "";
$destinacija = "";
$zastavica = 0;
$sql_upit_korisnik = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnik . "'";
$test = $mysql->selectDB($sql_upit_korisnik);
$kor_id = "";
while($row = $test->fetch_assoc()) {
    $kor_id = $row["korisnik_id"];
}

foreach ($_POST as $key => $value) {
    $ulica = $_POST["ulica"];
    $datum = $_POST["datum"];
    $vrijeme = $_POST["vrijeme"];
    $destinacija = $_POST["destinacija"];
    $zastavica = 1;
}

$sql_script_rezervacija = "INSERT INTO `rezervacija`(`ulica`, `datum`, `vrijeme_polaska`, `odrediste`, `korisnik`) VALUES ('" . $ulica . "', '" . $datum . "', '" . $vrijeme . "', '" . $destinacija . "', '" . $kor_id . "')";

if ($zastavica == 1) {
    $rez = $mysql->updateDB($sql_script_rezervacija);
    header("Location: odabir_podrucja.php");
}



$mysql->zatvoriDB();
?>


<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Rezervacija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Popis podrucja" />
        <meta name="kljucne_rijeci" content="projekt, registrirani korisnik, rezervacija" />
        <meta name="datum_izrade" content="02.06.2017." />
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
                    <a href="odabir_podrucja.php" class="active">Rezervacija</a>
                </li>
                <li>
                    <a href="evidencija_bodova.php">Bodovi</a>  
                </li>
                <li>
                    <a href="povratna.php">Povratna informacija</a>  
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
            <form id="novi" name="podrucje" class="novi_proizvod" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <h2 class="figcaption">Prazno mjesto</h2>
                <p>
                    <?php
                    echo $pd;
                    ?>
                </p><br><br>
                <label for="ulica">Ulica:</label>
                <p id="ulice">
                    <?php
                    echo $select;
                    ?>
                </p><br><br>
                <label for="datum">Datum polaska:</label>
                <input id="datum" type="date" name="datum" /><br>
                <label for="vrijeme">Vrijeme polaska:</label>
                <input id="vrijeme" type="time" name="vrijeme" /><br>
                <label for="destinacija">Adresa odredišta</label>
                <input id="odrediste" name="destinacija" type="text" placeholder="Destinacija" /><br>
                <input id="button" class="button" type="submit" name="submit" value="Odaberi"><br><br><br>
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