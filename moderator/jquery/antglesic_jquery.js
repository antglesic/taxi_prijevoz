
$(document).ready(function () {
    $('#tablica').dataTable();
    var drzave = new Array();


    $.getJSON("drzave.json",
            function (data) {
                $.each(data, function (key, val) {
                    console.log(val);
                    drzave.push(val);
                });
            });

    $('#drzave').autocomplete({
        source: drzave
    });

    var pozivni = new Array();
    $("#ucitaj").click(function (event) {
        $.getJSON("drzave-brojevi.json", function (data) {
            $.each(data, function (key, val) {
                $("#pozivni").append("<option>" + key + val + "</option>");
                //pozivni[key] = val;
            });
            //console.log(pozivni);
        });
    });
    $("#ime").focusout(function () {
        var reg = new RegExp(/^([A-Z]{1})/);
        var ime = $("#ime").val();
        var prez = $("#prez").val();
        var okej1 = reg.test(ime);
        var okej2 = reg.test(prez);
        if (ime === '' || !okej1) {
            $("#korIme").attr("disabled", true);
            $("#ime").addClass("greske");
        }
        if (prez === '' || !okej2) {
            $("#korIme").attr("disabled", true);
            $("#prez").addClass("greske");
        }
        if (ime !== '' && okej1) {
            $("#korIme").attr("disabled", false);
            $("#ime").removeClass("greske");
        }
        if (prez !== '' && okej2) {
            $("#korIme").attr("disabled", false);
            $("#prez").removeClass("greske");
        }
    });
    $("#prez").focusout(function () {
        var reg = new RegExp(/^([A-Z]{1})/);
        var ime = $("#ime").val();
        var prez = $("#prez").val();
        var okej1 = reg.test(ime);
        var okej2 = reg.test(prez);
        if (ime === '' || !okej1) {
            $("#korIme").attr("disabled", true);
            $("#ime").addClass("greske");
        }
        if (prez === '' || !okej2) {
            $("#korIme").attr("disabled", true);
            $("#prez").addClass("greske");
        }
        if (ime !== '' && okej1) {
            $("#korIme").attr("disabled", false);
            $("#ime").removeClass("greske");
        }
        if (prez !== '' && okej2) {
            $("#korIme").attr("disabled", false);
            $("#prez").removeClass("greske");
        }
    });
    $("#lozinka1").focusout(function () {
        var lozinka = $("#lozinka1").val();
        var re1 = new RegExp(/^(?=(?:.*[A-Z]){2,})(?=(?:.*[a-z]){2,})(?=(?:.*[0-9]){1,})\S{5,15}$/);
        var ok = re1.test(lozinka);
        if (ok === false) {
            $("#lozinka1").addClass("greske");
            $("#lozinka1").focus();
        } else {
            $("#lozinka1").removeClass("greske");
        }
    });
    $("#lozinka2").focusout(function () {
        var lozinka1 = $("#lozinka1").val();
        var lozinka2 = $("#lozinka2").val();
        if (lozinka2 !== lozinka1) {
            $("#lozinka2").addClass("greske");
            $("#lozinka2").focus();
        } else {
            $("#lozinka2").removeClass("greske");
        }
    });
    $("#email").focusout(function () {
        var mail = $("#email").val();
        var re = new RegExp(/[a-z0-9]+[_a-z0-9\.-]*[a-z0-9]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})/);
        var ok = re.test(mail);
        if (ok === false) {
            $("#email").addClass("greske");
            $("#submit").attr("disabled", true);
        } else {
            $("#email").removeClass("greske");
            $("#submit").attr("disabled", false);
        }
    });
    $("#korIme").focusout(function (event) {
        var korIme = $("#korIme").val();
        var korIme_provjera;
        $.ajax({
            url: 'http://barka.foi.hr/WebDiP/2016/materijali/zadace/dz3/korisnikImePrezime.php',
            data: {ime: korIme, prezime: korIme},
            type: 'GET',
            dataType: 'xml',
            success: function (xml) {
                $(xml).find('korisnicko_ime').each(function () {
                    korIme_provjera = $(this).text();
                });
                if ($.isNumeric(korIme_provjera) && korIme !== '') {
                    //console.log("Korisnik ne postoji!");
                    $("#greske").text("Korisnik ne postoji!");
                    $("#submit").attr("disabled", false);
                    $("#korIme").removeClass("greske");
                    $("#korIme").addClass("dobar");
                    //$("#lozinka1").addClass("greske");
                }
                if (!$.isNumeric(korIme_provjera) && korIme !== '') {
                    $("#greske").text("Korisnik postoji! Molim promijenite korisničko ime!");
                    $("#korIme").addClass("greske");
                    $("#submit").attr("disabled", true);
                    $("#korIme").focus();
                    //$("#lozinka1").removeClass("greske");
                }
                console.log(xml);
                console.log(korIme_provjera);
            }
        });
    });
    $("#prvi").hover(function () {
        var alt = $("#prvi").attr("alt");
        $("#alt").text(alt + "  Dimenzije: 300x450");
    });
    $("#drugi").hover(function () {
        var alt = $("#drugi").attr("alt");
        $("#alt").text(alt + "  Dimenzije: 300x450");
    });
    $("#treci").hover(function () {
        var alt = $("#treci").attr("alt");
        $("#alt").text(alt + "  Dimenzije: 300x450");
    });
});
