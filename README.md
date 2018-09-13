<h1> HACK #1 - Adding a custom field to shipping class </h1>

[![Build Status](https://travis-ci.com/woo-hacks/woo-add-shipping-class.svg?branch=master)](https://travis-ci.com/woo-hacks/woo-add-shipping-class)

<p><b>Check the plugin code for usage.</b></p>

>> Dont get overwhelmed! Most of the file in this repo is related to unit testing or integrated build.

<br/>
<strong>Following files are all need to get this running : </strong>

```
woo-add-shipping-class/
├── class-custom-woo-shipping-class-fields.php   (your intrested code)
└── init.php                                     (plugin main file)

```
<hr />
<h4>Steps -</h4>
<hr/>

*STEP 1 - Display Field Name in Header*
```php
//loc: woocommerce/includes/admin/settings/class-wc-settings-shipping.php #adding coulmns to list header
apply_filter('woocommerce_shipping_classes_columns', $shipping_columns_array) ;
```

*STEP 2 - Display Fields*
```php
//loc: woocommerce/includes/admin/settings/views/html-admin-page-shipping-classes.php
do_action('woocommerce_shipping_classes_column_'.$class)
```
![Alt text](screenshot1.png "Display Header Columns and Fields")


*STEP 3 - Update the database with the values set*

```php
//loc: woocommerce/includes/class-wc-ajax.php #function to add/update shipping class metas value
do_action('woocommerce_shipping_classes_save_class', $term_id, $data);
```


*STEP 4 - Modify shipping class object to add these fields' data before localization*
```php
//loc: woocommerce/includes/class-wc-shipping.php #function to modified localized shipping class data
apply_filter('woocommerce_get_shipping_classes', $shipping_classes);
```

![Alt text](screenshot2.png "Save data and Get Saved Data")
