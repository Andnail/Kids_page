
<?php

/*
* Функции для элементов произвольного типа записей Экскурсии (excursions)
*/

// миниатюра excursions
if (!function_exists('add_excursions_thumbnail')) {
    add_action('excursions', 'add_excursions_thumbnail');
    function add_excursions_thumbnail()
    {
        if (has_post_thumbnail()) :
            echo '<div class="list-item__image"><a href="' . esc_url(get_the_permalink()) . '">';
            the_post_thumbnail('cpt-thumbnail', [ 'loading' => 'lazy' ]);
            echo '</a></div>';
        endif;
    }
}



// вывод значения рейтинга
if (!function_exists('add_rating')) {
    add_action('rating', 'add_rating');
    function add_rating()
    {
        $average = get_average_ratings(get_the_ID());

        for ($i = 1; $i <= $average + 1; $i++) {
            $width = intval($i - $average > 0 ? 20 - (($i - $average) * 20) : 20);

            if (0 === $width) {
                continue;
            }
        }
        $rating = '';

        if ($average > 0) {
            $rating = '<div class="exc-rating exc-rating--list"><span class="exc-rating__label">' . __('Рейтинг', 'alba-longa') . '</span> <div class="exc-rating__wrap"><span class="exc-rating__num">' . $average . '</span><div class="exc-rating__stars" style="--rating: ' . $average . ';"></div></div></div>';
        }
        echo $rating;
    }
}



// изображение внутри excursions с рейтингом
if (!function_exists('add_excursions_img')) {
    add_action('excursions', 'add_excursions_img');
    function add_excursions_img($represent)
    {

        $option = TitanFramework::getInstance('alba');

        if (has_post_thumbnail()) :
            $attachmentId  = get_post_thumbnail_id();
            $attachmentImg = wp_get_attachment_image_src($attachmentId, $size = 'full');
            $imgAlt        = get_post_meta($attachmentId, '_wp_attachment_image_alt', true);
            $result        = '<div class="cpt-content__image cpt-content__image--'. $represent. '">';
            $result .= '<a href="' . esc_url($attachmentImg[0]) . '"><img decoding="async"  data-src="' . esc_url($attachmentImg[0]) . '" alt="' . esc_attr($imgAlt) . '" width="' . esc_attr($attachmentImg[1]) . '" height="' . esc_attr($attachmentImg[2]) . '"></a>';

            // рейтинг
            if ($option->getOption('cpt-excursions-rating-loc')) {
                $average = get_average_ratings(get_the_ID());
                for ($i = 1; $i <= $average + 1; $i++) {
                    $width = intval($i - $average > 0 ? 20 - (($i - $average) * 20) : 20);

                    if (0 === $width) {
                        continue;
                    }
                }

                if ($average) {
                    $result .= '<div class="exc-rating"><span class="exc-rating__label">' . __('Рейтинг экскурсии', 'alba-longa') . '</span> <div class="exc-rating__wrap"><span class="exc-rating__num">' . $average . '</span><div class="exc-rating__stars" style="--rating: ' . $average . ';"></div></div></div>';
                }
            }

            $result .= '</div>';

            echo $result;
        endif;
    }
}



// дни проведения excursions
if (!function_exists('add_excursion_day')) {
    add_action('excursions', 'add_excursion_day');
    function add_excursion_day()
    {
        $monday    = get_post_meta(get_the_ID(), 'exc_monday', true);
        $tuesday   = get_post_meta(get_the_ID(), 'exc_tuesday', true);
        $wednesday = get_post_meta(get_the_ID(), 'exc_wednesday', true);
        $thursday  = get_post_meta(get_the_ID(), 'exc_thursday', true);
        $friday    = get_post_meta(get_the_ID(), 'exc_friday', true);
        $saturday  = get_post_meta(get_the_ID(), 'exc_saturday', true);
        $sunday    = get_post_meta(get_the_ID(), 'exc_sunday', true);

        echo '<ul class="excursion-week">';
        echo '<li class="excursion-week__day ' . $monday . '">' . __('Пн', 'alba-longa') . '</li>';
        echo '<li class="excursion-week__day ' . $tuesday . '">' . __('Вт', 'alba-longa') . '</li>';
        echo '<li class="excursion-week__day ' . $wednesday . '">' . __('Ср', 'alba-longa') . '</li>';
        echo '<li class="excursion-week__day ' . $thursday . '">' . __('Чт', 'alba-longa') . '</li>';
        echo '<li class="excursion-week__day ' . $friday . '">' . __('Пт', 'alba-longa') . '</li>';
        echo '<li class="excursion-week__day ' . $saturday . '">' . __('Сб', 'alba-longa') . '</li>';
        echo '<li class="excursion-week__day ' . $sunday . '">' . __('Вс', 'alba-longa') . '</li>';
        echo '</ul>';
    }
}



