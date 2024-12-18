<?php

add_filter('zcpri/get-condition-type-group-date_time', 'zcpri_get_condition_type_calendar_days_of_month', 10, 2);

if (!function_exists('zcpri_get_condition_type_calendar_days_of_month')) {

    function zcpri_get_condition_type_calendar_days_of_month($list, $args) {
        $list['calendar_days_of_month'] = esc_html__('Days Of Month', 'zcpri-woopricely');
        return $list;
    }

}



add_filter('zcpri/get-condition-type-fields', 'zcpri_get_condition_type_calendar_days_of_month_fields', 10, 2);

if (!function_exists('zcpri_get_condition_type_calendar_days_of_month_fields')) {

    function zcpri_get_condition_type_calendar_days_of_month_fields($fields, $args) {

        $days = array();
        $day_text_format = esc_html__('Day [0]', 'zcpri-woopricely');
        for ($i = 1; $i <= 31; $i++) {
            $day_text = str_replace('[0]', $i, $day_text_format);
            $days[$i] = $day_text;
        }

        $flds = array(
            array(
                'id' => 'compare',
                'type' => 'select2',
                'default' => 'in_list',
                'options' => array(
                    'in_list' => esc_html__('Any in the list', 'zcpri-woopricely'),
                    'none' => esc_html__('None in the list', 'zcpri-woopricely'),
                ),
                'width' => '98%',
                'box_width' => '25%',
            ),
            array(
                'id' => 'days',
                'type' => 'select2',
                'multiple' => true,
                'placeholder' => 'Days of month...',
                'allow_clear' => true,
                'options' => $days,
                'width' => '100%',
                'box_width' => '56%',
            )
        );


        $fields['calendar_days_of_month'] = $flds;
        return $fields;
    }

}

add_filter('zcpri/validate-condition-calendar_days_of_month', 'zcpri_validate_condition_calendar_days_of_month', 10, 3);
if (!function_exists('zcpri_validate_condition_calendar_days_of_month')) {

    function zcpri_validate_condition_calendar_days_of_month($rule, $args) {

        if (!is_array($rule['days'])) {
            return false;
        }

        $date_range = WooPricelyUtil::get_date_from_month_days($rule['compare'], $rule['days']);
        if ($date_range == '') {
            return false;
        }
        $current_date_time = WooPricelyUtil::get_current_date(true);

        $from_is_valid = WooPricely_Validation_Util::validate_date('>=', $current_date_time, $date_range['from'], 'Y-m-d H:i:s', 'Y-m-d H:i:s');
        $to_is_valid = WooPricely_Validation_Util::validate_date('<=', $current_date_time, $date_range['to'], 'Y-m-d H:i:s', 'Y-m-d H:i:s');


        $is_valid = ($from_is_valid == true && $to_is_valid == true);

        if ($is_valid == true) {
            WooPricely_CountDown::set_datetime($date_range['to']);
        }

        return $is_valid;
    }

}