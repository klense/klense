{extends file="layout.tpl"}
{block name=head}
	<script type="text/javascript" src="content/js/jquery.fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="content/js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4-reshow.js"></script>
	<link rel="stylesheet" type="text/css" href="content/js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script type="text/javascript" src="content/js/pages/image.view.js"></script>
{/block}
{block name=content}
	<div class="image_container">
		<a class="fancybox_img" href="{$maxSize}"><img src="{$image_filename}" alt="{$image_displayName}" /></a>
	</div>
	<div class="image_meta">
		<span class="image_title">{$image_displayName}</span>
		{if $is_owner}<span class="image_editlink"><a href="#edit_form" id="image_edit_link">modifica</a></span>{/if}
		<div class="image_description">{$image_description}</div>
	</div>
	<div style="margin-top: 50px;">
		<div style="font-size: 1.3em;">Commenti</div>
		<textarea style="width: 500px; height: 80px;" maxlength="1000"></textarea>
	</div>
	{if $is_owner}
		{$edit_form}
	{/if}
{/block}
{block name=bottom_sidebar}
	<div class="image_sidebar_sections">
		<div class="image_owner">Di <a href="{$user_url}">{$user_publicname}</a></div>
		{if $hide_exif == false}
			{if isset($myexif.make_model)}
				<div>
					Foto scattata con una <a href="#">{$myexif.make_model}</a>
					{if isset($myexif.shot_ownerdate)}in data <time datetime="{$myexif.shot_userdatetime_iso}" title="Fuso orario corrente: {$myexif.shot_userdatetime}">{$myexif.shot_ownerdate}, ore {$myexif.shot_ownertime}.{else}</time>.{/if}
				</div>
			{else}
				{if isset($myexif.shot_ownerdate)}
					<div>Foto scattata in data <time datetime="{$myexif.shot_userdatetime_iso}" title="Fuso orario corrente: {$myexif.shot_userdatetime}">{$myexif.shot_ownerdate}, ore {$myexif.shot_ownertime}</time>.</div>
				{/if}
			{/if}
			<div>
				<div class="headingSlide closed"><a href="#" onclick="return false;">Dati EXIF</a></div>
				<div class="contentSlide closed" style="padding">
					<table>
						<tr>
							<td>Exif 1</td>
							<td>val1</td>
						</tr>
						<tr>
							<td>Exif 2</td>
							<td>val2</td>
						</tr>
					</table>
				</div>
			</div>
		{/if}
		<div>
			<div class="headingSlide opened"><a href="#" onclick="return false;">Altre dimensioni</a></div>
			<div class="contentSlide opened" style="padding">
				{foreach from=$otherSizes item=size}
					<div><a href="{$size.link}">{$size.descr} ({$size.w}x{$size.h})</a></div>
				{/foreach}
			</div>
		</div>
		<div>
			<div class="headingSlide opened"><a href="#" onclick="return false;">Tag</a></div>
			<div class="contentSlide opened">
				{if count($tags) > 0}
					<div class="tags">{foreach from=$tags item=tag}<a href="#">{$tag}</a> {/foreach}</div>
				{else}
					<div>Nessuno</div>
				{/if}
			</div>
		</div>
	</div>
{/block}