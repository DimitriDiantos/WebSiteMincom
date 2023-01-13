<?php

namespace WPDeskInvoicesVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \WPDeskInvoicesVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
