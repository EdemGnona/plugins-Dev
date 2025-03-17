<?php
/**
 * Plugin Name: Post Notification
 * Plugin URI: https://example.com/
 * Description: Envoie des notifications par email à chaque publication d'un article.
 * Version: 1.0
 * Author: GNONA BIOVA
 * Author URI: https://example.com/
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Sécurité
}

// Inclure les fichiers nécessaires
require_once plugin_dir_path(__FILE__) . 'includes/cpt-email.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/widget.php';

// Activation du plugin
function pn_activate() {
    gb_register_email_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'pn_activate');

// Désactivation du plugin
function pn_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'pn_deactivate');
