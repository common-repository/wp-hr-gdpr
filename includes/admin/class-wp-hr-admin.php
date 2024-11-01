<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle all Admin Requests
 *
 * @class wphrgdpr_admin
 * @since 1.0.0
 * @author prism
 */
class wphrgdpr_admin {
 
    /**
     * Constructor.
     */
    public function __construct() {
        add_action('init', array($this, 'wphrgdpr_admin_includes'));
    }
 
    /**
     * include admin assests file
     */
    public function wphrgdpr_admin_includes() {
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/class-wp-hr-admin-assets.php';
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/class-wp-hr-admin-custom-post.php';   
    }
}
return new wphrgdpr_admin();