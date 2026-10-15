{extends file="layout.tpl"}

{block name="title"}{$articlePage->article->title} — Блог{/block}

{block name="content"}
    {$article = $articlePage->article}

    <article class="article">
        <h1>{$article->title}</h1>
        
        <p class="lead">{$article->description}</p>

        <div class="article__meta">
            <time datetime="{$article->publishedAt->format('Y-m-d')}">{$article->publishedAt->format('d.m.Y')}</time>
            <span>Просмотры: {$article->views}</span>
        </div>

        <ul class="tags">
            {foreach $articlePage->categories as $category}
                <li><a class="tags__item" href="/category/{$category->slug}">{$category->name}</a></li>
            {/foreach}
        </ul>

        {include file="partials/image.tpl" src=$article->image alt=$article->title class="article__media"}

        <div class="article__content">
            {$article->content|paragraphs nofilter}
        </div>
    </article>

    {if $articlePage->similar}
        <section class="section">
            <h2 class="section__title__similar">Похожие статьи</h2>
            <div class="cards">
                {foreach $articlePage->similar as $similar}
                    {include file="partials/article_card.tpl" article=$similar}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
