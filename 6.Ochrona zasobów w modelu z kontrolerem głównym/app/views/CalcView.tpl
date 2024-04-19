{extends file="main.tpl"}

{block name=content}

<div class="pure-menu pure-menu-horizontal bottom-margin">
    <a href="{$conf->action_url}logout" class="pure-menu-heading pure-menu-link">wyloguj</a>
    <span style="float:right;">użytkownik: {$user->login}, rola: {$user->role}</span>
</div>

<form action="{$conf->action_url}calcCompute" method="post" class="pure-form pure-form-aligned bottom-margin">
    <legend>Prosty kalkulator kredytowy</legend>
    <fieldset>
        <div class="pure-control-group">
            <label for="kwota">Podaj wartość kredytu</label>
            <input id="kwota" type="text" placeholder="wartość kredytu" name="kwota" value="{$form->kwota}">
        </div>

        <div class="pure-control-group">
            <label for="lata">Podaj okres spłaty kredytu</label>
            <input id="lata" type="text" placeholder="okres spłaty kredytu" name="lata" value="{$form->lata}">
        </div>

        <div class="pure-control-group">
            <label for="oprocentowanie">Podaj wartość oprocentowania(%)</label>
            <input id="oprocentowanie" type="text" placeholder="wartość oprocentowania" name="oprocentowanie" value="{$form->oprocentowanie}">
        </div>

        <div class="pure-controls">
            <button type="submit" class="pure-button pure-button-primary">Oblicz miesięczną ratę kredytu</button>
        </div>
    </fieldset>
</form>

{include file='messages.tpl'}

{if isset($res->result)}
<div class="messages info">
    Miesięczna wartość kredytu: {$res->result}
</div>
{/if}

{/block}