<nav class="sort">
    <span class="sort__label">Сортировка:</span>
    {foreach $listing->sortings as $option}
        {if $option->key == $listing->sort->key}
            <a class="sort__item sort__item--active"
               href="/category/{$listing->category->slug}?sort={$option->key}&amp;dir={$listing->sort->oppositeDirection()}"
               title="Изменить направление">
                {$option->label} {if $listing->sort->direction == 'asc'}↑{else}↓{/if}
            </a>
        {else}
            <a class="sort__item" href="/category/{$listing->category->slug}?sort={$option->key}">{$option->label}</a>
        {/if}
    {/foreach}
</nav>