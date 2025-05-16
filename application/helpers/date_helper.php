<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('format_datetime_local')) {
    function format_datetime_local($datetime_str)
    {
        if (empty($datetime_str)) return '';

        // Caso contrário, assume que é formato do banco
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $datetime_str);
        return $dt ? $dt->format('Y-m-d\TH:i') : '';
    }
}
