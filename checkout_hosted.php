<?php
# This is a sample page to understand how to connect payment gateway. PLEASE MODIFY AS REQUIRED.
/**
 * set error_reporting level  0 in production mode
 * 0 means no error reported
 */
error_reporting(E_ALL);
/**
 * set display_errors value 0 in production mode
 * 1 means error display in browser
 * 0 means no error display in browser
 */
ini_set('display_errors', 1);

require_once(__DIR__ . "/lib/MoMagicConnector.php");
include("db_connection.php");
include("OrderTransaction.php");

use MoMagic\MoMagicConnector;

# Organize the checkout data
$post_data = array();
# please fill up all the fields
$post_data['currency'] = "BDT"; // string (3)	Mandatory - The currency type must be mentioned.
$post_data['amount'] = isset($_POST['amount']) ? filter_var($_POST['amount'], FILTER_SANITIZE_STRING) : "0.00";
$post_data['amount'] = sprintf("%.2f",ceil($post_data['amount']));
if ($post_data['amount'] < 10){
    echo "Minimum amount must be greater than 10 BDT.";
    die;
}
$post_data['order_id'] = "MMBD_" . uniqid(); // Mandatory-field, you can replace this value with your own value
$post_data['cus_name'] = isset($_POST['customer_name']) ? filter_var($_POST['customer_name'], FILTER_SANITIZE_STRING) : "UNKNOWN";
$post_data['cus_email'] = isset($_POST['customer_email']) ? filter_var($_POST['customer_email'], FILTER_SANITIZE_EMAIL) : "";
if (!filter_var($post_data['cus_email'], FILTER_VALIDATE_EMAIL)) {
    $email = $post_data['cus_email'];
    echo "$email is not a valid email address.";
    die;
}
if (empty($post_data['cus_email'])){
    echo "Please give a valid mail address.";
    die;
}
$post_data['cus_msisdn'] = isset($_POST['customer_mobile']) ? filter_var($_POST['customer_mobile'], FILTER_SANITIZE_STRING) : "";
if (empty($post_data['cus_msisdn'])){
    echo "Please give a valid mobile number.";
    die;
}
$post_data['cus_country'] = isset($_POST['country']) ? filter_var($_POST['country'], FILTER_SANITIZE_STRING) : "BD";
$post_data['cus_state'] = isset($_POST['state']) ? filter_var($_POST['state'], FILTER_SANITIZE_STRING) : "UNKNOWN";
$post_data['cus_city'] = isset($_POST['state']) ? filter_var($_POST['state'], FILTER_SANITIZE_STRING) : "UNKNOWN";
$post_data['cus_postcode'] = isset($_POST['zip']) ? filter_var($_POST['zip'], FILTER_SANITIZE_STRING) : "UNKNOWN";
$post_data['cus_address'] = isset($_POST['address']) ? filter_var($_POST['address'], FILTER_SANITIZE_STRING) : "UNKNOWN";
$post_data['product_name'] = isset($_POST['product_name']) ? filter_var($_POST['product_name'], FILTER_SANITIZE_STRING) : "UNKNOWN";
$post_data['num_of_item'] = isset($_POST['num_of_item']) ? filter_var($_POST['num_of_item'], FILTER_SANITIZE_STRING) : 1;
# First, save the input data into database table `orders`
$query = new OrderTransaction();
$sql = $query->saveTransactionQuery($post_data);

if ($conn_integration->query($sql) === TRUE) {
    $magic_way = new MoMagicConnector();
    # call payment checkout, it will redirect customer vendor payment channel selection page
    $magic_way->make_checkout($post_data);
} else {
    echo "DB_Error: " . $sql . "<br>" . htmlentities($conn_integration->error, ENT_QUOTES, 'UTF-8');
}


