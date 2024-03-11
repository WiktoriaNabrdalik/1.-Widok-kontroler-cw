<?php 
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Kalkulator kredytowy</title>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
</head>
<body>

<h1> Kalkulator kredytowy </h1>

<div style="width:90%; margin: 2em auto;">
	<a href="<?php print(_APP_ROOT); ?>/app/inna_chroniona.php" class="pure-button">kolejna chroniona strona</a>
	<a href="<?php print(_APP_ROOT); ?>/app/security/logout.php" class="pure-button pure-button-active">Wyloguj</a>
</div>

<div style="width:90%; margin: 2em auto;">

<?php
$kwota = isset($kwota) ? $kwota : '';
$lata = isset($lata) ? $lata : '';
$oprocentowanie = isset($oprocentowanie) ? $oprocentowanie : '';
?>

<form action="<?php print(_APP_URL);?>/app/calc_kredyt.php" method="post" class="pure-form pure-form-stacked">
	<legend>Podaj dane</legend>
	<fieldset>
		<label for="id_kwota">Podaj kwotę kredytu: </label>
		<input id="id_kwota" type="text" name="kwota" value="<?php out($kwota); ?>" /><br />
		<label for="id_lata">Podaj liczbę lat: </label>
		<input id="id_lata" type="text" name="lata" value="<?php out($lata); ?>" /><br />
		<label for="id_oprocentowanie">Podaj oprocentowanie (%) </label>
		<input id="id_oprocentowanie" type="text" name="oprocentowanie" value="<?php out($oprocentowanie); ?>" /><br />
	</fieldset>		
	<input type="submit" value="Oblicz ratę miesięczną" class="pure-button pure-button-primary" />
</form>	

<?php
//wyświeltenie listy błędów, jeśli istnieją
if (isset($messages)) {
	if (count ( $messages ) > 0) {
		echo '<ol style="margin: 20px; padding: 10px 10px 10px 30px; border-radius: 5px; background-color: #FF3336; width:300px;">';
		foreach ( $messages as $key => $msg ) {
			echo '<li>'.$msg.'</li>';
		}
		echo '</ol>';
	}
}
?>

<?php if (isset($rataMiesieczna)){ ?>
	<div style="margin-top: 1em; padding: 1em; border-radius: 0.5em; background-color: #33FFA8; width:25em;">
<?php echo 'Miesięczna rata: '.$rataMiesieczna; ?>
</div>
<?php } ?>

</body>
</html>