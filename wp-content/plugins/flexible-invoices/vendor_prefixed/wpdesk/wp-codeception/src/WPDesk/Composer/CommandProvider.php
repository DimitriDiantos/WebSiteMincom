<?php

namespace WPDeskInvoicesVendor\WPDesk\Composer\Codeception;

use WPDeskInvoicesVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests;
use WPDeskInvoicesVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests;
/**
 * Links plugin commands handlers to composer.
 */
class CommandProvider implements \WPDeskInvoicesVendor\Composer\Plugin\Capability\CommandProvider
{
    public function getCommands()
    {
        return [new \WPDeskInvoicesVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests(), new \WPDeskInvoicesVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests()];
    }
}
