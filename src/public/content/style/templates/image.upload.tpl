{extends file="layout.tpl"}
{block name=content}
	<div class="title">Carica immagini su klense</div>
	<div class="upload_form">
		<form action="{$base_url_params}" method="POST" enctype="multipart/form-data">
			{if $error != ''}
			<div class="form_error">{$error}</div>
			{/if}
			<div>Max file size: ?</div>
			<input type="hidden" name="MAX_FILE_SIZE" value="{$max_upload_size}" />
			<table>
				<tr>
					<td><input id="file_upload-1" type="file" name="file_upload[]" /></td>
				</tr>
				<tr>
					<td><input id="file_upload-2" type="file" name="file_upload[]" /></td>
				</tr>
				<tr>
					<td><input id="file_upload-3" type="file" name="file_upload[]" /></td>
				</tr>
				<tr>
					<td><button class="orange" type="submit">Carica</button></td>
				</tr>
			</table>
		</form>
	</div>
{/block}