<?php

/**
 * Фильтрация архива kids_activities
 * Категория + сезон + возраст. Зима/Лето включают «Круглый год».
 */

add_action('pre_get_posts', 'kids_filter_archive_query');

function kids_filter_archive_query($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (!is_post_type_archive('kids_activities') && !is_tax('kids_category') && !is_tax('kids_season')) {
        return;
    }

    $tax_query  = [];
    $meta_query = [];

    $category = '';

    if (!empty($_GET['category'])) {
        $category = sanitize_text_field($_GET['category']);
    } elseif (is_tax('kids_category')) {
        $term = get_queried_object();

        if ($term && !is_wp_error($term)) {
            $category = $term->slug;
        }
    }

    if ($category) {
        $tax_query[] = [
            'taxonomy' => 'kids_category',
            'field'    => 'slug',
            'terms'    => $category,
        ];
    }

    $season = '';

    if (!empty($_GET['season'])) {
        $season = sanitize_text_field($_GET['season']);
    } elseif (is_tax('kids_season')) {
        $term = get_queried_object();

        if ($term && !is_wp_error($term)) {
            $season = $term->slug;
        }
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

    if (!empty($_GET['age'])) {
        $age          = sanitize_text_field($_GET['age']);
        $meta_query[] = [
            'key'     => 'kids_age',
            'value'   => $age,
            'compare' => 'LIKE',
        ];
    }

    if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $query->set('tax_query', $tax_query);
    }

    if (!empty($meta_query)) {
        $query->set('meta_query', $meta_query);
    }
}
