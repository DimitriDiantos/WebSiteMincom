<?php

namespace WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility;

use WPDeskInvoicesVendor\WPDesk\WC\Helper\Order\OrderCompatible;
use WPDeskInvoicesVendor\WPDesk\WC\Helper\Product\ProductCompatible;
class HelperFactoryLegacyV33 implements \WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility\HelperFactory
{
    /**
     * @param \WC_Product $product
     *
     * @return ProductCompatible
     */
    public function create_product_helper(\WC_Product $product)
    {
        return new \WPDeskInvoicesVendor\WPDesk\WC\Helper\Product\Compatibility\LegacyV33($product);
    }
    /**
     * @param \WC_Product $product
     *
     * @return OrderCompatible
     */
    public function create_order_helper(\WC_Order $order)
    {
        return new \WPDeskInvoicesVendor\WPDesk\WC\Helper\Order\Compatibility\LegacyV33($order);
    }
}
