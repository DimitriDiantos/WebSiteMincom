<?php

namespace WPDeskInvoicesVendor\WPDesk\PluginBuilder\Plugin;

interface Hookable
{
    /**
     * Init hooks (actions and filters).
     *
     * @return void
     */
    public function hooks();
}
