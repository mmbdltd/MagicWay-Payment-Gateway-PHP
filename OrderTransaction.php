<?php

class OrderTransaction
{

    public function getRecordQuery($tran_id)
    {
        $sql = "SELECT * from orders WHERE transaction_id='" . $tran_id . "' ORDER BY id DESC LIMIT 1";
        return $sql;
    }

    public function saveTransactionQuery($post_data)
    {
        $name = $post_data['cus_name'];
        $email = $post_data['cus_email'];
        $phone = $post_data['cus_msisdn'];
        $transaction_amount = $post_data['amount'];
        $address = $post_data['cus_country'];
        $transaction_id = $post_data['order_id'];
        $currency = $post_data['currency'];

        $sql = "INSERT INTO orders (name, email, phone, amount, address, status, transaction_id,currency)
                                    VALUES ('$name', '$email', '$phone','$transaction_amount','$address','Pending', '$transaction_id','$currency')";

        return $sql;
    }

    public function updateTransactionQuery($tran_id, $type = 'Complete')
    {
        $sql = "UPDATE orders SET status='$type' WHERE transaction_id='$tran_id'";

        return $sql;
    }
}

