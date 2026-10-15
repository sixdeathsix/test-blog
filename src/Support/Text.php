<?php

namespace App\Support;

final class Text
{
    public static function paragraphs(string $text)
    {
        $blocks = preg_split('/\R\s*\R/', trim($text)) ?: [];
        $html = '';

        foreach ($blocks as $block) {
            $block = trim($block);

            if ($block === '') {
                continue;
            }

            $html .= '<p>' . nl2br(htmlspecialchars($block), false) . '</p>';
        }

        return $html;
    }
}
