<?php

// добавим метки (тэги) к excursions

add_action( 'init', 'excursions_tags' );
function excursions_tags(){

  register_taxonomy( 'excursions_tags', [ 'excursions' ], [
    'label'               => 'Метки "Развлечения и отдых"',  
    'labels'              => [
      'name'              => 'Метки "Развлечения и отдых"',
      'singular_name'     => 'Метка',
      'search_items'      => 'Найти метку',
      'all_items'         => 'Все метки',
      'view_item '        => 'Просмотр метки',
      'edit_item'         => 'Редактировать метку',
      'update_item'       => 'Обновить метку',
      'add_new_item'      => 'Добавить новую метку',
      'new_item_name'     => 'Название новой метки',
      'menu_name'         => 'Метки (тэги) "Развлечения и отдых"',
    ],
    'show_in_rest'  => true,
    'rewrite'       => array( 'slug' => 'excursions-tags' ),
    'query_var'     => true,
    'show_in_quick_edit'  => true,
  ] );
}	


// регистрируем новый кастомный тип постов Развлечение и отдых

if (!function_exists('excursions')) {

  add_action('init', 'excursions');

  function excursions() {

    $labels = array(
      'name'                  => __('Развлечения и отдых', 'alba-longa'),
      'singular_name'         => __('Развлечение/отдых', 'alba-longa'),
      'menu_name'             => __('5. Развлечения и отдых', 'alba-longa'),
      'name_admin_bar'        => __('5. Развлечения и отдых', 'alba-longa'),
      'archives'              => __('Все материалы', 'alba-longa'),
      'attributes'            => __('Атрибуты', 'alba-longa'),
      'parent_item_colon'     => __('Связанный элемент', 'alba-longa'),
      'all_items'             => __('Все предложения', 'alba-longa'),
      'add_new_item'          => __('Добавить новое', 'alba-longa'),
      'add_new'               => __('Добавить', 'alba-longa'),
      'new_item'              => __('Новое предложение', 'alba-longa'),
      'edit_item'             => __('Редактировать', 'alba-longa'),
      'update_item'           => __('Обновить', 'alba-longa'),
      'view_item'             => __('Просмотреть', 'alba-longa'),
      'view_items'            => __('Все предложения на сайте', 'alba-longa'),
      'search_items'          => __('Поиск по отдыху', 'alba-longa'),
      'not_found'             => __('Не найдено', 'alba-longa'),
      'not_found_in_trash'    => __('Нет в корзине', 'alba-longa'),
      'featured_image'        => __('Изображение', 'alba-longa'),
      'set_featured_image'    => __('Установить изображение', 'alba-longa'),
      'remove_featured_image' => __('Удалить изображение', 'alba-longa'),
      'use_featured_image'    => __('Использовать как изображение', 'alba-longa'),
      'insert_into_item'      => __('Добавить в карточку', 'alba-longa'),
      'uploaded_to_this_item' => __('Загрузить', 'alba-longa'),
      'items_list'            => __('Список', 'alba-longa'),
      'items_list_navigation' => __('Навигация', 'alba-longa'),
      'filter_items_list'     => __('Фильтр', 'alba-longa'),
    );

    $rewrite = array(
      'slug'         => 'excursions',
      'with_front'   => true,
      'pages'        => true,
      'feeds'        => false,
    );

    $args = array(
      'label'                 => __('Развлечения и отдых', 'alba-longa'),
      'description'           => __('Раздел с активностями: туры, прогулки, рыбалка и прочее', 'alba-longa'),
      'labels'                => $labels,
      'supports'              => array('title', 'editor', 'thumbnail', 'comments', 'custom-fields'),
      'taxonomies'            => array('excursions_cat', 'excursions_tags'),
      'hierarchical'          => true,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_icon'             => 'dashicons-palmtree',
      'menu_position'         => 100,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'post',
      'show_in_rest'          => true,
      'rewrite'               => $rewrite,
    );

    register_post_type('excursions', $args);
  }
}


// регистрируем новый кастомный тип постов Экскурсии, по шаблону

/*if (!function_exists('excursions')) {

  add_action('init', 'excursions');

 
  function excursions() {

    $labels = array(
      'name'                  => __('Экскурсии', 'alba-longa'),
      'singular_name'         => __('Экскурсия', 'alba-longa'),
      'menu_name'             => __('5. Экскурсии', 'alba-longa'),
      'name_admin_bar'        => __('5. Экскурсии', 'alba-longa'),
      'archives'              => __('Все Экскурсии', 'alba-longa'),
      'attributes'            => __('Атрибуты', 'alba-longa'),
      'parent_item_colon'     => __('Экскурсия', 'alba-longa'),
      'all_items'             => __('Все Экскурсии', 'alba-longa'),
      'add_new_item'          => __('Добавить новую Экскурсию', 'alba-longa'),
      'add_new'               => __('Добавить новую Экскурсию', 'alba-longa'),
      'new_item'              => __('Новая Экскурсия', 'alba-longa'),
      'edit_item'             => __('Редактировать Экскурсию', 'alba-longa'),
      'update_item'           => __('Обновить Экскурсию', 'alba-longa'),
      'view_item'             => __('Просмотр страницы Экскурсии', 'alba-longa'),
      'view_items'            => __('Все Экскурсии на сайте', 'alba-longa'),
      'search_items'          => __('Поиск', 'alba-longa'),
      'not_found'             => __('Не найдено', 'alba-longa'),
      'not_found_in_trash'    => __('Нет в корзине', 'alba-longa'),
      'featured_image'        => __('Изображение Экскурсии', 'alba-longa'),
      'set_featured_image'    => __('Установить изображение Экскурсии', 'alba-longa'),
      'remove_featured_image' => __('Удалить изображение Экскурсии', 'alba-longa'),
      'use_featured_image'    => __('Использовать как изображение Экскурсии', 'alba-longa'),
      'insert_into_item'      => __('Добавить в карточку Экскурсии', 'alba-longa'),
      'uploaded_to_this_item' => __('Загрузить', 'alba-longa'),
      'items_list'            => __('Список Экскурсий', 'alba-longa'),
      'items_list_navigation' => __('Навигация по Экскурсиям', 'alba-longa'),
      'filter_items_list'     => __('Фильтр Экскурсий', 'alba-longa'),
    );

    $rewrite = array(
      'slug'   =>    'excursions',
      'with_front'    => true,
      'pages'          => true,
      'feeds'           => false,
    );

    $args = array(
      'label'                 => __('Экскурсии', 'alba-longa'),
      'description'           => __('Раздел для публикации экскурсий, туров, прогулок', 'alba-longa'),
      'labels'                => $labels,
      'supports'              => array('title', 'editor', 'thumbnail', 'comments', 'custom-fields'),
      'taxonomies'            => array('excursions_cat', 'excursions_tags'),
      'hierarchical'          => true,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_icon'             => 'dashicons-flag',
      'menu_position'         => 100,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'post',
      'show_in_rest'          => true,
      'rewrite'               => $rewrite,
    );

    register_post_type('excursions', $args);
  }
}*/