{$paginator = $listing->paginator}
{if $paginator->totalPages > 1}
    <nav class="pagination">
        {for $number = 1 to $paginator->totalPages}
            {if $number == $paginator->page}
                <span class="pagination__item pagination__item--active">{$number}</span>
            {else}
                <a class="pagination__item" href="/category/{$listing->category->slug}?sort={$listing->sort->key}&amp;dir={$listing->sort->direction}&amp;page={$number}">{$number}</a>
            {/if}
        {/for}
    </nav>
{/if}
