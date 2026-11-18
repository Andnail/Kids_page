<?php
/**
 * Блок фильтров раздела «Детям»
 * Категории + сезон + возраст (совместная работа)
 */

$current_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$current_season   = isset($_GET['season']) ? sanitize_text_field($_GET['season']) : '';
$current_age      = isset($_GET['age']) ? sanitize_text_field($_GET['age']) : '';

// Базовый URL архива
$archive_url = get_post_type_archive_link('kids_activities');

if (!$archive_url) {
    $archive_url = home_url('/detjam/');
}

// Категории (из ТЗ + «Проживание» из таблицы)
$categories = [
    ''                      => 'Все',
    'pitanie'               => 'Питание',
    'gornolyzhnyj-otdyh'    => 'Горнолыжный отдых',
    'priroda-i-ekskursii'   => 'Природа и экскурсии',
    'obrazovanie-i-kultura' => 'Образование и культура',
    'razvlecheniya-i-igry'  => 'Развлечения и игры',
    'spa-i-wellness'        => 'SPA и wellness',
    'zimnie-razvlecheniya'  => 'Зимние развлечения',
    'aktivnyj-otdyh'        => 'Активный отдых',
    'prozhivanie'           => 'Проживание',
];

// Сезоны
$seasons = [
    ''            => 'Все',
    'zima'        => 'Зима',
    'leto'        => 'Лето',
    'kruglyj-god' => 'Круглый год',
];

// Возрастные группы (упрощённо по ТЗ и данным таблицы)
$ages = [
    ''    => 'Все',
    '0+'  => '0+',
    '3+'  => 'от 3 лет',
    '5+'  => 'от 5 лет',
    '6+'  => 'от 6 лет',
    '10+' => 'от 10 лет',
];

/**
 * Получить реальные термины из БД
 */
$cat_terms = get_terms([
    'taxonomy'   => 'kids_category',
    'hide_empty' => false,
]);
$season_terms = get_terms([
    'taxonomy'   => 'kids_season',
    'hide_empty' => false,
]);

$use_db_cats    = !is_wp_error($cat_terms) && !empty($cat_terms);
$use_db_seasons = !is_wp_error($season_terms) && !empty($season_terms);

$curr = [
    'category' => $current_category,
    'season'   => $current_season,
    'age'      => $current_age,
];
?>

<div class="kids-filters__group kids-filters__group--category">
    <span class="kids-filters__label">Категория:</span>
    <?php if ($use_db_cats) : ?>
        <a href="<?php echo esc_url(kids_filter_url($archive_url, 'category', '', $curr)); ?>"
           class="kids-filters__btn <?php echo $current_category === '' ? 'is-active' : ''; ?>">
            Все
        </a>
        <?php foreach ($cat_terms as $term) : ?>
            <a href="<?php echo esc_url(kids_filter_url($archive_url, 'category', $term->slug, $curr)); ?>"
               class="kids-filters__btn <?php echo $current_category === $term->slug ? 'is-active' : ''; ?>">
                <?php echo esc_html($term->name); ?>
            </a>
        <?php endforeach; ?>
    <?php else : ?>
        <?php foreach ($categories as $slug => $label) : ?>
            <a href="<?php echo esc_url(kids_filter_url($archive_url, 'category', $slug, $curr)); ?>"
               class="kids-filters__btn <?php echo $current_category === $slug ? 'is-active' : ''; ?>">
                <?php echo esc_html($label); ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="kids-filters__group kids-filters__group--season">
    <span class="kids-filters__label">Сезон:</span>
    <?php if ($use_db_seasons) : ?>
        <a href="<?php echo esc_url(kids_filter_url($archive_url, 'season', '', $curr)); ?>"
           class="kids-filters__btn <?php echo $current_season === '' ? 'is-active' : ''; ?>">
            Все
        </a>
        <?php foreach ($season_terms as $term) : ?>
            <a href="<?php echo esc_url(kids_filter_url($archive_url, 'season', $term->slug, $curr)); ?>"
               class="kids-filters__btn <?php echo $current_season === $term->slug ? 'is-active' : ''; ?>">
                <?php echo esc_html($term->name); ?>
            </a>
        <?php endforeach; ?>
    <?php else : ?>
        <?php foreach ($seasons as $slug => $label) : ?>
            <a href="<?php echo esc_url(kids_filter_url($archive_url, 'season', $slug, $curr)); ?>"
               class="kids-filters__btn <?php echo $current_season === $slug ? 'is-active' : ''; ?>">
                <?php echo esc_html($label); ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="kids-filters__group kids-filters__group--age">
    <span class="kids-filters__label">Возраст:</span>
    <?php foreach ($ages as $slug => $label) : ?>
        <a href="<?php echo esc_url(kids_filter_url($archive_url, 'age', $slug, $curr)); ?>"
           class="kids-filters__btn <?php echo $current_age === $slug ? 'is-active' : ''; ?>">
            <?php echo esc_html($label); ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($current_category || $current_season || $current_age) : ?>
    <div class="kids-filters__group kids-filters__group--reset">
        <a href="<?php echo esc_url($archive_url); ?>" class="kids-filters__btn kids-filters__btn--reset">
            Сбросить фильтры
        </a>
    </div>
<?php endif; ?>
