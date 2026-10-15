<?php

return [
    'per_page' => 9,
    'home_posts_per_category' => 3,
    'similar_articles_limit' => 3,
    'sorting' => [
        'default' => 'date',
        'options' => [
            'date' => ['label' => 'По дате', 'column' => 'published_at', 'direction' => 'desc'],
            'views' => ['label' => 'По просмотрам', 'column' => 'views', 'direction' => 'desc'],
        ],
    ],
];