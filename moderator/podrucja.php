<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();


$sql_upit_podrucja = "SELECT * FROM podrucje";

$result = $mysql->selectDB($sql_upit_podrucja);
$select = "<select id='podrucje' name='podrucje'>";
while ($row = mysqli_fetch_array($result)) {
    $prikaz = $row['naziv_podrucja'];
    $select .= "<option value='" . $row['moderator'] . "'>" . $prikaz . "</option>";
}
$select .= "</select>";
$podrucje = "";
foreach ($_POST as $key => $value) {
    $podrucje = $_POST["podrucje"];
}

$sql_upit_moderatori = "SELECT ime, prezime FROM korisnik WHERE korisnik_id LIKE '" . $podrucje . "'";
$moderatori = $mysql->selectDB($sql_upit_moderatori);

$head = "<thead>" . "<tr>" . "<th>Ime moderatora</th>" . "<th>Prezime moderatora</th>" . "</tr>" . "</thead>";
$table = "";
while ($row = $moderatori->fetch_assoc()) {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $row["ime"] . "</td>" . "<td>" . $row["prezime"] . "</td>";
    $table = $table . "</tr>";
}

$mysql->zatvoriDB();
?>

<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Popis područja</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Popis podrucja" />
        <meta name="kljucne_rijeci" content="projekt, popis_podrucja" />
        <meta name="datum_izrade" content="25.05.2017." />
        <meta name="autor" content="Antonio Glešić" />
        <link rel="stylesheet" type="text/css" href="css/antglesic.css">
        <!-- jQuery lib -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

        <!-- datatable lib -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>

        <!-- jqueryUI -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="jquery/antglesic_jquery.js"></script>
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
                    <a href="index_kor.php?odjava=0">Korisničke funkcionalnosti</a>
                </li>
                <li>
                    <a href="index_mod.php?odjava=0">Funkcionalnosti moderatora</a>  
                </li>
                <li>
                    <a href="podrucja.php" class="active">Popis područja</a>
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
        <script language="php"> </script>
        <section>
            <form id="novi" class="novi_proizvod" name="podrucje" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <h2 class="figcaption">Prazno mjesto</h2>
                <p id="podrucja">
                    <?php
                    echo $select;
                    ?>
                </p>
                <input id="button" class="button" type="submit" name="submit" value="Odaberi"><br>
            </form>
            <table id="tablica" class="container" class="display">
                <caption><h1>Moderator</h1></caption>
                <?php
                echo $head;
                ?>
                <tbody>
                    <?php
                    echo $table;
                    ?>
                </tbody>
            </table>

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

