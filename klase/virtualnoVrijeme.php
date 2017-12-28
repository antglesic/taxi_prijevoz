<?php
class virtualnoVrijeme {
    
    private static $datoteka;
    private static $citac;
    
    function __construct($url) {
        self::$datoteka = $url;
        self::$citac = new XMLReader();
    }
    
    function vratiVrijeme(){
        self::$citac->open(self::$datoteka);
	$pomak="Pomak je: ";      	  
        while(self::$citac->read() ) {
	print_r(self::$citac->name);
          if( 'brojSati' === self::$citac->name ) {
			self::$citac->read();
                    $pomak = self::$citac->value;
		    break;
                }
        }        
        return $pomak;
    }
}
?>
