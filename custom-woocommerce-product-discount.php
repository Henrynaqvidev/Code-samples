<?php
/*
Plugin Name: Custom WooCommerce Product Discount
Description: This plugin adds a discount field to WooCommerce products.
Version: 1.0
Author: Your Name
*/

defined( 'ABSPATH' ) || exit;

class Custom_WooCommerce_Product_Discount {
    
    public function __construct() {
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_discount_field' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_discount_field' ) );
        add_action( 'woocommerce_cart_calculate_fees', array( $this, 'apply_product_discount' ) );
    }
    
    public function add_discount_field() {
        woocommerce_wp_text_input( array(
            'id'            => '_product_discount',
            'label'         => __( 'Discount (%)', 'custom-woocommerce-product-discount' ),
            'placeholder'   => '',
            'desc_tip'      => 'true',
            'description'   => __( 'Enter the discount percentage for this product.', 'custom-woocommerce-product-discount' ),
            'type'          => 'number',
            'custom_attributes' => array(
                'step'  => 'any',
                'min'   => '0'
            )
        ));
    }
    
    public function save_discount_field( $product_id ) {
        $discount = isset( $_POST['_product_discount'] ) ? wc_clean( $_POST['_product_discount'] ) : '';
        update_post_meta( $product_id, '_product_discount', $discount );
    }
    
    public function apply_product_discount() {
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            $discount = get_post_meta( $product->get_id(), '_product_discount', true );
            if ( $discount ) {
                $discount_amount = $product->get_price() * ( $discount / 100 );
                $product->set_price( $product->get_price() - $discount_amount );
            }
        }
    }
    
}

new Custom_WooCommerce_Product_Discount();
