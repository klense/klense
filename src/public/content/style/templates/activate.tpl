{extends file="layout.tpl"}
{block name=content}
	{if $error == ''}
		<div class="title">Benvenuto, {$username}.</div>
		<div class="subtitle">
			Il tuo account è stato attivato con successo. <br />
			Per iniziare, effettua il <a href="login">login</a>.
		</div>
	{else}
		<div class="title">Errore durante l'attivazione.</div>
	{/if}
{/block}