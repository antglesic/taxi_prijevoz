<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$mod = Sesija::dajKorisnika();
$sql_upit_moderator = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '" . $mod . "'";
$rezultat = $mysql->selectDB($sql_upit_moderator);
$moderator = "";
while($row = $rezultat->fetch_assoc()) {
    $moderator = $row["korisnik_id"];
}

$sql_upit_podrucja = "SELECT * FROM podrucje";

$result1 = $mysql->selectDB($sql_upit_podrucja);
$selected = "<select id='podrucje' name='podrucje'>";
while ($row = mysqli_fetch_array($result1)) {
    $prikaz = $row['naziv_podrucja'];
    $selected .= "<option value='" . $row['podrucje_id'] . "'>" . $prikaz . "</option>";
}
$selected .= "</select>";


$podrucje = "";
$ulica = "";
foreach($_POST as $key => $value) {
    $podrucje = $_POST["podrucje"];
    $ulica = $_POST["ulica"];
}

$sql_script_ulica = "INSERT INTO ulica(podrucje, adresa_ulice) VALUES ('". $podrucje . "', '" . $ulica . "')";
$rez = $mysql->updateDB($sql_script_ulica);


$mysql->zatvoriDB();
?>

<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Definiranje ulica</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Definiranje ulica" />
        <meta name="kljucne_rijeci" content="projekt, moderator, definiranje_ulica" />
        <meta name="datum_izrade" content="27.05.2017." />
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
                    <a href="definiranje_ulica.php" class="active">Definiranje ulica</a>
                </li>
                <li>
                    <a href="rezervacije.php">Rezervacije</a>
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
                <label for="podrucje">Područje:</label>
                <p id="podrucje">
                    <?php
                    echo $selected;
                    ?>
                </p><br>
                <label for="ulica">Ulica:</label>
                <input type="text" id="ulica" name="ulica" size="15" maxlength="15" placeholder="Ulica" required="required"><br><br>

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