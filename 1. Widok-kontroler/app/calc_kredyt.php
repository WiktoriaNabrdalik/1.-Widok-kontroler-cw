<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

//pobranie parametrów

$kwota = $_REQUEST ['kwota'];
$lata = $_REQUEST ['lata'];
$oprocentowanie = $_REQUEST ['oprocentowanie'];


// walidacja parametrów 

// sprawdzenie, czy parametry zostały przekazane
if ( ! (isset($kwota) && isset($lata) && isset($oprocentowanie))) {
	//sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
	$messages [] = 'Błędne wywołanie aplikacji. Brak jednego z parametrów.';
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


if (empty( $messages )) {
	
	
	if (! is_numeric( $kwota )) {
		$messages [] = 'Wartość kredytu nie jest liczbą całkowitą';
	}
	
	if (! is_numeric( $lata )) {
		$messages [] = 'Okres spłaty kredytu nie jest liczbą całkowitą';
	}	

	if (! is_numeric( $oprocentowanie )) {
		$messages [] = 'Oprocentowanie nie jest liczbą całkowitą';
	}
}

// wykonaj zadanie jeśli wszystko w porządku

if (empty ( $messages )) { // gdy brak błędów
	
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


//Wywołanie widoku z przekazaniem zmiennych

include 'calc_kredyt_view.php';