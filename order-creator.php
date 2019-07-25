<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('OrderCreator')) {

    class OrderCreator
    {
        protected $product_id;
        protected $product_value;
        protected $client_id;
        protected $order;
        protected $fee;

        public function __construct()
        {
            global $woocommerce;
            $this->set_wc_create_order();
        }

        public function create_order()
        {
            $this->add_user_to_order();
            $this->add_product_to_order();
            $this->set_wc_order_fee();
            $this->add_fee_to_order();
        }

        public function set_product_id($id)
        {
            $this->product_id = $id;
        }

        public function set_product_value($value)
        {
            $this->product_value = $value;
        }

        public function set_client_id($id)
        {
            $this->client_id = $id;
        }

        protected function set_wc_order_fee()
        {
            $fee = new WC_Order_Item_Fee();
            $fee->set_name("FEE_NAME");
            $fee->set_total($this->get_product_value());
            $this->fee = $fee;
        }

        protected function get_wc_order_fee()
        {
            return $this->fee;
        }

        protected function add_fee_to_order()
        {
            $this->get_order()->add_item($this->get_wc_order_fee());
            $this->get_order()->calculate_totals();
            $this->get_order()->save();
        }

        protected function add_product_to_order()
        {
            $this->get_order()->add_product($this->get_product());
        }

        protected function add_user_to_order()
        {
            update_post_meta($this->get_order_id(), '_customer_user', $this->get_client_id());
        }

        protected function set_wc_create_order()
        {
            $this->order = wc_create_order();
        }

        protected function get_order()
        {
            return $this->order;
        }

        protected function get_order_id()
        {
            return $this->order->get_id();
        }

        protected function get_product()
        {
            return wc_get_product($this->get_product_id());
        }

        protected function get_product_id()
        {
            return $this->product_id;
        }

        protected function get_product_value()
        {
            return $this->product_value;
        }

        protected function get_client_id()
        {
            return $this->client_id;
        }
    }
}
