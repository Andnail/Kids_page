<?php
/**
 * Одиночная страница детской активности
 * По ТЗ: полное описание, условия, стоимость, график, контакты, галерея, PDF, видео
 */

get_header();

$organization = get_post_meta(get_the_ID(), '_kids_organization', true);
$price        = get_post_meta(get_the_ID(), '_kids_price', true);
$age          = get_post_meta(get_the_ID(), '_kids_age', true);
$duration     = get_post_meta(get_the_ID(), '_kids_duration', true);
$work_time    = get_post_meta(get_the_ID(), '_kids_work_time', true);
$group        = get_post_meta(get_the_ID(), '_kids_group', true);
$address      = get_post_meta(get_the_ID(), '_kids_address', true);
$phone        = get_post_meta(get_the_ID(), '_kids_phone', true);
$site         = get_post_meta(get_the_ID(), '_kids_site', true);
$vk           = get_post_meta(get_the_ID(), '_kids_vk', true);
$links        = get_post_meta(get_the_ID(), '_kids_links', true);
$video        = get_post_meta(get_the_ID(), '_kids_video', true);
$recommend    = get_post_meta(get_the_ID(), '_kids_recommend', true);

$season_terms = get_the_terms(get_the_ID(), 'kids_season');
$cat_terms    = get_the_terms(get_the_ID(), 'kids_category');

$season_name = ($season_terms && !is_wp_error($season_terms)) ? $season_terms[0]->name : '';
$cat_name    = ($cat_terms && !is_wp_error($cat_terms)) ? $cat_terms[0]->name : '';

$phone_clean = $phone ? preg_replace('/[^\d+]/', '', $phone) : '';
?>

<main id="main" class="site-main page single-kids">
    <div class="container">

        <div class="page__header page-header single-kids__header">
            <div class="page-header__wrap">
                <?php
                if (function_exists('add_breadcrumbs')) {
                    add_breadcrumbs();
                }
                ?>
                <h1 class="page-header__title single-kids__title"><?php the_title(); ?></h1>

                <?php if ($organization) : ?>
                    <div class="single-kids__org"><?php echo esc_html($organization); ?></div>
                <?php endif; ?>

                <?php if ($recommend) : ?>
                    <div class="single-kids__badge">Чахкли рекомендует</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="single-kids__layout">

            <!-- Левая колонка: контент -->
            <div class="single-kids__main">

                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-kids__image">
                        <?php the_post_thumbnail('large', [
                            'class'   => 'single-kids__img',
                            'loading' => 'eager',
                            'alt'     => the_title_attribute(['echo' => false]),
                        ]); ?>
                    </div>
                <?php endif; ?>

                <!-- Галерея (если будут доп. изображения через ACF / вложения) -->
                <?php
                $gallery = get_attached_media('image', get_the_ID());
                if ($gallery && count($gallery) > 1) :
                    ?>
                    <div class="single-kids__gallery">
                        <h2 class="single-kids__section-title">Фотогалерея</h2>
                        <div class="single-kids__gallery-grid">
                            <?php foreach ($gallery as $img) : ?>
                                <a href="<?php echo esc_url(wp_get_attachment_url($img->ID)); ?>"
                                   class="single-kids__gallery-item"
                                   data-fancybox="kids-gallery">
                                    <?php echo wp_get_attachment_image($img->ID, 'medium', false, ['loading' => 'lazy']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="single-kids__content post-content">
                    <?php the_content(); ?>
                </div>

                <?php if ($video) : ?>
                    <div class="single-kids__video">
                        <h2 class="single-kids__section-title">Видео</h2>
                        <div class="single-kids__video-wrap">
                            <?php
                            // Простая поддержка YouTube / Vimeo / прямых ссылок
                            if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video, $m)) {
                                echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($m[1]) . '" frameborder="0" allowfullscreen loading="lazy"></iframe>';
                            } elseif (preg_match('/vimeo\.com\/(\d+)/', $video, $m)) {
                                echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($m[1]) . '" frameborder="0" allowfullscreen loading="lazy"></iframe>';
                            } else {
                                echo '<a href="' . esc_url($video) . '" target="_blank" rel="noopener" class="btn">Смотреть видео</a>';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                // PDF / документы
                if ($links) :
                    $link_lines = array_filter(array_map('trim', explode("\n", $links)));
                    $pdfs = [];
                    foreach ($link_lines as $link) {
                        if (filter_var($link, FILTER_VALIDATE_URL) && preg_match('/\.pdf(\?|$)/i', $link)) {
                            $pdfs[] = $link;
                        }
                    }
                    if ($pdfs) :
                        ?>
                        <div class="single-kids__docs">
                            <h2 class="single-kids__section-title">Документы</h2>
                            <div class="single-kids__docs-list">
                                <?php foreach ($pdfs as $pdf) : ?>
                                    <a href="<?php echo esc_url($pdf); ?>"
                                       class="single-kids__doc-btn btn"
                                       target="_blank"
                                       rel="noopener">
                                        Посмотреть документ / меню
                                    </a>
                                    <a href="<?php echo esc_url($pdf); ?>"
                                       class="single-kids__doc-download"
                                       download
                                       target="_blank"
                                       rel="noopener">
                                        Скачать PDF
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif;
                endif;
                ?>

            </div>

            <!-- Правая колонка: сайдбар с ключевой информацией -->
            <aside class="single-kids__sidebar">

                <div class="single-kids__info-card">

                    <?php if ($price) : ?>
                        <div class="single-kids__info-item single-kids__info-item--price">
                            <span class="single-kids__info-label">Стоимость</span>
                            <span class="single-kids__info-value single-kids__price"><?php echo esc_html($price); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($age) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Возраст / ограничения</span>
                            <span class="single-kids__info-value"><?php echo esc_html($age); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($season_name) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Сезон</span>
                            <span class="single-kids__info-value"><?php echo esc_html($season_name); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($cat_name) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Категория</span>
                            <span class="single-kids__info-value"><?php echo esc_html($cat_name); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($duration) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Длительность</span>
                            <span class="single-kids__info-value"><?php echo esc_html($duration); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($work_time) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Время работы</span>
                            <span class="single-kids__info-value"><?php echo nl2br(esc_html($work_time)); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($group) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Группа</span>
                            <span class="single-kids__info-value"><?php echo esc_html($group); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($address) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Адрес</span>
                            <span class="single-kids__info-value"><?php echo nl2br(esc_html($address)); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($phone) : ?>
                        <div class="single-kids__info-item">
                            <span class="single-kids__info-label">Телефон</span>
                            <span class="single-kids__info-value">
                                <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="single-kids__phone">
                                    <?php echo esc_html(trim($phone)); ?>
                                </a>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="single-kids__actions">
                        <?php if ($phone) : ?>
                            <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="btn single-kids__btn single-kids__btn--call">
                                Позвонить
                            </a>
                        <?php endif; ?>

                        <?php if ($site) : ?>
                            <a href="<?php echo esc_url($site); ?>" class="btn single-kids__btn single-kids__btn--site" target="_blank" rel="noopener">
                                Сайт
                            </a>
                        <?php endif; ?>

                        <?php if ($vk) : ?>
                            <a href="<?php echo esc_url($vk); ?>" class="btn single-kids__btn single-kids__btn--vk" target="_blank" rel="noopener">
                                VK
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="single-kids__back">
                    <a href="<?php echo esc_url(get_post_type_archive_link('kids_activities')); ?>" class="single-kids__back-link">
                        ← Все активности для детей
                    </a>
                </div>

            </aside>

        </div>

    </div>
</main>

<?php
get_footer();
