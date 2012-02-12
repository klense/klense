{extends file="layout.tpl"}
{block name=head}
	<!-- Load Queue widget CSS -->
	<link rel="stylesheet" type="text/css" href="content/js/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css" />
	<!-- Load plupload and finally the jQuery queue widget -->
	<script type="text/javascript" src="content/js/plupload/js/plupload.js"></script>
	<script type="text/javascript" src="content/js/plupload/js/plupload.html4.js"></script>
	<script type="text/javascript" src="content/js/plupload/js/plupload.html5.js"></script>
	<script type="text/javascript" src="content/js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>

	<script type="text/javascript" src="content/js/pages/image.upload.js"></script>
{/block}
{block name=content}
	<div class="title">Carica immagini su klense</div>
	<form action="{$base_url_params}" method="POST">
		<div id="uploader">You browser doesn't support native upload.</div>
	</form>
	<!--
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
					<td><button class="orange" type="submit">Carica</button></td>
				</tr>
			</table>
		</form>
	</div>
	-->
{/block}