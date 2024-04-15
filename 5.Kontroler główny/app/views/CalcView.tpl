{extends file="main.tpl"}
{* przy zdefiniowanych folderach nie trzeba już podawać pełnej ścieżki *}

{block name=footer}przykładowa tresć stopki wpisana do szablonu głównego z szablonu kalkulatora{/block}

{block name=content}

<h3>Prosty kalkulator kredytowy</h2>

	<form class="pure-form pure-form-stacked" action="{$conf->action_root}calcCompute" method="post">
		<fieldset>

			<label for="kwota">Podaj wartość kredytu</label>
			<input id="kwota" type="text" placeholder="wartość kredytu" name="kwota" value="{$form->kwota}">

			<label for="lata">Podaj wokres spłaty kredytu</label>
			<input id="lata" type="text" placeholder="okres spłaty kredytu" name="lata" value="{$form->lata}">

			<label for="oprocentowanie">Podaj wartość oprocentowania(%)</label>
			<input id="oprocentowanie" type="text" placeholder="wartość oprocentowania" name="oprocentowanie" value="{$form->oprocentowanie}">


			<button type="submit" class="pure-button">Oblicz miesiączną ratę kredytu</button>
		</fieldset>
	</form>

<div class="messages">

	{* wyświeltenie listy błędów, jeśli istnieją *}
	{if $msgs->isError()}
		<h4>Wystąpiły błędy: </h4>
		<ol class="err">
		{foreach $msgs->getErrors() as $err}
		{strip}
			<li>{$err}</li>
		{/strip}
		{/foreach}
		</ol>
	{/if}
	
	{* wyświeltenie listy informacji, jeśli istnieją *}
	{if $msgs->isInfo()}
		<h4>Informacje: </h4>
		<ol class="inf">
		{foreach $msgs->getInfos() as $inf}
		{strip}
			<li>{$inf}</li>
		{/strip}
		{/foreach}
		</ol>
	{/if}
	
	{if isset($res->result)}
		<h4>Miesięczna rata kredytu</h4>
		<p class="res">
		{$res->result}
		</p>
	{/if}
	
	</div>
	
	{/block}