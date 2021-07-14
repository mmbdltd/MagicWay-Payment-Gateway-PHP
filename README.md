### MagicWay Payment Gateway Integration - PHP Library


### Library Directory

```
 |-- config/
    |-- config.php
 |-- lib/
    |-- MoMagicAbstraction.php (core file)
    |-- MoMagicInterface.php (core file)
    |-- MoMagicConnector.php (core file)
 |-- pg_redirection/
    |-- cancel.php
    |-- fail.php
    |-- success.php
    |-- ipn.php
 |-- README.md
 |-- orders.sql
 |-- db_connection.php
 |-- checkout_hosted.php
 |-- example_hosted.php
 |-- OrderTransaction.php
```
#### Instructions:

* __Step 1:__ Download and extract the library files into your project

* __Step 2:__ Create a database and import the `orders.sql` table schema. Then set the database credential on `db_connection.php` file.

* __Step 3:__ For Checkout integration, you can update the `checkout_hosted.php` or use a different file according to your need. We have provided a basic sample page from where you can kickstart the payment gateway integration.

* __Step 4:__ When user click Continue to checkout button, redirect customer to payment channel selection page.

* __Step 5:__ For redirecting action from MagicWay Payment gateway, we have also provided sample `success.php`, `cancel.php`, `fail.php` and `ipn.php` files. You can update those files according to your need.

### Contributors

> Arifur Rahman

> info@momagicbd.com
