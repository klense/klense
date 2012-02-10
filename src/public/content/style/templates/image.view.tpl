{extends file="layout.tpl"}
{block name=head}
	<script>
		$(function() {
			$(".contentSlide.closed").hide();
			$(".headingSlide").click(function() {
				if ($(this).next(".contentSlide").is(":visible")) {
					$(this).removeClass("opened closed").addClass("closed");
					$(this).next(".contentSlide").removeClass("opened closed").addClass("closed");
				} else {
					$(this).removeClass("opened closed").addClass("opened");
					$(this).next(".contentSlide").removeClass("opened closed").addClass("opened");
				}
				$(this).next(".contentSlide").slideToggle(200);
			});
		});
	</script>
{/block}
{block name=content}
	<div class="image_container">
		<img src="{$image_filename}" alt="{$image_displayName}" /></a>
	</div>
	<div class="image_title">{$image_displayName}</div>
	<div class="image_description">{$image_description}</div>
{/block}
{block name=bottom_sidebar}
	<div class="image_sidebar_sections">
		<div class="image_owner">Di <a href="{$user_url}">{$user_publicname}</a></div>
		{if isset($myexif.make_model)}
			<div>
				Foto scattata con una <a href="#">{$myexif.make_model}</a>
				{if isset($myexif.shot_date)}in data {$myexif.shot_date}, ore {$myexif.shot_time}.{else}.{/if}
			</div>
		{else}
			{if isset($myexif.shot_date)}
				<div>Foto scattata in data {$myexif.shot_date}, ore {$myexif.shot_time}.</div>
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
		<div>
			<div class="headingSlide opened"><a href="#" onclick="return false;">Altre dimensioni</a></div>
			<div class="contentSlide opened" style="padding">
				{foreach from=$otherSizes item=size}
					<div><a href="#">{$size.descr} ({$size.w}x{$size.h})</a> · <a href="{$size.link}">download</a></div>
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