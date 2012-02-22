{extends file="layout.tpl"}
{block name=head}
	<!--{$phplivex_init}-->

	<!--<script type="text/javascript" src="content/js/pages/user.js"></script>-->
{/block}
{block name=content}
	<div class="title">{$user_publicname}</div>
	{if isset($user_username)}<div class="subtitle">{$user_username}</div>{/if}
{/block}
{block name=middle_sidebar}
{/block}