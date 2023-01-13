<?php

namespace WPDeskInvoicesVendor\WPDesk\Composer\Codeception;

use WPDeskInvoicesVendor\Composer\Composer;
use WPDeskInvoicesVendor\Composer\IO\IOInterface;
use WPDeskInvoicesVendor\Composer\Plugin\Capable;
use WPDeskInvoicesVendor\Composer\Plugin\PluginInterface;
/**
 * Composer plugin.
 *
 * @package WPDesk\Composer\Codeception
 */
class Plugin implements \WPDeskInvoicesVendor\Composer\Plugin\PluginInterface, \WPDeskInvoicesVendor\Composer\Plugin\Capable
{
    /**
     * @var Composer
     */
    private $composer;
    /**
     * @var IOInterface
     */
    private $io;
    public function activate(\WPDeskInvoicesVendor\Composer\Composer $composer, \WPDeskInvoicesVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    public function getCapabilities()
    {
        return [\WPDeskInvoicesVendor\Composer\Plugin\Capability\CommandProvider::class => \WPDeskInvoicesVendor\WPDesk\Composer\Codeception\CommandProvider::class];
    }
}
