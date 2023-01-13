<?php

namespace WPDeskInvoicesVendor\WPDesk\PluginBuilder\Plugin;

interface HookablePluginDependant extends \WPDeskInvoicesVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Set Plugin.
     *
     * @param AbstractPlugin $plugin Plugin.
     *
     * @return null
     */
    public function set_plugin(\WPDeskInvoicesVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin $plugin);
    /**
     * Get plugin.
     *
     * @return AbstractPlugin.
     */
    public function get_plugin();
}
