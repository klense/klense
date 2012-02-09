{extends file="layout.tpl"}
{block name=content}
	<div class="title login_title">Accedi</div>
	<div class="login_form">
		<form action="{$base_url_params}" method="POST">
			{if $error != ''}
			<div class="form_error">{$error}</div>
			{/if}
			<input type="text" name="username" placeholder="Nome utente" />
			<input type="password" name="password" placeholder="Password" />
			<button type="submit" class="orange">Accedi</button>
		</form>
	</div>
{/block}