<?php
/**
 * Архив детских активностей
 * URL: /detjam/
 * Порядок блоков по ТЗ: знакомство с Чахкли → фильтры → карточки
 */

get_header();

$chakhkli_text = 'Привет! Я Чахкли — ваш маленький друг из Хибин. Я собрал здесь всё самое интересное для детей: от первых спусков на лыжах и весёлых игр до семейных прогулок и северных приключений. Выбирайте, что вам по душе, и отправляйтесь знакомиться с Большим Вудъявром вместе со мной!';
// Позже можно заменить на страницу «Чахкли и его друзья»
$chakhkli_url = home_url('/chakhkli/'); // заглушка, страницу создадим отдельно
?>

<main id="main" class="site-main page kids-archive">
    <div class="container">

        <div class="page__header page-header">
            <div class="page-header__wrap">
                <h1 class="page-header__title">Детям</h1>
                <?php
                if (function_exists('add_breadcrumbs')) {
                    add_breadcrumbs();
                }
?>
            </div>
        </div>

        <!-- 1. Знакомство с Чахкли -->
        <section class="kids-intro">
            <div class="kids-intro__inner">
                <div class="kids-intro__mascot">
                    <?php
    // Когда будет файл: get_stylesheet_directory_uri() . '/assets/img/chakhkli.png'
    $mascot = '';

if ($mascot) :
    ?>
                        <img src="<?php echo esc_url($mascot); ?>" alt="Чахкли" class="kids-intro__img" loading="lazy">
                    <?php else : ?>
                        <div class="kids-intro__placeholder" aria-hidden="true">Чахкли</div>
                    <?php endif; ?>
                </div>
                <div class="kids-intro__content">
                    <p class="kids-intro__text"><?php echo esc_html($chakhkli_text); ?></p>
                    <a href="<?php echo esc_url($chakhkli_url); ?>" class="kids-intro__btn btn">
                        Узнать больше о Чахкли
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. Фильтры -->
        <div class="kids-filters">
            <?php get_template_part('inc/kids/filters'); ?>
        </div>

        <!-- 3. Карточки -->
        <ul class="page-content__list kids-list" id="kids-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php kids_card(); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <li class="kids-empty">
                    <p>По выбранным фильтрам ничего не найдено.</p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('kids_activities')); ?>" class="btn">
                        Сбросить фильтры
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <?php
        // Пагинация (кнопку «Загрузить ещё» можно добавить позже)
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
