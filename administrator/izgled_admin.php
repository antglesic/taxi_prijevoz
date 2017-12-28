<?php
include("baza.class.php");
include("sesija.class.php");
include("klase/virtualnoVrijeme.php");

$mysql = new BAZA();
$mysql->spojiDB();

$odabir = "";
foreach ($_POST as $key => $value) {
    $odabir = $_POST["izgled"];
}

if ($odabir == '1') {
    $fh = fopen('css/antglesic.css', 'w');
    fclose($fh);
    $fs = fopen("css/antglesic_orange.css", "r");
    $ft = fopen("css/antglesic.css", "w");
    while ($ch = fgets($fs))
        fputs($ft, $ch);

    fclose($fs);
    fclose($ft);
    header("Location: izgled_admin.php");
}
if ($odabir == '2') {
    $fh = fopen('css/antglesic.css', 'w');
    fclose($fh);
    $fs = fopen("css/antglesic_red.css", "r");
    $ft = fopen("css/antglesic.css", "w");
    while ($ch = fgets($fs))
        fputs($ft, $ch);

    fclose($fs);
    fclose($ft);
    header("Location: izgled_admin.php");
}
if ($odabir == '3') {
    $fh = fopen('css/antglesic.css', 'w');
    fclose($fh);
    $fs = fopen("css/antglesic_green.css", "r");
    $ft = fopen("css/antglesic.css", "w");
    while ($ch = fgets($fs))
        fputs($ft, $ch);

    fclose($fs);
    fclose($ft);
    header("Location: izgled_admin.php");
}


$mysql->zatvoriDB();
?>

<!DOCTYPE html>

<html lang="hr">
    <head>
        <title>Naslovnica</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Početna stranica" />
        <meta name="kljucne_rijeci" content="zadaća 2, početna" />
        <meta name="datum_izrade" content="07.03.2017." />
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
                    <a href="dnevnik.php">Dnevnik</a>
                </li>
                <li>
                    <a href="otkljucavanje_korisnika.php">Otključavanje</a>
                </li>
                <li>
                    <a href="izgled_admin.php" class="active">Izgled</a>  
                </li>
                <li>
                    <a href="index.php?odjava=1">Odjava</a>  
                </li>
            </ul>
        </nav>
        <section>
            <form id="novi" class="novi_proizvod" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                <select id="izgled" name="izgled">
                    <option value="1">Narančasta tema</option>
                    <option value="2">Crvena tema</option>
                    <option value="3">Zelena tema</option>
                </select><br><br>
                
                
                <input id="submit" class="button" type="submit" name="submit" value="ODABERI"/><br><br>
                
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