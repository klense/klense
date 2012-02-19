<article>
	<header>
		<a id="comment-{$comment.id}"></a>
		<a href="{$comment.author_url}"><img src="content/style/images/generic_avatar.png" class="avatar small" /></a>
		<a href="{$comment.author_url}">{$comment.author_name}</a> - <a href="{$base_url_params}#comment-{$comment.id}"><time pubdate datetime="{$comment.datetime_iso}">{$comment.datetime}</time></a>
	</header>
	<div>{$comment.content}</div>
</article>