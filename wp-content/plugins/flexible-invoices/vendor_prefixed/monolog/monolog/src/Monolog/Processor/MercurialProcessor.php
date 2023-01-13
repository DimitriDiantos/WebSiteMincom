<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jonathan A. Schweder <jonathanschweder@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPDeskInvoicesVendor\Monolog\Processor;

use WPDeskInvoicesVendor\Monolog\Logger;
/**
 * Injects Hg branch and Hg revision number in all records
 *
 * @author Jonathan A. Schweder <jonathanschweder@gmail.com>
 */
class MercurialProcessor implements \WPDeskInvoicesVendor\Monolog\Processor\ProcessorInterface
{
    private $level;
    private static $cache;
    public function __construct($level = \WPDeskInvoicesVendor\Monolog\Logger::DEBUG)
    {
        $this->level = \WPDeskInvoicesVendor\Monolog\Logger::toMonologLevel($level);
    }
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        // return if the level is not high enough
        if ($record['level'] < $this->level) {
            return $record;
        }
        $record['extra']['hg'] = self::getMercurialInfo();
        return $record;
    }
    private static function getMercurialInfo()
    {
        if (self::$cache) {
            return self::$cache;
        }
        $result = \explode(' ', \trim(`hg id -nb`));
        if (\count($result) >= 3) {
            return self::$cache = array('branch' => $result[1], 'revision' => $result[2]);
        }
        return self::$cache = array();
    }
}
