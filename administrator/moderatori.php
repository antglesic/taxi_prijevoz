<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$sql_upit_korisnici = "SELECT * FROM korisnik";

$result = $mysql->selectDB($sql_upit_korisnici);
$select = "<select id='korisnik' name='korisnik'>";
while ($row = mysqli_fetch_array($result)) {
    $prikaz = $row['ime'] . " " . $row['prezime'];
    $select .= "<option value='" . $row['korisnik_id'] . "'>" . $prikaz . "</option>";
}
$select .= "</select>";

$korisnik = "";
$uloga = "";
$zastavica = 0;

foreach ($_POST as $key => $value) {
    $korisnik = $_POST["korisnik"];
    $uloga = $_POST["ovlasti"];
    $zastavica = 1;
}

if ($zastavica == 1) {
    if ($uloga == '0') {
        $sql_script_uloga = "UPDATE korisnik SET uloga = '3' WHERE korisnik_id LIKE '" . $korisnik . "'";
        $mysql->updateDB($sql_script_uloga);
        header("Location: moderatori.php");
        exit;
    }
    if ($uloga == '1') {
        $sql_script_uloga = "UPDATE korisnik SET uloga = '2' WHERE korisnik_id LIKE '" . $korisnik . "'";
        $mysql->updateDB($sql_script_uloga);
        header("Location: moderatori.php");
        exit;
    }
}


$mysql->zatvoriDB();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Ovlasti</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Ovlasti" />
        <meta name="kljucne_rijeci" content="projekt, administrator, podjela_ovlasti" />
        <meta name="datum_izrade" content="03.06.2017." />
        <meta name="autor" content="Antonio Glešić" />
        <link rel="stylesheet" type="text/css" href="css/antglesic.css">
    </head>
    <body>
        <header>
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
                    <a href="moderatori.php" class="active">Ovlasti</a>
                </li>
                <li>
                    <a href="novo_podrucje.php">Novo područje</a>  
                </li>
                <li>
                    <a href="novi_kupon.php">Novi kupon</a>  
                </li>
                <li>
                    <a href="statistika.php">Statistika</a>  
                </li>
                <li>
                    <a href="dnevnik.php">Dnevnik</a>
                </li>
                <li>
                    <a href="otkljucavanje_korisnika.php">Otključavanje</a>
                </li>
                <li>
                    <a href="izgled_admin.php">Izgled</a>  
                </li>
                <li>
                    <a href="index.php?odjava=1">Odjava</a>  
                </li>
            </ul>
        </nav>
        <script language="php"> </script>
        <section>
            <form id="novi" class="novi_proizvod" name="ovlasti" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <h2 class="figcaption">Prazno mjesto</h2>
                <label for="korisnici">Korisnik:</label>
                <p id="korisnici">
                    <?php
                    echo $select;
                    ?>
                </p><br><br>
                <label for="ovlasti">Uloga:</label>
                <select id="ovlasti" name="ovlasti">
                    <option value="0">Registrirani korisnik</option>
                    <option value="1">Moderator</option>
                </select><br><br>
                <input id="button" class="button" type="submit" name="submit" value="Pošalji"><br><br><br>
            </form>
        </section>
        <footer id="footer">
            <figure class="foot1">
                <img src="slike/HTML5.png" alt="HTML 5 validator" width="50" usemap="#mapa2" />
                <map name="mapa2">
                    <area href="https://validator.w3.org/check?uri=http://barka.foi.hr/WebDiP/2016/zadaca_04/antglesic/otkljucavanje_korisnika.php" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
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
        </footer>
    </body>
</html>