<script type="text/javascript" src="content/js/pages/image.edit.overlay.js"></script>
<div style="display:none">
	<div id="edit_form" class="image_edit_form_overlay">
		<form method="POST" action="">
			<div class="overlay_title">Modifica immagine</div>
			<div class="form_error"></div>
			<div class="fields">
				<div>
					<div>Titolo</div>
					<div><input type="text" name="displayName" value="{$image_displayName}" maxlength="128" required /></div>
				</div>
				<div>
					<div>Descrizione</div>
					<div><textarea name="description" maxlength="1024">{$image_description}</textarea></div>
				</div>
				<div>
					<div>Tag (separare con una virgola)</div>
					<div><input type="text" name="tags" value="{$tags_str}" maxlength="128" /></div>
				</div>
				<div>
					<label>Nascondi dati EXIF <input type="checkbox" name="hide_exif" value="on" {if $hide_exif}checked{/if} /></label>
				</div>
			</div>
			<div>
				<button class="orange" type="submit">Modifica</button>
			</div>
		</form>
	</div>
</div>