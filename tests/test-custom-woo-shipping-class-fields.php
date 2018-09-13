<?php
/**
 * Class CustomShippingClassFieldsTest
 *
 * @package 1_Woo_Add_Shipping_Class
 */

/**
 * Unit test cases for .
 */
class Custom_Woo_Shipping_Class_Fields_Test extends WP_UnitTestCase
{
    private $CWSC;

    private $shipping_class;

    public function setUp() {
        $this->CWSC = new Custom_Woo_Shipping_Class_Fields();
        //load all hooks
        $this->CWSC->run();

        // the actual taxanomy is somthing else, but the functionality can be tested using any taxanomy
        $this->shipping_class = $this->factory->term->create(array('taxonomy' => 'category', 'slug' => 'test-class'));

    }

    public function tearDown() {
        $this->CWSC = null;
    }

    /**
     * Test if the headers for the new fileds is added properly via the hooks
     *
     * @return void
     */
    public function test_if_new_header_added()
    {

        $initial_columns = array( 'test-column-1'=> 'test-column-1', 'test-column-1' => 'test-column-1' );
        $final_columns = apply_filters('woocommerce_shipping_classes_columns', $initial_columns );

        // Check of the key is update after the above filter.
        $this->assertArrayHasKey('kt-new-field-a', $final_columns);
        $this->assertArrayHasKey('kt-new-field-b', $final_columns);
    }


    /**
     * Test if the fileds html where hooked Propely
     *
     * @return void
     */
    public function test_if_fields_rendered()
    {

        ob_start();
            do_action('woocommerce_shipping_classes_column_kt-new-field-a');
            do_action('woocommerce_shipping_classes_column_kt-new-field-b');
        $fields_html_view = ob_get_clean();


        // Check for a unique piece of string present in the above string
        $this->assertContains('name="fielda[', $fields_html_view);
        $this->assertContains('name="fieldb[', $fields_html_view);

    }


    /**
     * Check if the new data is saved in db
     *
     * @return void
     */
    public function test_if_new_meta_values_saved()
    {
        $data = array('fielda'=>'Test Value A', 'fieldb' => 'Test Value B');

        do_action('woocommerce_shipping_classes_save_class', $this->shipping_class, $data);

        $value_in_db_a = get_term_meta($this->shipping_class, 'fielda', true);
        $value_in_db_b = get_term_meta($this->shipping_class, 'fieldb', true);

        $this->assertEquals($data['fielda'], $value_in_db_a);
        $this->assertEquals($data['fieldb'], $value_in_db_b);
    }


}
