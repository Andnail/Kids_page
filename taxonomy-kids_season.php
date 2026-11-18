<?php
/**
 * Архив по сезону (kids_season)
 */

get_header();

$term = get_queried_object();
?>

<main id="main" class="site-main page kids-archive kids-archive--season">
    <div class="container">

        <div class="page__header page-header">
            <div class="page-header__wrap">
                <h1 class="page-header__title">
                    <?php echo esc_html($term->name); ?>
                </h1>
                <?php
                if (function_exists('add_breadcrumbs')) {
                    add_breadcrumbs();
                }
?>
            </div>

            <?php if ($term->description) : ?>
                <div class="page-header__descr post-content">
                    <?php echo wp_kses_post($term->description); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Фильтры -->
        <div class="kids-filters">
            <?php get_template_part('inc/kids/filters'); ?>
        </div>

        <ul class="page-content__list kids-list" id="kids-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php kids_card(); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <li class="kids-empty">В этом сезоне пока нет активностей.</li>
            <?php endif; ?>
        </ul>

        <?php
        the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => '←',
            'next_text' => '→',
        ]);
?>

    </div>
</main>

<?php
get_footer();
