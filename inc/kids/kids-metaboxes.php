<?php
/**
 * Метаполя kids_activities
 * По тому же принципу, что и Excursions: класс + JSON-конфиг
 * Ключи меты: kids_* (без ведущего _, как у exc_*)
 */

class Kids
{

    private $config = '
{
  "title": "Детям",
  "description": "Настройка «Детям»",
  "prefix": "kids_",
  "domain": "theme",
  "class_name": "Kids",
  "post-type": ["kids_activities"],
  "context": "normal",
  "priority": "default",
  "cpt": "kids_activities",
  "fields": [
    {"type": "text", "label": "Организация / объект", "id": "kids_organization"},
    {"type": "textarea", "label": "Стоимость", "id": "kids_price"},
    {"type": "text", "label": "Возраст / ограничения", "id": "kids_age"},
    {"type": "text", "label": "Длительность", "id": "kids_duration"},
    {"type": "textarea", "label": "Время работы / график", "id": "kids_work_time"},
    {"type": "text", "label": "Размер группы", "id": "kids_group"},
    {"type": "textarea", "label": "Адрес", "id": "kids_address"},
    {"type": "text", "label": "Телефон", "id": "kids_phone"},
    {"type": "text", "label": "Сайт", "id": "kids_site"},
    {"type": "text", "label": "VK / соцсеть", "id": "kids_vk"},
    {"type": "textarea", "label": "Ссылки (PDF, документы)", "id": "kids_links"},
    {"type": "text", "label": "Видео (ссылка или ID)", "id": "kids_video"},
    {"type": "checkbox", "label": "Чахкли рекомендует", "description": "Показывать отметку на карточке", "id": "kids_recommend"}
  ]
}';

    public function __construct()
    {
        $this->config = json_decode($this->config, true);
        $this->process_cpts();
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('admin_head', [$this, 'admin_head']);
        add_action('save_post', [$this, 'save_post']);
    }

    public function process_cpts()
    {
        if (!empty($this->config['cpt'])) {
            if (empty($this->config['post-type'])) {
                $this->config['post-type'] = [];
            }
            $parts                     = explode(',', $this->config['cpt']);
            $parts                     = array_map('trim', $parts);
            $this->config['post-type'] = array_merge($this->config['post-type'], $parts);
        }
    }

    public function add_meta_boxes()
    {
        foreach ($this->config['post-type'] as $screen) {
            add_meta_box(
                sanitize_title($this->config['title']),
                $this->config['title'],
                [$this, 'add_meta_box_callback'],
                $screen,
                $this->config['context'],
                $this->config['priority']
            );
        }
    }

    public function admin_head()
    {
        global $typenow;

        if (in_array($typenow, $this->config['post-type'], true)) {
            ?><?php
        }
    }

    public function save_post($post_id)
    {
        if (get_post_type($post_id) !== 'kids_activities') {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        foreach ($this->config['fields'] as $field) {
            switch ($field['type']) {
                case 'checkbox':
                    update_post_meta(
                        $post_id,
                        $field['id'],
                        isset($_POST[$field['id']]) ? $_POST[$field['id']] : ''
                    );
                    break;

                case 'textarea':
                    if (isset($_POST[$field['id']])) {
                        $allowed_html = [
                            'iframe' => [
                                'align'           => true,
                                'width'           => true,
                                'height'          => true,
                                'frameborder'     => true,
                                'name'            => true,
                                'src'             => true,
                                'id'              => true,
                                'class'           => true,
                                'style'           => true,
                                'allowfullscreen' => true,
                                'loading'         => true,
                                'referrerpolicy'  => true,
                                'apikey'          => true,
                            ],
                        ];
                        $allowed = wp_kses($_POST[$field['id']], $allowed_html);
                        update_post_meta($post_id, $field['id'], $allowed);
                    }
                    break;

                default:
                    if (isset($_POST[$field['id']])) {
                        $sanitized = sanitize_text_field($_POST[$field['id']]);
                        update_post_meta($post_id, $field['id'], $sanitized);
                    }
            }
        }
    }

    public function add_meta_box_callback()
    {
        echo '<div class="rwp-description">' . $this->config['description'] . '</div>';
        $this->fields_table();
    }

    private function fields_table()
    {
        ?><table class="form-table" role="presentation">
            <tbody><?php
        foreach ($this->config['fields'] as $field) {
            ?><tr>
                        <th scope="row"><?php $this->label($field); ?></th>
                        <td><?php $this->field($field); ?></td>
                    </tr><?php
        }
        ?></tbody>
        </table><?php
    }

    private function label($field)
    {
        switch ($field['type']) {
            default:
                printf(
                    '<label class="" for="%s">%s</label>',
                    $field['id'],
                    $field['label']
                );
        }
    }

    private function field($field)
    {
        switch ($field['type']) {
            case 'checkbox':
                $this->checkbox($field);
                break;
            case 'textarea':
                $this->textarea($field);
                break;
            default:
                $this->input($field);
        }
    }

    private function checkbox($field)
    {
        printf(
            '<label class="rwp-checkbox-label"><input %s id="%s" name="%s" type="checkbox"> %s</label>',
            $this->checked($field),
            $field['id'],
            $field['id'],
            isset($field['description']) ? $field['description'] : ''
        );
    }

    private function input($field)
    {
        printf(
            '<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
            isset($field['class']) ? $field['class'] : '',
            $field['id'],
            $field['id'],
            isset($field['pattern']) ? "pattern=\"{$field['pattern']}\"" : '',
            $field['type'],
            $this->value($field)
        );
    }

    private function textarea($field)
    {
        printf(
            '<textarea class="regular-text" id="%s" name="%s" rows="%d">%s</textarea>',
            $field['id'],
            $field['id'],
            isset($field['rows']) ? $field['rows'] : 5,
            $this->value($field)
        );
    }

    private function value($field)
    {
        global $post;

        if (metadata_exists('post', $post->ID, $field['id'])) {
            $value = get_post_meta($post->ID, $field['id'], true);
        } elseif (isset($field['default'])) {
            $value = $field['default'];
        } else {
            return '';
        }

        return str_replace('\u0027', "'", $value);
    }

    private function checked($field)
    {
        global $post;

        if (metadata_exists('post', $post->ID, $field['id'])) {
            $value = get_post_meta($post->ID, $field['id'], true);

            if ($value === 'on' || $value === '1') {
                return 'checked';
            }

            return '';
        } elseif (isset($field['checked'])) {
            return 'checked';
        }

        return '';
    }

}

new Kids();
