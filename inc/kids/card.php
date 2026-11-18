<?php
/**
 * Карточка детской активности
 * По ТЗ: фото, название, объект, описание, возраст, сезон, адрес, телефон, ссылки, стоимость
 */

$organization = get_post_meta(get_the_ID(), '_kids_organization', true);
$price        = get_post_meta(get_the_ID(), '_kids_price', true);
$age          = get_post_meta(get_the_ID(), '_kids_age', true);
$work_time    = get_post_meta(get_the_ID(), '_kids_work_time', true);
$address      = get_post_meta(get_the_ID(), '_kids_address', true);
$phone        = get_post_meta(get_the_ID(), '_kids_phone', true);
$site         = get_post_meta(get_the_ID(), '_kids_site', true);
$vk           = get_post_meta(get_the_ID(), '_kids_vk', true);
$links        = get_post_meta(get_the_ID(), '_kids_links', true);
$recommend    = get_post_meta(get_the_ID(), '_kids_recommend', true);

$season_terms = get_the_terms(get_the_ID(), 'kids_season');
$cat_terms    = get_the_terms(get_the_ID(), 'kids_category');

$season_name = ($season_terms && !is_wp_error($season_terms)) ? $season_terms[0]->name : '';
$cat_name    = ($cat_terms && !is_wp_error($cat_terms)) ? $cat_terms[0]->name : '';
?>

<li class="kids-card<?php echo $recommend ? ' kids-card--recommend' : ''; ?>">
    <div class="kids-card__inner">

        <?php if (has_post_thumbnail()) : ?>
            <div class="kids-card__image">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium_large', [
                        'class'   => 'kids-card__img',
                        'loading' => 'lazy',
                        'alt'     => the_title_attribute(['echo' => false]),
                    ]); ?>
                </a>
                <?php if ($recommend) : ?>
                    <span class="kids-card__badge" title="Чахкли рекомендует">Чахкли рекомендует</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="kids-card__content">
            <?php if ($organization) : ?>
                <div class="kids-card__org"><?php echo esc_html($organization); ?></div>
            <?php endif; ?>

            <h3 class="kids-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <?php if (has_excerpt()) : ?>
                <div class="kids-card__excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <ul class="kids-card__meta">
                <?php if ($age) : ?>
                    <li class="kids-card__meta-item kids-card__meta-item--age">
                        <span class="kids-card__meta-label">Возраст:</span>
                        <?php echo esc_html($age); ?>
                    </li>
                <?php endif; ?>

                <?php if ($season_name) : ?>
                    <li class="kids-card__meta-item kids-card__meta-item--season">
                        <span class="kids-card__meta-label">Сезон:</span>
                        <?php echo esc_html($season_name); ?>
                    </li>
                <?php endif; ?>

                <?php if ($cat_name) : ?>
                    <li class="kids-card__meta-item kids-card__meta-item--category">
                        <span class="kids-card__meta-label">Категория:</span>
                        <?php echo esc_html($cat_name); ?>
                    </li>
                <?php endif; ?>

                <?php if ($price) : ?>
                    <li class="kids-card__meta-item kids-card__meta-item--price">
                        <span class="kids-card__meta-label">Стоимость:</span>
                        <?php echo esc_html($price); ?>
                    </li>
                <?php endif; ?>

                <?php if ($work_time) : ?>
                    <li class="kids-card__meta-item kids-card__meta-item--time">
                        <span class="kids-card__meta-label">Время:</span>
                        <?php echo nl2br(esc_html($work_time)); ?>
                    </li>
                <?php endif; ?>

                <?php if ($address) : ?>
                    <li class="kids-card__meta-item kids-card__meta-item--address">
                        <span class="kids-card__meta-label">Адрес:</span>
                        <?php echo esc_html(wp_trim_words($address, 12, '…')); ?>
                    </li>
                <?php endif; ?>

                <?php if ($phone) : ?>
                    <li class="kids-card__meta-item kids-card__meta-item--phone">
                        <span class="kids-card__meta-label">Телефон:</span>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>">
                            <?php echo esc_html(trim($phone)); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="kids-card__links">
                <?php if ($site) : ?>
                    <a href="<?php echo esc_url($site); ?>" class="kids-card__ext-link" target="_blank" rel="noopener">Сайт</a>
                <?php endif; ?>
                <?php if ($vk) : ?>
                    <a href="<?php echo esc_url($vk); ?>" class="kids-card__ext-link" target="_blank" rel="noopener">VK</a>
                <?php endif; ?>
                <?php if ($links) : ?>
                    <?php
                    $link_lines = array_filter(array_map('trim', explode("\n", $links)));
                    foreach ($link_lines as $link) :
                        if (!filter_var($link, FILTER_VALIDATE_URL)) {
                            continue;
                        }
                        $is_pdf = (bool) preg_match('/\.pdf(\?|$)/i', $link);
                        ?>
                        <a href="<?php echo esc_url($link); ?>"
                           class="kids-card__ext-link <?php echo $is_pdf ? 'kids-card__ext-link--pdf' : ''; ?>"
                           target="_blank" rel="noopener">
                            <?php echo $is_pdf ? 'Документ / меню' : 'Ссылка'; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="kids-card__footer">
                <a href="<?php the_permalink(); ?>" class="kids-card__btn btn">Подробнее</a>
            </div>
        </div>
    </div>
</li>
