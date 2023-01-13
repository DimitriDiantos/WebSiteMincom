<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPDeskInvoicesVendor\Monolog\Handler;

use WPDeskInvoicesVendor\Monolog\Logger;
use WPDeskInvoicesVendor\Monolog\Formatter\NormalizerFormatter;
use WPDeskInvoicesVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \WPDeskInvoicesVendor\Monolog\Handler\AbstractProcessingHandler
{
    private $client;
    public function __construct(\WPDeskInvoicesVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \WPDeskInvoicesVendor\Monolog\Logger::DEBUG, $bubble = \true)
    {
        $this->client = $client;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        $this->client->postDocument($record['formatted']);
    }
    protected function getDefaultFormatter()
    {
        return new \WPDeskInvoicesVendor\Monolog\Formatter\NormalizerFormatter();
    }
}
