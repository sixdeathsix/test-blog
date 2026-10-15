{extends file="layout.tpl"}

{block name="title"}Блог{/block}

{block name="content"}
    {foreach $sections as $section}
        <section class="section">
            <div class="section__header">
                <h2 class="section__title">{$section->category->name}</h2>
                <a class="button" href="/category/{$section->category->slug}">Все статьи</a>
            </div>
            <div class="cards">
                {foreach $section->articles as $article}
                    {include file="partials/article_card.tpl" article=$article}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p>Статей пока нет.</p>
    {/foreach}
{/block}
