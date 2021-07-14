<?php
/**
 * # THIS FILE IS ONLY AN EXAMPLE. PLEASE MODIFY AS REQUIRED.
 * # Contributor: Arifur Rahman <arifur@momagicbd.com>
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../lib/MoMagicConnector.php";
include_once __DIR__ . "/../db_connection.php";
include_once __DIR__ . "/../OrderTransaction.php";

use MoMagic\MoMagicConnector;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_request_data = file_get_contents('php://input');  // get json data from request
    $post_data = json_decode($api_request_data, true); // convert json to array
} else {
    echo "Bad request. only POST method is allowed.";
    die;
}
if (empty($post_data['order_id'])) {
    echo "Invalid Information.";
    die;
}

$opr = filter_var($post_data['opr'], FILTER_SANITIZE_STRING);
$payment_ref_id = filter_var($post_data['payment_ref_id'], FILTER_SANITIZE_STRING);
$order_id = filter_var($post_data['order_id'], FILTER_SANITIZE_STRING);
$status = filter_var($post_data['status'], FILTER_SANITIZE_STRING);

$magic_way = new MoMagicConnector();
$access_token_response = $magic_way->access_token();
if ($access_token_response['status']) {
    $access_token = $access_token_response['access_token'];
    $payment_validation_response = $magic_way->validate_payment($opr, $order_id, $payment_ref_id, $access_token);
    if ($payment_validation_response['status']) {
        $payment_verification_status = $payment_validation_response['pay_status'] ? 'Processing' : 'Failed';
        $ecom_order_id = $payment_validation_response['ecom_order_id'];
        $ots = new OrderTransaction();
        $sql = $ots->getRecordQuery($ecom_order_id);
        $result = $conn_integration->query($sql);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if (empty($row)) {
            echo "Invalid Order ID.";
            die;
        }
        if ($row['status'] == 'Pending') {
            $sql = $ots->updateTransactionQuery($ecom_order_id, $payment_verification_status);
            if ($conn_integration->query($sql) === true) {
                $message = "Payment Record Updated Successfully";
            } else {
                $message = "Error updating record: " . $conn_integration->error;
            }
        } else if (in_array($row['status'], array('Processing', 'Failed'))) {
            $message = "This order is already processing";
        } else {
            $message = "Payment processing done, please contact with service provider";
        }
    } else {
        $message = $payment_validation_response['message'];
    }
} else {
    $message = $access_token_response['message'];
}
echo htmlentities($message, ENT_QUOTES, 'UTF-8');
die;


