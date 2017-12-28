<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$korisnicko_ime = Sesija::dajKorisnika();
$kor_id = "";
$sql_upit_kor_id = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $korisnicko_ime . "'";
$rezultat1 = $mysql->selectDB($sql_upit_kor_id);
while ($row = $rezultat1->fetch_assoc()) {
    $kor_id = $row["korisnik_id"];
}

$sql_upit_prijevoz = "SELECT * FROM prijevoz WHERE prijevoznik LIKE '" . $kor_id . "'";
$rezultat2 = $mysql->selectDB($sql_upit_prijevoz);

$select = "<select id='prijevoz' name='prijevoz'>";

$prikaz = "";
while($row = $rezultat2->fetch_assoc()) {
    $prikaz = $row["opis"];
    $select .= "<option value='" . $row["prijevoz_id"]. "'>" . $prikaz . "</option>";
}
$select .= "</select>";

$prijevoz = "";
$status = "";
$zastavica = 0;
foreach($_POST as $key => $value) {
    $prijevoz = $_POST["prijevoz"];
    $status = $_POST["status"];
    if($status != '0') {
        $zastavica = 1;
    }
}
if($zastavica == 1) {
    $sql_script_prijevoz = "UPDATE prijevoz SET odraden = '" . $status . "' WHERE prijevoz_id LIKE '" . $prijevoz . "'";
    $trenutno_cisto = date('Y-m-d G:i:s');
    $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('" . $trenutno_cisto . "', 'Moderator " . $korisnicko_ime . " je označio prijevoz " . $prijevoz . " kao odrađen')";
    $rez1 = $mysql->updateDB($sql_script_prijevoz);
    $rez2 = $mysql->updateDB($sql_script_dnevnik);
}
else {
    $sql_script_prijevoz = "UPDATE prijevoz SET status = '" . $status . "' WHERE prijevoz_id LIKE '" . $prijevoz . "'";
    $trenutno_cisto = date('Y-m-d G:i:s');
    $sql_script_dnevnik = "INSERT INTO `dnevnik`(`vrijeme`, `dogadaj`) VALUES ('" . $trenutno_cisto . "', 'Moderator " . $korisnicko_ime . " je označio prijevoz " . $prijevoz . " kao neodrađen')";
    $rez1 = $mysql->updateDB($sql_script_prijevoz);
    $rez2 = $mysql->updateDB($sql_script_dnevnik);
}


$mysql->zatvoriDB();
?>

<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Prijevoz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Prijevoz" />
        <meta name="kljucne_rijeci" content="projekt, moderator, dovrsen-nedovrsen prijevoz" />
        <meta name="datum_izrade" content="03.06.2017." />
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
                    <a href="rezervacije.php">Rezervacije</a>
                </li>
                <li>
                    <a href="prijevoz.php" class="active">Prijevoz</a>
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
                <label for="prijevoz">Prijevoz:</label>
                <p id="prijevozi">
                    <?php
                    echo $select;
                    ?>
                </p><br><br>
                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="0">Nedovršen</option>
                    <option value="1">Odrađen</option>
                </select>
                <input class="button" id="submit" type="submit" value="UNESI"><br><br>
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
