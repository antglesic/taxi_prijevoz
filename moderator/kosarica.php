<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$korisnicko_ime = Sesija::dajKorisnika();
$sql_upit_kor = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnicko_ime . "'";
$rez1 = $mysql->selectDB($sql_upit_kor);
$kor_id = "";
while ($row = $rez1->fetch_assoc()) {
    $kor_id = $row["korisnik_id"];
}

$sql_upit_po_korisniku = "SELECT korisnik.ime, korisnik.prezime, SUM(bodovi.broj_skupljenih_bodova), SUM(bodovi.broj_potrosenih_bodova) FROM korisnik, bodovi WHERE korisnik.korisnik_id LIKE bodovi.korisnik AND korisnik.korisnik_id LIKE '" . $kor_id . "' GROUP BY bodovi.korisnik";
$rezultat2 = $mysql->selectDB($sql_upit_po_korisniku);
$skupljeni_bodovi = "";
$potroseni_bodovi = "";
while ($row = mysqli_fetch_array($rezultat2)) {
    $skupljeni_bodovi = $row[2];
    $potroseni_bodovi = $row[3];
}
$trenutni_bodovi = $skupljeni_bodovi - $potroseni_bodovi;

$sql_upit_kosarica = "SELECT kupon.kupon_id, kupon.naziv, kupon.slika, kupon.bodovi FROM kupon, kosarica WHERE kosarica.korisnik LIKE '" . $kor_id . "' AND kupon.kupon_id LIKE kosarica.kupon";
$rez2 = $mysql->selectDB($sql_upit_kosarica);

$kupljeni_kupon = "";
$zastavica = 0;
$bodovi_potroseni = "";
foreach ($_POST as $key => $value) {
    $zastavica = 1;
    while ($row = $rez2->fetch_assoc()) {
        if ($_POST[$row["naziv"]] == '1') {
            $kupljeni_kupon = $row["kupon_id"];
            $bodovi_potroseni = $row["bodovi"];
        }
        if ($_POST[$row["naziv"]] == '2') {
            $kupljeni_kupon = $row["kupon_id"];
            $zastavica = 2;
        }
    }
}
$slano_vrime = sha1(date("F j, Y, g:i a"));
$slano_korime = sha1($kor_id);
$gen_kod = sha1($slano_vrime . "--" . $slano_korime);

if ($zastavica == 1) {
    $trenutno_cisto = date('Y-m-d G:i:s');
    $sql_script_evidencija = "INSERT INTO `evidencija`(`korisnik`, `akcija`) VALUES ('" . $kor_id . "', '2')";
    $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('" . $trenutno_cisto . "', 'Korisnik " . $korisnicko_ime . " je kupio kupon " . $kupljeni_kupon . "')";
    $sql_script_bodovi1 = "INSERT INTO `bodovi`(`korisnik`,`broj_potrosenih_bodova`) VALUES ('" . $kor_id . "', '" . $bodovi_potroseni . "')";
    $sql_script_bodovi2 = "INSERT INTO `bodovi`(`korisnik`,`broj_skupljenih_bodova`) VALUES ('" . $kor_id . "', '10')";
    $sql_script_prazni_kosaricu = "DELETE FROM `kosarica` WHERE korisnik LIKE '" . $kor_id . "' AND kupon LIKE '" . $kupljeni_kupon . "'";
    $sql_script_gen_kod = "UPDATE `kupon` SET generiran_kod = '" . $gen_kod . "' WHERE kupon_id LIKE '" . $kupljeni_kupon . "'";
    $rez5 = $mysql->updateDB($sql_script_evidencija);
    $rez6 = $mysql->updateDB($sql_script_bodovi1);
    $rez7 = $mysql->updateDB($sql_script_bodovi2);
    $rez8 = $mysql->updateDB($sql_script_dnevnik);
    $rez9 = $mysql->updateDB($sql_script_prazni_kosaricu);
    $rez10 = $mysql->updateDB($sql_script_gen_kod);
}
if ($zastavica == 2) {
    $trenutno_cisto = date('Y-m-d G:i:s');
    $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('" . $trenutno_cisto . "', 'Korisnik " . $korisnicko_ime . " je izbacio kupon " . $kupljeni_kupon . " iz košarice')";
    $sql_script_prazni_kosaricu = "DELETE FROM `kosarica` WHERE korisnik LIKE '" . $kor_id . "' AND kupon LIKE '" . $kupljeni_kupon . "'";
    $rez10 = $mysql->updateDB($sql_script_dnevnik);
    $rez11 = $mysql->updateDB($sql_script_prazni_kosaricu);
}


$mysql->zatvoriDB();
?>

<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Košarica</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Prijevoz povratna informacija" />
        <meta name="kljucne_rijeci" content="projekt, registrirani korisnik, košarica kupona" />
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
                    <a href="povratna.php">Povratna informacija</a>  
                </li>
                <li>
                    <a href="kuponi.php" class="active">Kuponi</a>
                </li>
                <li>
                    <a href="index.php?odjava=1">Odjava</a>
                </li>
            </ul>
        </nav>
        <script language="php"> </script>
        <section>
            <h1>KOŠARICA</h1>
            <form id="novi" class="novi_proizvod" name="podrucje" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <h2 class="figcaption">Prazno mjesto</h2>
                <label for="podrucje">Kupon:</label>
                <p id="kuponi">
                    <?php
                    while ($row = $rez2->fetch_assoc()) {
                        echo "Cijena: " . $row["bodovi"] . " bodova" . "<br>";
                        echo "<img src='slike/" . $row["slika"] . "' alt='" . $row["slika"] . "' width=" . 100 . " height=" . 150 . " />" . "<br><br>";
                        echo "<select id=kupon name='" . $row["naziv"] . "' >";
                        echo "<option value='0'>Ne kupi</option>";
                        echo "<option value='1'>Kupi</option>";
                        echo "<option value='2'>Izbaci iz kosarice</option>";
                        echo "</select>" . "<br><br><br>";
                    }
                    ?>
                </p><br><br>
                <input id="button" class="button" type="submit" name="submit" value="POTVRDI"><br><br><br>
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