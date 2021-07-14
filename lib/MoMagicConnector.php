<?php
# don't touch this page
namespace MoMagic;

require_once(__DIR__ . "/MoMagicAbstraction.php");

class MoMagicConnector extends MoMagicAbstraction
{
    protected $checkout_payload = array();
    protected $config = [];
    private $successUrl;
    private $failedUrl;
    private $cancelUrl;
    private $ipnUrl;


    /**
     * MoMagicConnector constructor.
     */
    public function __construct()
    {
        $this->config = include(__DIR__ . '/../config/config.php');
        $this->setStoreId($this->config['api_credentials']['store_id']);
        $this->setStorePassword($this->config['api_credentials']['store_password']);
        $this->setStoreUserName($this->config['api_credentials']['store_user']);
        $this->setStoreUserEmail($this->config['api_credentials']['store_email']);
        $this->set_checkout_api_url($this->config['api_url'] . $this->config['api_path']['payment_initiate']);
        $this->set_access_token_api_url($this->config['api_url'] . $this->config['api_path']['access_token']);
        $this->set_payment_verification_api_url($this->config['api_url'] . $this->config['api_path']['payment_status']);
    }

    protected function setSuccessUrl()
    {
        $this->successUrl = $this->config['client_application_url'] . '/' . $this->config['success_url'];
    }

    protected function getSuccessUrl()
    {
        return $this->successUrl;
    }

    protected function setFailedUrl()
    {
        $this->failedUrl = $this->config['client_application_url'] . '/' . $this->config['failed_url'];
    }

    protected function getFailedUrl()
    {
        return $this->failedUrl;
    }

    protected function setCancelUrl()
    {
        $this->cancelUrl = $this->config['client_application_url'] . '/' . $this->config['cancel_url'];
    }

    protected function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    protected function setIpnUrl()
    {
        $this->ipnUrl = $this->config['client_application_url'] . '/' . $this->config['ipn_url'];
    }

    protected function getIpnUrl()
    {
        return $this->ipnUrl;
    }

    public function make_checkout(array $requestData)
    {
        if (empty($requestData)) {
            return "Please provide a valid information list.";
        }
        // Set API data
        $this->set_payment_initiate_api_data($requestData);
        // call the Gateway API
        $response = $this->checkout_api($this->checkout_payload);
        // parse the API response data
        $parse_data = $this->parse_checkout_response($response); // Here we will define the response pattern
        if ($parse_data['status']) {
            echo $this->post_redirection($parse_data['checkout_url']);
        } else {
            echo htmlentities($parse_data['message'], ENT_QUOTES, 'UTF-8');
        }
    }

    public function access_token()
    {
        // call the Gateway API
        $response = $this->access_token_api();
        // parse the API response data
        $parse_data = $this->parse_access_token_response($response); // Here we will define the response pattern
        return $parse_data;
    }

    public function validate_payment($opr = "", $order_id = "", $payment_ref_id = "", $access_token = "")
    {
        // call the Gateway API
        $response = $this->payment_verification_api($opr, $order_id, $payment_ref_id, $access_token);
        // parse the API response data
        $parse_data = $this->parse_payment_validation_response($response); // Here we will define the response pattern
        return $parse_data;
    }

    public function set_payment_initiate_api_data(array $info)
    {
        // Set the SUCCESS, FAIL, CANCEL and IPN URL
        $this->setSuccessUrl();
        $this->setFailedUrl();
        $this->setCancelUrl();
        $this->setIpnUrl();
        $this->checkout_payload["store_id"] = $this->getStoreId();
        $this->checkout_payload["amount"] = sprintf("%.2f", $info['amount']);
        $this->checkout_payload["order_id"] = $info['order_id'];
        $this->checkout_payload["success_url"] = $this->getSuccessUrl(); // string (255)	Mandatory - It is the callback URL of your website where user will redirect after successful payment (Length: 255)
        $this->checkout_payload["fail_url"] = $this->getFailedUrl();     // string (255)	Mandatory - It is the callback URL of your website where user will redirect after any failure occure during payment (Length: 255)
        $this->checkout_payload["cancel_url"] = $this->getCancelUrl();   // string (255)	Mandatory - It is the callback URL of your website where user will redirect if user canceled the transaction (Length: 255)
        $this->checkout_payload["ipn_url"] = $this->getIpnUrl();
        $this->checkout_payload["cus_name"] = $info['cus_name'];
        $this->checkout_payload["cus_address"] = $info['cus_address'];
        $this->checkout_payload["cus_country"] = $info['cus_country'];
        $this->checkout_payload["cus_state"] = $info['cus_state'];
        $this->checkout_payload["cus_city"] = $info['cus_city'];
        $this->checkout_payload["cus_postcode"] = $info['cus_postcode'];
        $this->checkout_payload["msisdn"] = $info['cus_msisdn'];
        $this->checkout_payload["email"] = $info['cus_email'];
        $this->checkout_payload["currency"] = $info['currency'];
        $this->checkout_payload["num_of_item"] = $info['num_of_item'];
        $this->checkout_payload["product_name"] = $info['product_name'];
    }
}
