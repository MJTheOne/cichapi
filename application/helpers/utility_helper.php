<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    if (!function_exists('assetUrl()')) {
        function assetUrl($input) {
            return base_url().'assets/' . $input;
        }
    }

    if (!function_exists('ajaxUrl')) {
        function ajaxUrl() {
            return base_url() . 'index.php/ajaxcontroller/actionHandle';
        }
    }

    if (!function_exists('ajaxUrlDefined')) {
        function ajaxUrlDefined($input) {
            return base_url() . 'index.php/ajaxcontroller/' . $input;
        }
    }