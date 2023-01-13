<?php

namespace WPDeskInvoicesVendor\WPDesk\WC\Helper;

use WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility\HelperFactory;
use WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility\HelperFactoryLegacyV33;
use WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility\HelperFactoryLegacyV27;
class Factory
{
    /**
     * @param $version
     *
     * @return HelperFactory
     */
    public static function create_compatibility_helper_factory($version = WC_VERSION)
    {
        if (\version_compare($version, '2.7', '<')) {
            return new \WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility\HelperFactoryLegacyV27();
        } else {
            return new \WPDeskInvoicesVendor\WPDesk\WC\Helper\Compatibility\HelperFactoryLegacyV33();
        }
    }
}
