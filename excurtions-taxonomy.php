<?php

// регистрируем таксономию для excursions

add_action('init', 'excursions_taxonomy');
function excursions_taxonomy()
{

    register_taxonomy('excursions_cat', [ 'excursions' ], [
        'label'  => 'Категории "Развлечения и отдых"',
        'labels' => [
            'name'              => 'Категории "Развлечения и отдых"',
            'singular_name'     => 'Категория',
            'search_items'      => 'Найти категорию',
            'all_items'         => 'Все категории',
            'view_item '        => 'Просмотр категории',
            'parent_item'       => 'Родительская категория',
            'parent_item_colon' => 'Родительская категория:',
            'edit_item'         => 'Редактировать категорию',
            'update_item'       => 'Обновить категорию',
            'add_new_item'      => 'Добавить новую категорию',
            'new_item_name'     => 'Название новой категории',
            'menu_name'         => 'Категории "Развлечения и отдых"',
            'back_to_items'     => '← Назад к категориям',
        ],
        'description'       => 'Категории для "Развлечения и отдых"',
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'excursions-category'],
        'query_var'         => true,
        'rest_base'         => 'excursions_cat',
    ]);
}
