<?php
/**
 * Replace this value with your project URL
 */
if (!defined('CLIENT_APPLICATION_URL')) {
    define('CLIENT_APPLICATION_URL', 'http://localhost:8080/magicway-php-plugin');
}
/**
 * In production mode replace below value with live credentials
 * To get live credentials contact info@momagicbd.com
 */
if (!defined('API_URL')) {
    define('API_URL', 'https://sandbox.magicway.io');
}

if (!defined('STORE_ID')) {
    define('STORE_ID', '1b4a19e331aa653945ec0cf8b864wd12');
}

if (!defined('STORE_PASSWORD')) {
    define('STORE_PASSWORD', 'd61792c501a97b3598dcabc1aaf223wq');
}

if (!defined('STORE_USER')) {
    define('STORE_USER', 'sandbox');
}
if (!defined('STORE_EMAIL')) {
    define('STORE_EMAIL', 'sandbox@xyz.com');
}
/**
 * can be add more ip in near future, in this case use coma separator string
 * it's value must be string
 * because Constant values can only be strings and numbers
 */
if (!defined('VENDOR_WHITE_LIST_IP')) {
    define('VENDOR_WHITE_LIST_IP', 'xxx.xx.xx.xx');
}

return [
    'client_application_url' => constant("CLIENT_APPLICATION_URL"),
    'api_credentials' => [
        'store_id' => constant("STORE_ID"),
        'store_password' => constant("STORE_PASSWORD"),
        'store_user' => constant("STORE_USER"),
        'store_email' => constant("STORE_EMAIL")
    ],
    'api_url' => constant("API_URL"),
    'api_path' => [
        'payment_initiate' => "/api/V1/payment-initiate",
        'access_token' => "/api/V1/auth/token",
        'payment_status' => "/api/V1/charge/status"
    ],
    'success_url' => 'pg_redirection/success.php',
    'failed_url' => 'pg_redirection/fail.php',
    'cancel_url' => 'pg_redirection/cancel.php',
    'ipn_url' => 'pg_redirection/ipn.php',
    'vendor_white_list_ip' => constant('VENDOR_WHITE_LIST_IP')
];
