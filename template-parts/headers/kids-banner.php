<?php
/**
 * Баннер «Детям» в шапке сайта
 */

// Пока хардкод. Позже можно вынести в Titan / ACF Options
$banner_image = ''; // URL картинки, например get_stylesheet_directory_uri() . '/assets/img/kids-banner.jpg'
$banner_text  = 'Развлечения и активности для детей';
$banner_btn   = 'Смотреть все';
$banner_url   = get_post_type_archive_link('kids_activities');
?>

<div class="kids-header-banner">
    <?php if ($banner_image) : ?>
        <div class="kids-header-banner__bg" style="background-image: url('<?php echo esc_url($banner_image); ?>')"></div>
    <?php endif; ?>

    <div class="container kids-header-banner__inner">
        <div class="kids-header-banner__content">
            <p class="kids-header-banner__text"><?php echo esc_html($banner_text); ?></p>
            <a href="<?php echo esc_url($banner_url); ?>" class="kids-header-banner__btn">
                <?php echo esc_html($banner_btn); ?>
            </a>
        </div>
    </div>
</div>