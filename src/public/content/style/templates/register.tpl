{extends file="layout.tpl"}
{block name=head}
	<script>
		function formValid() {
			document.form.password_c.setCustomValidity(document.form.password_c.value != document.form.password.value ? 'Passwords do not match.' : '');
		}

		var RecaptchaOptions = {
			theme : 'white'
		};

		$(function() {
			$( "#birth_date" ).datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				maxDate: "-10y",
				minDate: "-110y",
				yearRange: "-110:-10"
			});
		});
	</script>
{/block}
{block name=content}
	<div class="title">Registrazione</div>
	<div>
		Grazie per aver scelto di registrarti a klense!<br />
		Dopo aver immesso le informazioni richieste, sarai pronto per iniziare.
	</div>
	<form method="POST" action="{$base_url_params}" name="form" oninput="formValid();">
		<div class="registration_form">
			{if $error != ''}
			<div class="form_error">{$error}</div>
			{/if}
			<table>
				<tr>
					<td><label for="username">Nome utente</label></td>
					<td><input type="text" name="username" id="username" value="" pattern="{literal}^[a-zA-Z\d_]{5,20}${/literal}" required /> <span class="field_hint" title="da 5 a 20 caratteri, sono ammessi lettere, numeri e underscore">?</span></td>
				</tr>
				<tr>
					<td><label for="email">Indirizzo email</label></td>
					<td><input type="email" name="email" id="email" value="" required /></td>
				</tr>
				<tr>
					<td><label for="password">Password</label></td>
					<td><input type="password" name="password" id="password" value="" required pattern="{literal}^.{6,}${/literal}" /> <span class="field_hint" title="minimo 6 caratteri">?</span> </td>
				</tr>
				<tr>
					<td><label for="password_c">Ripeti password</label></td>
					<td><input type="password" name="password_c" id="password_c" value="" required /></td>
				</tr>
				<tr>
					<td><label for="birth_date">Data di nascita</label></td>
					<td><input type="text" name="birth_date" id="birth_date" value="" required /></td>
				</tr>
				<tr>
					<td><label for="timezone">Fuso orario</label></td>
					<td>
						<select size="1" id="timezone" name="timezone">
							{foreach from=$timezones item=curr_tz}
							<option value="{$curr_tz}" {if $curr_tz=="UTC"}selected{/if} >{$curr_tz}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>{$recaptcha}</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:left"><label><input type="checkbox" name="accept" value="1" required /> Ho letto e accetto i <a href="#" target="_blank">termini di servizio</a> di klense.</label></td>
				</tr>
				<tr>
					<td colspan="2"><button class="orange" type="submit">Registrati</button></td>
				</tr>
			</table>
		</div>
	</form>
{/block}