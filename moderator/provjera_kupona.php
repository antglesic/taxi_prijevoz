<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$greska_kod = "";
$greska_datum = "";

function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    
    $interval = date_diff($datetime1, $datetime2);
    
    return $interval->format($differenceFormat);
    
}

$sql_upit_kuponi = "SELECT * FROM kupon";

$result = $mysql->selectDB($sql_upit_kuponi);
$select = "<select id='kupon' name='kupon'>";
while ($row = mysqli_fetch_array($result)) {
    $prikaz = $row['naziv'];
    $select .= "<option value='" . $row['kupon_id'] . "'>" . $prikaz . "</option>";
}
$select .= "</select>";

$kupon = "";
$kod = "";
$zastavica = 0;
foreach($_POST as $key => $value) {
    $kupon = $_POST["kupon"];
    $kod = $_POST["kod"];
    $zastavica = 1;
}

$trenutno = date("Y-m-d H:i:s");
$od = "";
$do = "";
$novi_od = "";
$novi_do = "";
if($zastavica == 1) {
    $sql_upit_kupon = "SELECT * FROM kupon WHERE kupon_id LIKE '" . $kupon . "'";
    $rez = $mysql->selectDB($sql_upit_kupon);
    $od = "";
    $do = "";
    $gen_kod = "";
    while($row = $rez->fetch_assoc()) {
        $od = $row["aktivanOd"];
        $do = $row["aktivanDo"];
        $gen_kod = $row["generiran_kod"];
    }
    if($kod != $gen_kod) {
        $greska_kod = "Netočan kod!"."<br>";
    }
    if($kod == $gen_kod) {
        $greska_kod = "Kod je ispravan!"."<br>";
    }

    $diff = abs(strtotime($trenutno) - strtotime($od));
    $diff2 = abs(strtotime($do) - strtotime($trenutno));
    
    if($diff > 0 && $diff2 > 0) {
        $sql_script_status = "UPDATE kupon SET active = '". 1 . "' WHERE kupon_id LIKE '". $kupon . "'";
        $rezultat = $mysql->updateDB($sql_script_status);
        $greska_datum = "Kupon je aktivan!"."<br>";
    }
    if($diff <= 0 || $diff2 <= 0) {
        $sql_script_status1 = "UPDATE kupon SET active = '". 0 . "' WHERE kupon_id LIKE '". $kupon . "'";
        $rezultat1 = $mysql->updateDB($sql_script_status1);
        $greska_datum = "Kupon je neaktivan!"."<br>";
    }
}


$mysql->zatvoriDB();
?>

<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Provjera kupona</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Popis podrucja" />
        <meta name="kljucne_rijeci" content="projekt, moderator, provjera_kupona" />
        <meta name="datum_izrade" content="31.05.2017." />
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
                    <a href="provjera_kupona.php" class="active">Provjera kupona</a>  
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
                    <a href="prijevoz.php">Prijevoz</a>
                </li>
                <li>
                    <a href="index.php?odjava=1">Odjava</a>
                </li>
            </ul>
        </nav>
        <script language="php"> </script>
        <p>
            <?php 
            
                if(isset($greska_kod)) {
                    echo $greska_kod;
                }
                if(isset($greska_datum)) {
                    echo $greska_datum;
                }
            ?>
        </p>
        <section>
            <h2 class="figcaption">Prazno mjesto</h2>
            <form id="novi" class="novi_proizvod" method="POST" name="novi_proizvod" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                <label for="kupon">Kupon:</label>
                <p id="kupon">
                    <?php
                    echo $select;
                    ?>
                </p><br>
                <label for="kod">Kod:</label>
                <input type="text" id="kod" name="kod" size="15" maxlength="15" placeholder="Generiran Kod" required="required"><br><br>
                
                <input class="button" id="submit" type="submit" value="PROVJERI"><br><br>
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