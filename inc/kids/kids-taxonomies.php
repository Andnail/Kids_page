<?php

/**
 * Таксономии для kids_activities
 * По аналогии с excursions_taxonomy
 */

add_action('init', 'kids_taxonomy');

function kids_taxonomy()
{
    // ===== Категория =====
    register_taxonomy('kids_category', ['kids_activities'], [
        'label'  => 'Категории «Детям»',
        'labels' => [
            'name'              => 'Категории «Детям»',
            'singular_name'     => 'Категория',
            'search_items'      => 'Найти категорию',
            'all_items'         => 'Все категории',
            'view_item'         => 'Просмотр категории',
            'parent_item'       => 'Родительская категория',
            'parent_item_colon' => 'Родительская категория:',
            'edit_item'         => 'Редактировать категорию',
            'update_item'       => 'Обновить категорию',
            'add_new_item'      => 'Добавить новую категорию',
            'new_item_name'     => 'Название новой категории',
            'menu_name'         => 'Категории «Детям»',
            'back_to_items'     => '← Назад к категориям',
        ],
        'description'       => 'Категории для раздела «Детям»',
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'detjam-category'],
        'query_var'         => true,
        'rest_base'         => 'kids_category',
    ]);

    // ===== Сезон =====
    register_taxonomy('kids_season', ['kids_activities'], [
        'label'  => 'Сезоны «Детям»',
        'labels' => [
            'name'              => 'Сезоны «Детям»',
            'singular_name'     => 'Сезон',
            'search_items'      => 'Найти сезон',
            'all_items'         => 'Все сезоны',
            'view_item'         => 'Просмотр сезона',
            'parent_item'       => 'Родительский сезон',
            'parent_item_colon' => 'Родительский сезон:',
            'edit_item'         => 'Редактировать сезон',
            'update_item'       => 'Обновить сезон',
            'add_new_item'      => 'Добавить сезон',
            'new_item_name'     => 'Новый сезон',
            'menu_name'         => 'Сезоны «Детям»',
            'back_to_items'     => '← Назад к сезонам',
        ],
        'description'       => 'Сезонность активностей',
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'detjam-season'],
        'query_var'         => true,
        'rest_base'         => 'kids_season',
    ]);
}
