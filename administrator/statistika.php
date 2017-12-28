<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();


$tip = "";
foreach ($_POST as $key => $value) {
    $tip = $_POST["tip"];
}

$sql_upit_po_korisniku = "SELECT korisnik.ime, korisnik.prezime, SUM(bodovi.broj_skupljenih_bodova), SUM(bodovi.broj_potrosenih_bodova) FROM korisnik, bodovi WHERE korisnik.korisnik_id LIKE bodovi.korisnik GROUP BY bodovi.korisnik";
$sql_upit_po_akciji = "SELECT * FROM akcija";
$sql_upit_po_akciji1 = "SELECT evidencija.akcija, COUNT(*) from evidencija GROUP BY evidencija.akcija";
$head1 = "";
$head2 = "";
$head1 = "<thead>" . "<tr>" . "<th>Ime</th>" . "<th>Prezime</th>" . "<th>Skupljeni bodovi</th>" . "<th>Potroseni bodovi</th>" . "</tr>" . "</thead>";
$head2 = "<thead>" . "<tr>" . "<th>Akcija</th>" . "<th>Broj skupljenih bodova</th>" . "</tr>" . "</thead>";
$table = "";
$zastavica = 0;
if ($tip === '1') {
    $zastavica = 1;
    $rezultat1 = $mysql->selectDB($sql_upit_po_korisniku);
    while ($row = mysqli_fetch_array($rezultat1)) {
        $table = $table . "<tr>";
        $table = $table . "<td>" . $row["ime"] . "</td>" . "<td>" . $row["prezime"] . "</td>" . "<td>" . $row[2] . "</td>" . "<td>" . $row[3] . "</td>";
        $table = $table . "</tr>";
    }
} else {
    $zastavica = 2;
    $rezultat2 = $mysql->selectDB($sql_upit_po_akciji);
    $rezultat3 = $mysql->selectDB($sql_upit_po_akciji1);
    $count1 = 0;
    $count2 = 0;
    $count3 = 0;
    $count4 = 0;
    $count5 = 0;
    while ($row1 = mysqli_fetch_array($rezultat3)) {
        if($row1[0] == '1') $count1 = $row1[1];
        if($row1[0] == '2') $count2 = $row1[1];
        if($row1[0] == '3') $count3 = $row1[1];
        if($row1[0] == '4') $count4 = $row1[1];
        if($row1[0] == '5') $count5 = $row1[1];
    }
    while ($row = mysqli_fetch_array($rezultat2)) {
        $table = $table . "<tr>";
        if ($row[0] == '1') {
            $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td>" . $count1 * $row[2] . "</td>";
        }
        if ($row[0] == '2') {
            $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td>" . $count2 * $row[2] . "</td>";
        }
        if ($row[0] == '3') {
            $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td>" . $count3 * $row[2] . "</td>";
        }
        if($row[0] == '4') {
            $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td>" . $count4 * $row[2] . "</td>";
        }
        if($row[0] == '5') {
            $table = $table . "<td>" . $row["naziv"] . "</td>" . "<td>" . $count5 * $row[2] . "</td>";
        }
        $table = $table . "</tr>";
    }
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
        <script type="text/javascript" src="js/antglesic_jquery.js"></script>
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
                    <a href="statistika.php" class="active">Statistika</a>  
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
        <section>
            <form id="tipovi" name="tipovi" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                <select id="tip" name="tip">
                    <option value="1">Bodovi po korisniku</option>
                    <option value="2">Bodovi po akciji</option>
                </select>
                <input type="submit" id="button" name="submit" value="ODABERI"/><br> 
            </form>
        </section>
        <section>
            <table id="tablica" class="container" class="display" >
                <caption><h1>Statistika</h1></caption>
                <?php
                if ($zastavica === 1)
                    echo $head1;
                if ($zastavica === 2)
                    echo $head2;
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