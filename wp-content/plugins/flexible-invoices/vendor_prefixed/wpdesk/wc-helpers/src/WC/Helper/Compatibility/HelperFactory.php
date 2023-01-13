<?php

namespace WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility;

use WPDeskInvoicesVendor\WPDesk\WC\Helper\Order\OrderCompatible;
use WPDeskInvoicesVendor\WPDesk\WC\Helper\Product\ProductCompatible;
interface HelperFactory
{
    /**
     * @param \WC_Product $product
     *
     * @return ProductCompatible
     */
    public function create_product_helper(\WC_Product $product);
    /**
     * @param \WC_Product $product
     *
     * @return OrderCompatible
     */
    public function create_order_helper(\WC_Order $order);
}
