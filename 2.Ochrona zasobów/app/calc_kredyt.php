<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

//ochrona kontrolera- - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

//pobranie parametrów
function getParams(&$kwota,&$lata,&$oprocentowanie){
	$kwota = isset($_REQUEST['kwota']) ? $_REQUEST['kwota'] : null;
	$lata = isset($_REQUEST['lata']) ? $_REQUEST['lata'] : null;
	$oprocentowanie = isset($_REQUEST['oprocentowanie']) ? $_REQUEST['oprocentowanie'] : null;	
}


// walidacja parametrów 
//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$kwota,&$lata,&$oprocentowanie,&$messages){
// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($kwota) && isset($lata) && isset($oprocentowanie))) {
		// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
		// teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
		return false;
	}

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $kwota == "") {
		$messages [] = 'Nie podano kwoty';
	}

	if ( $lata == "") {
		$messages [] = 'Nie podano okresu spłaty kredytu';
	}

	if ( $oprocentowanie == "") {
		$messages [] = 'Nie podano oprocentowania';
	}


	//nie ma sensu walidować dalej gdy brak parametrów
	if (count ( $messages ) != 0) return false;
	
	
	if (! is_numeric( $kwota )) {
		$messages [] = 'Wartość kredytu nie jest liczbą całkowitą';
	}
	
	if (! is_numeric( $lata )) {
		$messages [] = 'Okres spłaty kredytu nie jest liczbą całkowitą';
	}	

	if (! is_numeric( $oprocentowanie )) {
		$messages [] = 'Oprocentowanie nie jest liczbą całkowitą';
	}

	if (count ( $messages ) != 0) return false;
    else return true;
}

function process(&$kwota,&$lata,&$oprocentowanie,&$messages,&$rataMiesieczna){
    global $role;
    
    //konwersja parametrów na float
    $kwota = floatval($kwota);
    $lata = floatval($lata);
    $oprocentowanie = floatval($oprocentowanie);
    
    //obliczanie kalkulatora kredytowego
    $oprocentowanieMiesieczne = ($oprocentowanie / 100) / 12; //miesiączne oprocentowanie
    $liczbaMiesiecy = $lata * 12; //liczba miesięcy
    $rataMiesieczna = ($kwota * $oprocentowanieMiesieczne) / (1 - pow(1 + $oprocentowanieMiesieczne, -$liczbaMiesiecy));
    $rataMiesieczna = round($rataMiesieczna, 2);  //zaokrąlenie do dwóch miejsc po przecinku
}

//definicja zmiennych kontrolera
$kwota = null;
$lata = null;
$oprocentowanie = null;
$rataMiesieczna = null;
$messages = array();

//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
getParams($kwota,$lata,$oprocentowanie);
if ( validate($kwota,$lata,$oprocentowanie,$messages) ) { // gdy brak błędów
    process($kwota,$lata,$oprocentowanie,$messages,$rataMiesieczna);
}

// Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$kwota,$lata,$oprocentowanie,$rataMiesieczna)
//   będą dostępne w dołączonym skrypcie
include 'calc_kredyt_view.php';