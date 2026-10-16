{extends file="layout.tpl"}

{block name="title"}{$status} — {$message}{/block}

{block name="content"}
    <section class="error-page" aria-labelledby="error-title">
        <div class="error-page__panel">
            <div class="error-page__visual" aria-hidden="true">
                <span class="error-page__code">{$status}</span>
            </div>

            <div class="error-page__content">
                <p class="error-page__eyebrow">Ошибка {$status}</p>
                <h1 class="error-page__title" id="error-title">{$message}</h1>

                <p class="error-page__description">
                    {if $status == 404}
                        Возможно, ссылка устарела или в адресе есть опечатка.
                        На главной найдётся что почитать.
                    {elseif $status == 405}
                        Этот адрес не поддерживает такой способ запроса.
                        Вернитесь на главную и продолжите чтение.
                    {elseif $status >= 500}
                        На нашей стороне произошла ошибка.
                        Попробуйте открыть страницу немного позже.
                    {else}
                        Не удалось открыть эту страницу.
                        Начните с главной — там собраны последние статьи.
                    {/if}
                </p>

                <a class="button error-page__home" href="/">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         aria-hidden="true" focusable="false">
                        <path d="m3 10 9-7 9 7M5 9v12h5v-6h4v6h5V9"
                              stroke="currentColor" stroke-width="1.75"
                              stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    На главную
                </a>
            </div>
        </div>
    </section>
{/block}
