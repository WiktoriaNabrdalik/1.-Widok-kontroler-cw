<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
//załaduj Smarty
require_once _ROOT_PATH.'/lib/smarty/Smarty.class.php';

// pobranie parametrów i przypisanie do tablicy $form
//pobranie parametrów
function getParams(&$form){
$form['kwota'] = isset($_REQUEST['kwota']) ? $_REQUEST['kwota'] : null;
$form['lata'] = isset($_REQUEST['lata']) ? $_REQUEST['lata'] : null;
$form['oprocentowanie'] = isset($_REQUEST['oprocentowanie']) ? $_REQUEST['oprocentowanie'] : null;
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$form,&$infos,&$msgs,&$hide_intro){

//sprawdzenie, czy parametry zostały przekazane - jeśli nie to zakończ walidację
if ( ! (isset($form['kwota']) && isset($form['lata']) && isset($form['oprocentowanie']) ))	return false;	

//parametry przekazane zatem
	//nie pokazuj wstępu strony gdy tryb obliczeń (aby nie trzeba było przesuwać)
	// - ta zmienna zostanie użyta w widoku aby nie wyświetlać całego bloku itro z tłem 
	$hide_intro = true;

	$infos [] = 'Przekazano parametry.';

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $form['kwota'] == "") $msgs [] = 'Nie podano kwoty';
	if ( $form['lata'] == "") $msgs [] = 'Nie podano okresu spłaty kredytu';
	if ( $form['oprocentowanie'] == "") $msgs [] = 'Nie podano oprocentowania';

	//nie ma sensu walidować dalej gdy brak parametrów
	if ( count($msgs)==0 ) {
		// sprawdzenie, czy $x i $y są liczbami całkowitymi
		if (! is_numeric( $form['kwota'] )) $msgs [] = 'Kwota kredytu nie jest liczbą całkowitą';
		if (! is_numeric( $form['lata'] )) $msgs [] = 'Okres spłaty kredytu nie jest liczbą całkowitą';
		if (! is_numeric( $form['oprocentowanie'] )) $msgs [] = 'Oprocentowanie kredytu nie jest liczbą całkowitą';
	}
	
	if (count($msgs)>0) return false;
	else return true;
}

// wykonaj obliczenia
function process(&$form,&$infos,&$msgs,&$result){
	$infos [] = 'Parametry poprawne. Wykonuję obliczenia.';

	//konwersja parametrów na float
	$form['kwota'] = floatval($form['kwota']);
	$form['lata'] = floatval($form['lata']);
	$form['oprocentowanie'] = floatval($form['oprocentowanie']);
	
	// obliczanie kalkulatora kredytowego
	$oprocentowanieMiesieczne = ($form['oprocentowanie'] / 100) / 12; // miesięczne oprocentowanie
	$liczbaMiesiecy = $form['lata'] * 12; // liczba miesięcy
	$rataMiesieczna = ($form['kwota'] * $oprocentowanieMiesieczne) / (1 - pow(1 + $oprocentowanieMiesieczne, -$liczbaMiesiecy));
	$rataMiesieczna = round($rataMiesieczna, 2);  // zaokrąlenie do dwóch miejsc po przecinku
	
	// przypisanie wyniku do $result
	$result = $rataMiesieczna;
}

//inicjacja zmiennych
$form = null;
$infos = array();
$messages = array();
$result = null;
$hide_intro = false;
	
getParams($form);
if ( validate($form,$infos,$messages,$hide_intro) ){
	process($form,$infos,$messages,$result);
}

// przygotowanie danych dla szablonu

$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Proste szablonowanie');
$smarty->assign('page_description','Szablonowanie oparte na bibliotece Smarty');
$smarty->assign('page_header','Szablony Smarty');

$smarty->assign('hide_intro',$hide_intro);

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// wywołanie szablonu
$smarty->display(_ROOT_PATH.'/app/calc_kredyt.html');


