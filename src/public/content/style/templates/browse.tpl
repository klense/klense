{extends file="layout.tpl"}
{block name=content}
	<div class="title">Le ultime immagini caricate</div>
	<table class="imagelist">
		{section name=outer_imgarr loop=$images start=0 step=3}
			<tr>
			{section name=inner_imgarr loop=$images start=$smarty.section.outer_imgarr.index step=1 max=3}
				<td>
					<div>
						<a href="{$images[$smarty.section.inner_imgarr.index].imgurl}"><img src="{$images[$smarty.section.inner_imgarr.index].filename}" title="{$images[$smarty.section.inner_imgarr.index].displayName}" alt="{$images[$smarty.section.inner_imgarr.index].displayName}" /></a>
						<div>
							di <a href="{$images[$smarty.section.inner_imgarr.index].user_url}">{$images[$smarty.section.inner_imgarr.index].user_publicname}</a>
						</div>
					</div>
				</td>
			{/section}
			</tr>
		{/section}
	</table>
{/block}