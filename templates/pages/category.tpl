{extends file="layout.tpl"}

{block name="title"}{$listing->category->name} — Блог{/block}

{block name="content"}
    <h1>{$listing->category->name}</h1>
    <p class="lead">{$listing->category->description}</p>

    {include file="partials/sort_switch.tpl" listing=$listing}

    {if $listing->articles}
        <div class="cards">
            {foreach $listing->articles as $article}
                {include file="partials/article_card.tpl" article=$article}
            {/foreach}
        </div>

        {include file="partials/pagination.tpl" listing=$listing}
    {else}
        <p>В этой категории пока нет статей.</p>
    {/if}
{/block}
