<?php

namespace WPDeskInvoicesVendor\WPDesk\License\Page\License\Action;

use WPDeskInvoicesVendor\WPDesk\License\Page\Action;
/**
 * Do nothing.
 *
 * @package WPDesk\License\Page\License\Action
 */
class Nothing implements \WPDeskInvoicesVendor\WPDesk\License\Page\Action
{
    public function execute(array $plugin)
    {
        // NOOP
    }
}
