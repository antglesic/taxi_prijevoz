<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$sql_upit_podrucja = "SELECT * FROM podrucje";

$result = $mysql->selectDB($sql_upit_podrucja);
$select = "<select id='podrucje' name='podrucje'>";
while ($row = $result->fetch_assoc()) {
    $prikaz = $row['naziv_podrucja'];
    $select .= "<option value='" . $row['podrucje_id'] . "'>" . $prikaz . "</option>";
}
$select .= "</select>";
$podrucje = "";
$zastavica = 0;
foreach ($_POST as $key => $value) {
    $podrucje = $_POST["podrucje"];
    $zastavica = 1;
}
$link = "rezervacija.php?pod=".$podrucje;
if($zastavica == 1) {
    header("Location: $link");
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
        <meta name="kljucne_rijeci" content="projekt, registrirani korisnik, odabir_podrucja" />
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
            <form id="novi" class="novi_proizvod" name="podrucje" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <h2 class="figcaption">Prazno mjesto</h2>
                <label for="podrucje">Područje:</label>
                <p id="podrucja">
                    <?php
                    echo $select;
                    ?>
                </p><br><br>
                <input id="button" class="button" type="submit" name="submit" value="Odaberi"><br>
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