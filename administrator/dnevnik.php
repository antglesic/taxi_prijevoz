<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$sql_upit_dnevnik = "SELECT dogadaj, vrijeme FROM dnevnik";
$head = "<thead>" . "<tr>" . "<th>Dogadaj</th>" . "<th>Vrijeme nastanka</th>" . "</tr>" . "</thead>";
$table = "";
$rezultat = $mysql->selectDB($sql_upit_dnevnik);
while ($row = mysqli_fetch_array($rezultat)) {
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["dogadaj"] . "</td>" . "<td>" . $row["vrijeme"] . "</td>";
        $table = $table . "</tr>";
    }


$mysql->zatvoriDB();
?>


<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Statistika</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Statistika" />
        <meta name="kljucne_rijeci" content="projekt, statistika" />
        <meta name="datum_izrade" content="28.05.2017." />
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
                    <a href="moderatori.php">Ovlasti</a>
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
                    <a href="dnevnik.php" class="active">Dnevnik</a>
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
        <section>
            <table id="tablica" class="container" class="display" >
                <caption><h1>Dnevnik</h1></caption>
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
            <p>Vrijeme izrade <strong>Popisa proizvoda</strong>: <i>2 sata i 55 minuta</i></p>
            <figure class="foot1">
                <img src="slike/HTML5.png" alt="HTML 5 validator" width="50" usemap="#mapa2" />
                <map name="mapa2">
                    <area href="https://validator.w3.org/check?uri=http://barka.foi.hr/WebDiP/2016/zadaca_02/antglesic/popis_proizvoda.html" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
                </map>
                <figcaption>HTML5 validator</figcaption>
            </figure>
            <figure class="foot1">
                <img src="slike/CSS3.png" alt="CSS3 validator" width="50" usemap="#mapa3"/>
                <map name="mapa3">
                    <area href="https://jigsaw.w3.org/css-validator/validator?uri=http://barka.foi.hr/WebDiP/2016/zadaca_02/antglesic/antglesic.css" shape="rect" alt="pravokutnik" coords="0,0,50,50" target="a" />
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