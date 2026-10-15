{extends file="layout.tpl"}

{block name="title"}{$status} — {$message}{/block}

{block name="content"}
    <h1>{$status}</h1>
    <p>{$message}</p>
    <p><a href="/">На главную</a></p>
{/block}
