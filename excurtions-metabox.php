<?php
// метаполя
class Excursions
{

    private $config = '
{"title":"Развлечения и отдых","description":"Настройка «Развлечения и отдых»","prefix":"exc_","domain":"theme","class_name":"Excursions","post-type":["excursions"],"context":"normal","priority":"default","cpt":"excursions","fields":[ 
{"type":"text","label":"Текст, отображаемый на кнопке бронирования на подробной странице", "id":"exc_url_textpage"}, 
{"type":"text","label":"Текст, отображаемый на кнопке покупки билета на списке (карточке)", "id":"exc_url_textcard"}, 
{"type":"text","label":"Произвольная ссылка на кнопке заказа", "id":"exc_url"},  
{"type":"text","label":"Содержимое onClick", "id":"exc_onclick"}, 
{"type":"textarea","label":"Стоимость","id":"exc_price"}, 
{"type":"text","label":"Продолжительность","id":"exc_continue"},
	
{"type":"checkbox","label":"По каким дням проводится","description":"Понедельник", "id":"exc_monday"},
	
{"type":"checkbox","label":" ","description":"Вторник", "id":"exc_tuesday"},
	
{"type":"checkbox","label":"","description":"Среда", "id":"exc_wednesday"},
	
{"type":"checkbox","label":"","description":"Четверг", "id":"exc_thursday"},
	
{"type":"checkbox","label":"","description":"Пятница", "id":"exc_friday"},
	
{"type":"checkbox","label":"","description":"Суббота", "id":"exc_saturday"},
	
{"type":"checkbox","label":"","description":"Воскресенье", "id":"exc_sunday"},
	
{"type":"time","label":"Время отправления","default":"12:00","id":"exc_time"},
{"type":"textarea","label":"Адрес отправления","id":"exc_adress"},
{"type":"textarea","label":"Карта с точкой отправления","id":"exc_map"},
{"type":"text","label":"Тип или размер группы","id":"exc_group"}, 
{"type":"text","label":"Как проходит","id":"exc_mode"},
{"type":"checkbox","label":"Топовая экскурсия","description":"Поставьте галочку, чтобы показывать эту карточку на Главной","id":"exc_recommend"},
{"type":"text","label":"Номер телефона", "id":"exc_phone_number"}]}';

    public function __construct()
    {
        $this->config = json_decode($this->config, true);
        $this->process_cpts();
        add_action('add_meta_boxes', [ $this, 'add_meta_boxes' ]);
        add_action('admin_head', [ $this, 'admin_head' ]);
        add_action('save_post', [ $this, 'save_post' ]);
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
                [ $this, 'add_meta_box_callback' ],
                $screen,
                $this->config['context'],
                $this->config['priority']
            );
        }
    }

    public function admin_head()
    {
        global $typenow;

        if (in_array($typenow, $this->config['post-type'])) {
            ?><?php
        }
    }

    public function save_post($post_id)
    {
        foreach ($this->config['fields'] as $field) {
            switch ($field['type']) {
                case 'checkbox':
                    update_post_meta($post_id, $field['id'], isset($_POST[ $field['id'] ]) ? $_POST[ $field['id'] ] : '');
                    break;
                case 'textarea':
                    if (isset($_POST[ $field['id'] ])) {
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
                        $allowed = wp_kses($_POST[ $field['id'] ], $allowed_html);
                        update_post_meta($post_id, $field['id'], $allowed);
                    }
                    break;
                default:
                    if (isset($_POST[ $field['id'] ])) {
                        $sanitized = sanitize_text_field($_POST[ $field['id'] ]);
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
            case 'time':
                $this->input_minmax($field);
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
            isset($field['pattern']) ? "pattern='
{$field['pattern']}'" : '',
            $field['type'],
            $this->value($field)
        );
    }

    private function input_minmax($field)
    {
        printf(
            '<input class="regular-text" id="%s" %s %s name="%s" %s type="%s" value="%s">',
            $field['id'],
            isset($field['max']) ? "max='
{$field['max']}'" : '',
            isset($field['min']) ? "min='
{$field['min']}'" : '',
            $field['id'],
            isset($field['step']) ? "step='
{$field['step']}'" : '',
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

            if ($value === 'on') {
                return 'checked';
            }

            return '';
        } elseif (isset($field['checked'])) {
            return 'checked';
        }

        return '';
    }

}
new Excursions();
