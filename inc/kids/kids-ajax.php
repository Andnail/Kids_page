<?php

/**
 * AJAX: Загрузить ещё kids_activities
 */

add_action('wp_ajax_kids_loadmore', 'kids_loadmore_handler');
add_action('wp_ajax_nopriv_kids_loadmore', 'kids_loadmore_handler');

function kids_loadmore_handler()
{
    $paged    = isset($_POST['page']) ? absint($_POST['page']) + 1 : 2;
    $season   = isset($_POST['season']) ? sanitize_text_field($_POST['season']) : '';
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $age      = isset($_POST['age']) ? sanitize_text_field($_POST['age']) : '';

    $args = [
        'post_type'      => 'kids_activities',
        'post_status'    => 'publish',
        'posts_per_page' => get_option('posts_per_page', 10),
        'paged'          => $paged,
    ];

    $tax_query  = [];
    $meta_query = [];

    if ($category) {
        $tax_query[] = [
            'taxonomy' => 'kids_category',
            'field'    => 'slug',
            'terms'    => $category,
        ];
    }

    if ($season) {
        $season_terms = [$season];
        $slug_lower   = mb_strtolower($season);

        if (in_array($slug_lower, ['zima', 'зима', 'leto', 'лето'], true)) {
            $year_round = get_term_by('name', 'Круглый год', 'kids_season');

            if (!$year_round) {
                $year_round = get_term_by('slug', 'kruglyj-god', 'kids_season');
            }

            if ($year_round && !is_wp_error($year_round)) {
                $season_terms[] = $year_round->slug;
            } else {
                $season_terms[] = 'kruglyj-god';
            }
        }

        $tax_query[] = [
            'taxonomy' => 'kids_season',
            'field'    => 'slug',
            'terms'    => $season_terms,
            'operator' => 'IN',
        ];
    }

    if ($age) {
        $meta_query[] = [
            'key'     => 'kids_age',
            'value'   => $age,
            'compare' => 'LIKE',
        ];
    }

    if ($tax_query) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $args['tax_query'] = $tax_query;
    }

    if ($meta_query) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            add_kids_card();
        }
        wp_reset_postdata();
    }

    wp_die();
}
