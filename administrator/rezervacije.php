<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$mod = Sesija::dajKorisnika();

$sql_upit_rezervacije = "SELECT * FROM rezervacija WHERE status IS NULL";
$rezultat = $mysql->selectDB($sql_upit_rezervacije);

$sql_upit_kor = "SELECT ime, prezime FROM korisnik, rezervacija WHERE korisnik.korisnik_id LIKE rezervacija.korisnik";
$korisnik = "";
$kor_id = "";

$sql_upit_ulica = "SELECT adresa_ulice FROM ulica, rezervacija WHERE ulica.ulica_id LIKE rezervacija.ulica";
$ulica = "";
$odrediste = "";


$select = "<select id='rezervacija' name='rezervacija'>";
while ($row = $rezultat->fetch_assoc()) {
    if ($rez = $mysql->selectDB($sql_upit_kor)) {
        while ($row1 = $rez->fetch_assoc()) {
            $korisnik = $row1["ime"] . " " . $row1["prezime"];
        }
    }
    if ($rez2 = $mysql->selectDB($sql_upit_ulica)) {
        while ($row2 = $rez2->fetch_assoc()) {
            $ulica = $row2["adresa_ulice"];
        }
    }
    $kor_id = $row["korisnik"];
    $odrediste = $row["odrediste"];
    $prikaz = $korisnik . " polazi iz " . $ulica . " " . $row["datum"] . " u " . $row["vrijeme_polaska"] . " i putuje za " . $row["odrediste"];
    $select .= "<option value='" . $row['rezervacija_id'] . "'>" . $prikaz . "</option>";
}
$select .= "</select>";


$rezervacija = "";
$status = "";
$zastavica = 0;
foreach ($_POST as $key => $value) {
    $rezervacija = $_POST["rezervacija"];
    $status = $_POST["status"];
    $zastavica = 1;
}


if ($zastavica == 1) {
    if ($status == '1') {
        $sql_script_rez = "UPDATE rezervacija SET status = 1 WHERE rezervacija_id LIKE '" . $rezervacija . "'";
        $sql_script_evidencija = "INSERT INTO `evidencija`(`korisnik`, `akcija`) VALUES ('" . $kor_id . "', '1')";
        $sql_script_bodovi = "INSERT INTO `bodovi`(`korisnik`, `broj_skupljenih_bodova`) VALUES ('" . $kor_id . "', '10')";
        $trenutno_cisto = date('Y-m-d G:i:s');
        $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('" . $trenutno_cisto . "', 'Moderator " . $mod . " je prihvatio rezervaciju pod sifrom " . $rezervacija . "')";
        $sql_script_prijevoz = "INSERT INTO `prijevoz`(`rezervacija`, `odraden`, `opis`) VALUES ('" . $rezervacija . "', '0', '" . $korisnik . " polazi iz " . $ulica . " za " . $odrediste . "')";
        $re1 = $mysql->updateDB($sql_script_rez);
        $re2 = $mysql->updateDB($sql_script_evidencija);
        $re3 = $mysql->updateDB($sql_script_bodovi);
        $re4 = $mysql->updateDB($sql_script_dnevnik);
        $re5 = $mysql->updateDB($sql_script_prijevoz);
    }
    if ($status == '0') {
        $sql_script_rez = "UPDATE rezervacija SET status = 1 WHERE rezervacija_id LIKE '" . $rezervacija . "'";
        $sql_script_evidencija = "INSERT INTO `evidencija`(`korisnik`, `akcija`) VALUES ('" . $korisnik . "', '1')";
        $trenutno_cisto = date('Y-m-d G:i:s');
        $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('" . $trenutno_cisto . "', 'Moderator " . $mod . " je odbio rezervaciju pod sifrom " . $rezervacija . "')";
        $re1 = $mysql->updateDB($sql_script_rez);
        $re2 = $mysql->updateDB($sql_script_evidencija);
        $re4 = $mysql->updateDB($sql_script_dnevnik);
    }
}


$mysql->zatvoriDB();
?>



<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Rezervacije</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Rezervacije" />
        <meta name="kljucne_rijeci" content="projekt, moderator, prihvacanje ili odbijanje rezervacija" />
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
                    <a href="definiranje_kupona.php">Definiranje kupona</a>
                </li>
                <li>
                    <a href="provjera_kupona.php">Provjera kupona</a>  
                </li>
                <li>
                    <a href="izgled.php">Izgled stranice</a>  
                </li>
                <li>
                    <a href="definiranje_ulica.php">Definiranje ulica</a>
                </li>
                <li>
                    <a href="rezervacije.php" class="active">Rezervacije</a>
                </li>
                <li>
                    <a href="prijevoz.php">Prijevoz</a>
                </li>
                <li>
                    <a href="index.php?odjava=1">Odjava</a>
                </li>
            </ul>
        </nav>
        <script language="php"> </script>
        <section>
            <h2 class="figcaption">Prazno mjesto</h2>
            <form id="novi" class="novi_proizvod" method="POST" name="novi_proizvod" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                <label for="kupon">Kupon:</label>
                <p id="rezervacije">
                    <?php
                    echo $select;
                    ?>
                </p><br><br>
                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="0">Neodobreno</option>
                    <option value="1">Prihvaćeno</option>
                </select>
                <input class="button" id="submit" type="submit" value="DEFINIRAJ"><br><br>
            </form>
        </section>
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