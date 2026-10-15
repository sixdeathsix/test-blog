<article class="card">
    <a href="/article/{$article->slug}" tabindex="-1">
        {include file="partials/image.tpl" src=$article->image alt=$article->title class="card__media"}
    </a>
    <div class="card__body">
        <h3 class="card__title">
            <a href="/article/{$article->slug}">{$article->title}</a>
        </h3>
        <p class="card__description">{$article->description}</p>
        <div class="card__meta">
            <time datetime="{$article->publishedAt->format('Y-m-d')}">{$article->publishedAt->format('d.m.Y')}</time>
            <span>Просмотры: {$article->views}</span>
        </div>
    </div>
</article>
