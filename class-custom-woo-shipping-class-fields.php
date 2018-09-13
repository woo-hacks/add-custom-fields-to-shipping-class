<?php
class Custom_Woo_Shipping_Class_Fields
{
    /**
     * Initialize Hooks.
     *
     * @access public
     */
    public function run()
    {

        /**
         * STEP 1 - Display Header
         */
        //loc: woocommerce/includes/admin/settings/class-wc-settings-shipping.php #adding coulmns to list header
        add_filter('woocommerce_shipping_classes_columns', array ($this, 'display_field_name_in_list_header' ), 11, 1);

        /**
         * STEP 2 - Display Fields
         */
        //loc: woocommerce/includes/admin/settings/views/html-admin-page-shipping-classes.php
        // action('woocommerce_shipping_classes_column_'.$class)
        add_action('woocommerce_shipping_classes_column_kt-new-field-a', array($this, 'display_field_view'), 10);
        add_action('woocommerce_shipping_classes_column_kt-new-field-b', array($this, 'display_field_view'), 10);


        /**
         * STEP 3 - Update the data base with the values set
         */
        //loc: woocommerce/includes/class-wc-ajax.php #function to add/update shipping class metas value
        add_action('woocommerce_shipping_classes_save_class', array($this, 'save_field_values'), 11, 2);


        /**
         * STEP 4 - Modify shipping class object to add these field before localization
         */
        //loc: woocommerce/includes/class-wc-shipping.php #function to modified localized shipping class data
        add_filter('woocommerce_get_shipping_classes', array($this, 'modify_shipping_class_object') , 11, 1);

    }



    /**
     * Adding New Columns to List Table's Header
     *
     * @param array $shipping_class_columns
     * @return array modified $shipping_class_columns
     */
    public function display_field_name_in_list_header($shipping_class_columns)
    {

        $shipping_class_columns['kt-new-field-a'] = __('Field A', 'kt-12');
        $shipping_class_columns['kt-new-field-b'] = __('Field B', 'kt-12');

        /** Uncomment the following two line to re-positioning Prodcut Count to the last **/
        // unset($shipping_class_columns['wc-shipping-class-count']);
        // $shipping_class_columns['wc-shipping-class-count'] = __('Product count', 'woocommerce');
        return $shipping_class_columns;
    }


    public function display_field_view()
    {
        /**
         * The fields are rendered in the client side and `data` is a localized variable
         * You can find it in //loc: woocommerce/assets/js/admin/wc-shipping-classes.js
         * Localization code can be found in //loc: woocommerce/includes/admin/settings/views/html-admin-page-shipping-classes.php
         */
        $current_action = current_filter();

        //cropping out the current action name to get only the field name
        $field = str_replace("woocommerce_shipping_classes_column_", "", $current_action);

        switch ($field) {
            case 'kt-new-field-a':
                ?>
					<div class="view">{{ data.fielda }}</div>
					<div class="edit"><input type="text" name="fielda[{{ data.term_id }}]" data-attribute="fielda" value="{{ data.fielda }}" placeholder="<?php esc_attr_e('Text for Field A', 'kt-12'); ?>" /></div>
                <?php
             break;
            case 'kt-new-field-b':
                ?>
                    <div class="view">{{ data.fieldb }}</div>
                    <div class="edit"><input type="text" name="fieldb[{{ data.term_id }}]" data-attribute="fieldb" value="{{ data.fieldb }}" placeholder="<?php esc_attr_e('Text for Field B', 'kt-12'); ?>" /></div>
                <?php
                break;
            default:
                break;
        }
    }



    /**
     * Save Update Fields Values to shipping class meta data
     *
     * @param int $term_id
     * @param array $data
     * @return void
     */
    public function save_field_values($term_id, $data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, array('fielda', 'fieldb'))) {
                update_term_meta($term_id, $key, $value);
            }
        }
    }


    /**
     * Modify Shipping Class default data before localization.
     * This will add the values of new fields from the databse to the view
     *
     * @param array shipping_class array of shipping classes
     * @return array array of modified classes return as stdClass instead
     */
    public function modify_shipping_class_object($shipping_class)
    {
        $classes = [];
        $class_new_fields = array('fielda', 'fieldb');
        foreach ($shipping_class as $key => $class) {
            // convert shipping class object to array
            $data = (array)$class;

            //add new field value to array
            foreach ($class_new_fields as $meta_field) {
                $data[$meta_field] = get_term_meta($class->term_id, $meta_field, true);
            }

            //convert back to object format. But this makes a object of stdClass insteaed, which will also work
            $classes[$key] = (object)$data;
        }
        return $classes;
    }


}
