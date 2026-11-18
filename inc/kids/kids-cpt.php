<?php

/**
 * Регистрация Custom Post Type: kids_activities
 * По аналогии с excursions
 */

if (!function_exists('kids_activities')) {

    add_action('init', 'kids_activities');

    function kids_activities()
    {
        $labels = [
            'name'                  => __('Детский досуг', 'alba-longa'),
            'singular_name'         => __('Активность', 'alba-longa'),
            'menu_name'             => __('Детям', 'alba-longa'),
            'name_admin_bar'        => __('Детям', 'alba-longa'),
            'archives'              => __('Все активности', 'alba-longa'),
            'attributes'            => __('Атрибуты', 'alba-longa'),
            'parent_item_colon'     => __('Связанный элемент', 'alba-longa'),
            'all_items'             => __('Все активности', 'alba-longa'),
            'add_new_item'          => __('Добавить новую активность', 'alba-longa'),
            'add_new'               => __('Добавить', 'alba-longa'),
            'new_item'              => __('Новая активность', 'alba-longa'),
            'edit_item'             => __('Редактировать активность', 'alba-longa'),
            'update_item'           => __('Обновить активность', 'alba-longa'),
            'view_item'             => __('Смотреть активность', 'alba-longa'),
            'view_items'            => __('Все активности на сайте', 'alba-longa'),
            'search_items'          => __('Искать активности', 'alba-longa'),
            'not_found'             => __('Активности не найдены', 'alba-longa'),
            'not_found_in_trash'    => __('В корзине активностей нет', 'alba-longa'),
            'featured_image'        => __('Изображение активности', 'alba-longa'),
            'set_featured_image'    => __('Установить изображение', 'alba-longa'),
            'remove_featured_image' => __('Удалить изображение', 'alba-longa'),
            'use_featured_image'    => __('Использовать как изображение', 'alba-longa'),
            'insert_into_item'      => __('Добавить в карточку', 'alba-longa'),
            'uploaded_to_this_item' => __('Загрузить', 'alba-longa'),
            'items_list'            => __('Список активностей', 'alba-longa'),
            'items_list_navigation' => __('Навигация', 'alba-longa'),
            'filter_items_list'     => __('Фильтр', 'alba-longa'),
        ];

        $rewrite = [
            'slug'       => 'detjam',
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];

        $args = [
            'label'               => __('Детский досуг', 'alba-longa'),
            'description'         => __('Раздел с детским досугом и активностями', 'alba-longa'),
            'labels'              => $labels,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'taxonomies'          => ['kids_category', 'kids_season'],
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-smiley',
            'menu_position'       => 6,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rewrite'             => $rewrite,
        ];

        register_post_type('kids_activities', $args);
    }
}
