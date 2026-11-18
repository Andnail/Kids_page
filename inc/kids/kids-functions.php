<?php
/**
 * Функции для элементов произвольного типа записей «Детям» (kids_activities)
 * По аналогии с excurtions-functions.php
 */

/*
 * Подключение стилей и скриптов раздела
 */
if (!function_exists('kids_enqueue_assets')) {
    add_action('wp_enqueue_scripts', 'kids_enqueue_assets');

    function kids_enqueue_assets()
    {
        if (!is_post_type_archive('kids_activities')
            && !is_singular('kids_activities')
            && !is_tax('kids_season')
            && !is_tax('kids_category')
        ) {
            return;
        }

        $theme_uri = get_stylesheet_directory_uri();
        $version   = '1.0.2';

        wp_enqueue_style(
            'kids-styles',
            $theme_uri . '/assets/css/kids.css',
            [],
            $version
        );

        wp_enqueue_script(
            'kids-loadmore',
            $theme_uri . '/assets/js/kids-loadmore.js',
            ['jquery'],
            $version,
            true
        );

        wp_localize_script('kids-loadmore', 'kids_ajax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }
}


/*
 * Миниатюра kids (для списка / related)
 */
if (!function_exists('add_kids_thumbnail')) {
    add_action('kids', 'add_kids_thumbnail');

    function add_kids_thumbnail()
    {
        if (has_post_thumbnail()) :
            echo '<div class="list-item__image"><a href="' . esc_url(get_the_permalink()) . '">';
            the_post_thumbnail('cpt-thumbnail', ['loading' => 'lazy']);
            echo '</a></div>';
        endif;
    }
}


/*
 * Изображение на подробной странице kids
 */
if (!function_exists('add_kids_img')) {
    add_action('kids', 'add_kids_img');

    function add_kids_img($represent = 'single')
    {
        if (!has_post_thumbnail()) {
            return;
        }

        $attachment_id  = get_post_thumbnail_id();
        $attachment_img = wp_get_attachment_image_src($attachment_id, 'full');
        $img_alt        = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        $recommend      = get_post_meta(get_the_ID(), '_kids_recommend', true);

        if (!$attachment_img) {
            return;
        }

        $result = '<div class="cpt-content__image cpt-content__image--' . esc_attr($represent) . '">';
        $result .= '<a href="' . esc_url($attachment_img[0]) . '">';
        $result .= '<img decoding="async" data-src="' . esc_url($attachment_img[0]) . '" src="' . esc_url($attachment_img[0]) . '" alt="' . esc_attr($img_alt) . '" width="' . esc_attr($attachment_img[1]) . '" height="' . esc_attr($attachment_img[2]) . '">';
        $result .= '</a>';

        if ($recommend) {
            $result .= '<div class="kids-recommend-badge"><span>' . esc_html__('Чахкли рекомендует', 'alba-longa') . '</span></div>';
        }

        $result .= '</div>';

        echo $result;
    }
}


/*
 * Ячейка (карточка) kids — в разметке list-item, как у excursions
 */
if (!function_exists('add_kids_card')) {
    add_action('kids', 'add_kids_card');

    function add_kids_card()
    {
        $organization = get_post_meta(get_the_ID(), '_kids_organization', true);
        $price        = get_post_meta(get_the_ID(), '_kids_price', true);
        $age          = get_post_meta(get_the_ID(), '_kids_age', true);
        $duration     = get_post_meta(get_the_ID(), '_kids_duration', true);
        $group        = get_post_meta(get_the_ID(), '_kids_group', true);
        $recommend    = get_post_meta(get_the_ID(), '_kids_recommend', true);

        $season_terms = get_the_terms(get_the_ID(), 'kids_season');
        $season_name  = ($season_terms && !is_wp_error($season_terms)) ? $season_terms[0]->name : '';
        ?>
    <li class="page-list__item list-item<?php echo $recommend ? ' list-item--recommend' : ''; ?>">
      <?php add_kids_thumbnail(); ?>

      <div class="list-item__wrap">
        <div class="list-item__content">

          <?php if ($organization) : ?>
            <div class="list-item__org"><?php echo esc_html($organization); ?></div>
          <?php endif; ?>

          <?php
            if (is_front_page()) {
                the_title(
                    sprintf('<span class="list-item__title"><a class="list-item__link" href="%s">', esc_url(get_permalink())),
                    '</a></span>'
                );
            } else {
                the_title(
                    sprintf('<h2 class="list-item__title"><a class="list-item__link" href="%s">', esc_url(get_permalink())),
                    '</a></h2>'
                );
            }
        ?>

          <?php if ($recommend) : ?>
            <div class="list-item__badge"><?php echo esc_html__('Чахкли рекомендует', 'alba-longa'); ?></div>
          <?php endif; ?>

          <?php if ($age) : ?>
            <div class="list-item__info">
              <svg class="list-item__icon">
                <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/sprite.svg#user"></use>
              </svg>
              <p><?php echo esc_html($age); ?></p>
            </div>
          <?php endif; ?>

          <?php if ($group) : ?>
            <div class="list-item__info">
              <svg class="list-item__icon">
                <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/sprite.svg#flag"></use>
              </svg>
              <p><?php echo esc_html($group); ?></p>
            </div>
          <?php endif; ?>

          <?php if ($duration) : ?>
            <div class="list-item__info">
              <svg class="list-item__icon">
                <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/sprite.svg#clock"></use>
              </svg>
              <p><?php echo esc_html__('Длительность', 'alba-longa'); ?>: <?php echo esc_html($duration); ?></p>
            </div>
          <?php endif; ?>

          <?php if ($season_name) : ?>
            <div class="list-item__info">
              <p><?php echo esc_html__('Сезон', 'alba-longa'); ?>: <?php echo esc_html($season_name); ?></p>
            </div>
          <?php endif; ?>

        </div><!-- .list-item__content -->

        <div class="list-item__footer">
          <?php if ($price) : ?>
            <span class="list-item__price"><?php echo esc_html($price); ?></span>
          <?php endif; ?>

          <a class="btn" href="<?php the_permalink(); ?>">
            <?php echo esc_html__('Подробнее', 'alba-longa'); ?>
          </a>
        </div><!-- .list-item__footer -->

      </div><!-- .list-item__wrap -->
    </li>
        <?php
    }
}


/*
 * Обёртка для архива / AJAX: либо template-part (kids-card), либо list-item
 * По умолчанию — template-part с разметкой kids-card (как в ТЗ)
 */
if (!function_exists('kids_card')) {
    function kids_card($style = 'template')
    {
        if ($style === 'list-item') {
            add_kids_card();

            return;
        }
        get_template_part('inc/kids/card');
    }
}


/*
 * Похожие / другие активности
 */
if (!function_exists('add_related_kids')) {
    add_action('kids', 'add_related_kids');

    function add_related_kids()
    {
        $price = get_post_meta(get_the_ID(), '_kids_price', true);
        ?>
    <li class="page-list__item list-item list-item--related">
      <?php add_kids_thumbnail(); ?>

      <div class="list-item__wrap">
        <div class="list-item__content">
          <?php
            the_title(
                sprintf('<h2 class="list-item__title"><a class="list-item__link" href="%s">', esc_url(get_permalink())),
                '</a></h2>'
            );
        ?>
        </div>
        <div class="list-item__footer">
          <?php if ($price) : ?>
            <span class="list-item__price"><?php echo esc_html($price); ?></span>
          <?php endif; ?>
          <a class="btn" href="<?php the_permalink(); ?>">
            <?php echo esc_html__('Подробнее', 'alba-longa'); ?>
          </a>
        </div>
      </div>
    </li>
        <?php
    }
}


/*
 * Микроразметка schema.org для kids
 */
if (!function_exists('add_kids_schema')) {
    add_action('wp_head', 'add_kids_schema');

    function add_kids_schema()
    {
        if (!is_singular('kids_activities')) {
            return;
        }

        $thumb   = get_post_thumbnail_id();
        $img_url = $thumb ? wp_get_attachment_url($thumb) : '';
        $price   = get_post_meta(get_the_ID(), '_kids_price', true);
        // Из «от 5000 ₽» берём число, если есть
        $price_num = '';

        if ($price && preg_match('/(\d[\d\s]*)/', $price, $m)) {
            $price_num = preg_replace('/\s+/', '', $m[1]);
        }

        $future_date = gmdate('Y-m-d', strtotime('+1 month'));
        $desc        = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20);

        $data = [
            '@context'    => 'https://schema.org/',
            '@type'       => 'Product',
            'name'        => get_the_title(),
            'image'       => esc_url($img_url),
            'description' => wp_strip_all_tags($desc),
            'brand'       => [
                '@type' => 'Brand',
                'name'  => get_bloginfo('name'),
            ],
            'sku'    => (string) get_the_ID(),
            'offers' => [
                '@type'           => 'Offer',
                'url'             => get_permalink(),
                'priceCurrency'   => 'RUB',
                'price'           => $price_num ?: '0',
                'priceValidUntil' => $future_date,
                'availability'    => 'https://schema.org/InStock',
            ],
        ];

        echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}


/*
 * Хелпер: значение меты с fallback
 */
if (!function_exists('kids_get_meta')) {
    function kids_get_meta($post_id, $key, $default = '')
    {
        $value = get_post_meta($post_id, $key, true);

        return ($value !== '' && $value !== null) ? $value : $default;
    }
}


/*
 * Собрать URL фильтров с сохранением остальных параметров
 */
if (!function_exists('kids_filter_url')) {
    function kids_filter_url($archive_url, $key, $value, $current = [])
    {
        $params = array_filter([
            'category' => $current['category'] ?? '',
            'season'   => $current['season'] ?? '',
            'age'      => $current['age'] ?? '',
        ]);

        if ($value === '' || $value === null) {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }

        if (empty($params)) {
            return $archive_url;
        }

        return add_query_arg($params, $archive_url);
    }
}
