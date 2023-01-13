<?php

namespace WPDeskInvoicesVendor\WPDesk\Composer\Codeception\Commands;

use WPDeskInvoicesVendor\Composer\Command\BaseCommand as CodeceptionBaseCommand;
use WPDeskInvoicesVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Base for commands - declares common methods.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
abstract class BaseCommand extends \WPDeskInvoicesVendor\Composer\Command\BaseCommand
{
    /**
     * @param string $command
     * @param OutputInterface $output
     */
    protected function execAndOutput($command, \WPDeskInvoicesVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        \passthru($command);
    }
}
