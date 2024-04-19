<?php


namespace app\controllers;

//zamieniamy 'require' na 'use' wskazując jedynie przestrzeń nazw, w której znajduje się klasa
use app\forms\CalcForm;
use app\transfer\CalcResult;
use Smarty\Smarty;


class CalcCtrl {

	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //inne dane dla widoku

	/** 
	 * Konstruktor - inicjalizacja właściwości
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->form = new CalcForm();
		$this->result = new CalcResult();
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->kwota = getFromRequest('kwota');
		$this->form->lata = getFromRequest('lata');
		$this->form->oprocentowanie = getFromRequest('oprocentowanie');
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeśli brak błedów, false w przeciwnym wypadku 
	 */
	public function validate() {
		// sprawdzenie, czy parametry zostały przekazane
		if (! (isset ( $this->form->kwota ) && isset ( $this->form->lata ) && isset ( $this->form->oprocentowanie ))) {
			// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
			return false; //zakończ walidację z błędem
		} 
		
		// sprawdzenie, czy potrzebne wartości zostały przekazane
		if ($this->form->kwota == "") {
			getMessages()->addError('Nie podano kwoty kredytu');
		}
		if ($this->form->lata == "") {
			getMessages()->addError('Nie podano okresu spłaty kredytu');
		}
		if ($this->form->oprocentowanie == "") {
			getMessages()->addError('Nie podano wartości oprocentowania');
		}

		
		// nie ma sensu walidować dalej gdy brak parametrów
		if (! getMessages()->isError()) {
			
			// sprawdzenie, czy $x i $y są liczbami całkowitymi
			if (! is_numeric ( $this->form->kwota )) {
				getMessages()->addError('Kwota kredytu nie jest liczbą całkowitą');
			}
			
			if (! is_numeric ( $this->form->lata )) {
				getMessages()->addError('Okres spłaty kredytu nie jest liczbą całkowitą');
			}

			if (! is_numeric ( $this->form->oprocentowanie )) {
				getMessages()->addError('Oprocentowanie kredytu nie jest liczbą całkowitą');
			}
		}
		
		return ! getMessages()->isError();
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
	

	public function action_calcCompute(){

    $this->getparams();

    if ($this->validate()) {
        // konwersja parametrów na float
        $this->form->kwota = floatval($this->form->kwota);
        $this->form->lata = floatval($this->form->lata);
        $this->form->oprocentowanie = floatval($this->form->oprocentowanie);
        
        // Sprawdzenie czy użytkownik jest administratorem
        if (inRole('admin')) {
            // obliczanie kalkulatora kredytowego niezależnie od kwoty kredytu
            $oprocentowanieMiesieczne = ($this->form->oprocentowanie / 100) / 12; // miesięczne oprocentowanie
            $liczbaMiesiecy = $this->form->lata * 12; // liczba miesięcy
            $result = ($this->form->kwota * $oprocentowanieMiesieczne) / (1 - pow(1 + $oprocentowanieMiesieczne, -$liczbaMiesiecy));
            $result = round($result, 2);  // zaokrąlenie do dwóch miejsc po przecinku
            
            getMessages()->addInfo('Miesięczna rata kredytu została obliczona');

            // Ustawienie wyniku w obiekcie CalcResult
            $this->result->result = $result;
        } else {
            // Sprawdzenie czy kwota kredytu jest mniejsza od miliona
            if ($this->form->kwota < 1000000) {
                // obliczanie kalkulatora kredytowego dla kwoty mniejszej od miliona
                $oprocentowanieMiesieczne = ($this->form->oprocentowanie / 100) / 12; // miesięczne oprocentowanie
                $liczbaMiesiecy = $this->form->lata * 12; // liczba miesięcy
                $result = ($this->form->kwota * $oprocentowanieMiesieczne) / (1 - pow(1 + $oprocentowanieMiesieczne, -$liczbaMiesiecy));
                $result = round($result, 2);  // zaokrąlenie do dwóch miejsc po przecinku
                
                getMessages()->addInfo('Miesięczna rata kredytu została obliczona');

                // Ustawienie wyniku w obiekcie CalcResult
                $this->result->result = $result;
            } else {
                // Komunikat o braku uprawnień do wprowadzania kwoty równej lub większej od miliona
                getMessages()->addError('Tylko administrator może wprowadzać kwoty kredytu równą lub większą od miliona.');
            }
        }
    }
    
    $this->generateView();
}
	
public function action_calcShow(){
	getMessages()->addInfo('Witaj w kalkulatorze kredytowym');
	$this->generateView();
}
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){

		getSmarty()->assign('user',unserialize($_SESSION['user']));
				
		getSmarty()->assign('page_title','Super kalkulator - role');

		getSmarty()->assign('form',$this->form);
		getSmarty()->assign('res',$this->result);
		
		getSmarty()->display('CalcView.tpl');
	}
}