// ячейка excursions
if (!function_exists('add_excursion_card')) {
    add_action('excursions', 'add_excursion_card');
    function add_excursion_card()
    {
        $option   = TitanFramework::getInstance('alba');
        $currency = $option->getOption('currency');
        $price    = get_post_meta(get_the_ID(), 'exc_price', true);
        $group    = get_post_meta(get_the_ID(), 'exc_group', true);
        $continue = get_post_meta(get_the_ID(), 'exc_continue', true);
        $mode     = get_post_meta(get_the_ID(), 'exc_mode', true);
        ?>
    <li class="page-list__item list-item">
      <?php add_excursions_thumbnail(); ?>

      <div class="list-item__wrap">
        <div class="list-item__content">

          <?php
                  // заголовок
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

          <?php
        // рейтинг на главной
        if (is_front_page()) {
            if ($option->getOption('excurs-home-cards-loc')) {
                add_rating();
            }
        }

        // рейтинг в архиве
        if (is_post_type_archive('excursions')) {
            if ($option->getOption('archive-excursions-rating-loc')) {
                add_rating();
            }
        }
        ?>

          <div class="list-item__info">
            <?php if ($group) : ?>
              <svg class="list-item__icon">
                <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/sprite.svg#flag"></use>
              </svg>
              <p><?php echo esc_html($group); ?>


                <?php if ($mode) : ?>
                  &mdash;
                  <?php echo esc_html($mode); ?>
                <?php endif; ?>
              </p>
            <?php endif; ?>
          </div>

          <?php if ($continue) : ?>
            <div class="list-item__info">
              <svg class="list-item__icon">
                <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/sprite.svg#clock"></use>
              </svg>
              <p><?php echo _e('Продолжительность', 'alba-longa'); ?>: <?php echo esc_html($continue); ?></p>
            </div>
          <?php endif; ?>

        </div><!-- .list-item__content -->

        <div class="list-item__footer">
          <?php if ($price) : ?>
            <span class="list-item__price"><?php echo esc_html($price) . ' ' . esc_html($currency); ?></span>
          <?php endif; ?>

          <!--<a class="btn" href="<?php the_permalink(); ?>"><?php echo _e('Заказать', 'alba-longa'); ?></a>-->
		  <?php
        if (get_post_meta(get_the_ID(), 'exc_url_textcard', true)) {
            echo '<a class="btn" href="' . get_permalink() . '">' . esc_html(get_post_meta(get_the_ID(), 'exc_url_textcard', true)) . '</a>';
        } else {
            echo '<a class="btn" href="' . get_permalink() . '">' . __('Подробнее', 'alba-longa') . '</a>'; // Иначе стандартная ссылка "Забронировать"
        }
        ?>
        </div><!-- .list-item__footer -->

      </div><!-- .list-item__wrap -->
    </li>
  <?php
    }
}


// рейтинг в отзывах excursions
if (!function_exists('add_excursion_feedback')) {
    add_action('excursions', 'add_excursion_feedback');
    function add_excursion_feedback($rating)
    {
        if ($rating) {
            $stars = '<p class="stars">';
            for ($i = 1; $i <= $rating; $i++) {
                $stars .= '<span class="star-filled"></span>';
            }
            $stars .= '</p>';
            echo $stars;
        }
    }
}



// дополнительные поля формы CF7 для передачи в сообщение информации о заказе
if (function_exists('wpcf7')) {
    function cf7_extra_fields_func()
    {
        if (is_singular('excursions')) {
            $price = get_post_meta(get_the_ID(), 'exc_price', true);
            $html  = '';
            $html .= '<input type="hidden" name="product-title" value="'.get_the_title().'" />';
            $html .= '<input type="hidden" name="exc-price" value="' . esc_attr($price)  . '" />';

            return $html;
        }
    }
    wpcf7_add_form_tag('cf7_extra_fields', 'cf7_extra_fields_func');
}



// другие экскурсии
if (!function_exists('add_related_excursions')) {
    add_action('excursions', 'add_related_excursions');
    function add_related_excursions()
    {
        $option   = TitanFramework::getInstance('alba');
        $currency = $option->getOption('currency');

        $price = get_post_meta(get_the_ID(), 'exc_price', true);
        ?>
    <li class="page-list__item list-item list-item--related">

    <?php add_excursions_thumbnail(); ?>

      <div class="list-item__wrap">
        <div class="list-item__content">

          <?php
                // заголовок
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

          <?php
        if ($option->getOption('cpt-excursions-rating-loc')) {
            add_rating();
        } ?>

        </div><!-- .list-item__content -->
        <div class="list-item__footer">
          <?php if ($price) : ?>
            <span class="list-item__price"><?php echo esc_html($price) . ' ' . esc_html($currency); ?></span>
          <?php endif; ?>
          <a class="btn" href="<?php the_permalink(); ?>"><?php echo _e('Подробнее', 'alba-longa'); ?></a>
        </div><!-- .list-item__footer -->
      </div><!-- .list-item__wrap -->
    </li>
<?php
    }
}



// микроразметка schema.org для экскурсий
if (!function_exists('add_excursions_schema')) {
    add_action('wp_head', 'add_excursions_schema');
    function add_excursions_schema()
    {
        if (is_singular('excursions')) {

            $thumb       = get_post_thumbnail_id();
            $imgUrl      = wp_get_attachment_url($thumb, 'full');
            $price       = get_post_meta(get_the_ID(), 'exc_price', true);
            $future_date = date('Y-m-d', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y')));
            $ratesCount  = get_comments_number(get_the_ID());
            $average     = get_average_ratings(get_the_ID());

            $data = '<script type="application/ld+json">
      {
        "@context": "https://schema.org/", 
        "@type": "Product", 
        "name": "' . get_the_title() . '",
        "image": "' . esc_url($imgUrl) . '",
        "description": "' . add_text_anons(20) . '",
        "brand": {
          "@type": "Brand",
          "name": "' . get_bloginfo('name') . '"
        },
        "sku": "' . get_the_ID() . '",
        "offers": {
          "@type": "Offer",
          "url": "' . get_permalink() . '",
          "priceCurrency": "RUB",
          "price": "' . esc_attr($price) . '",
          "priceValidUntil": "' . esc_attr($future_date) . '",
          "availability": "https://schema.org/PreOrder"
        },
        "aggregateRating": {
          "@type": "AggregateRating",
          "ratingValue": "' . esc_attr($average) . '",
          "bestRating": "5",
          "worstRating": "1",
          "ratingCount": "' . esc_attr($ratesCount) . '"
        }
      }
      </script>';
            echo $data;
        }
    }
}
