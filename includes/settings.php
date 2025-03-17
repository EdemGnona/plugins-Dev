<?php
// Empêche l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Ajouter une page de paramètres au menu admin
function pn_add_settings_page() {
    add_options_page(
        'Paramètres Post Notification',
        'Post Notification',
        'manage_options',
        'pn-settings',
        'pn_render_settings_page'
    );
}
add_action('admin_menu', 'pn_add_settings_page');

// Afficher la page des paramètres
function pn_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Paramètres de Post Notification</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('pn_settings_group');
            do_settings_sections('pn-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Enregistrer les paramètres
function pn_register_settings() {
    register_setting('pn_settings_group', 'pn_custom_email');
    register_setting('pn_settings_group', 'pn_email_subject');
    register_setting('pn_settings_group', 'pn_email_content');

    add_settings_section('pn_settings_section', 'Configuration des emails', null, 'pn-settings');

    add_settings_field('pn_custom_email', 'Email personnalisé', 'pn_custom_email_callback', 'pn-settings', 'pn_settings_section');
    add_settings_field('pn_email_subject', 'Sujet de l\'email', 'pn_email_subject_callback', 'pn-settings', 'pn_settings_section');
    add_settings_field('pn_email_content', 'Contenu de l\'email', 'pn_email_content_callback', 'pn-settings', 'pn_settings_section');
}
add_action('admin_init', 'pn_register_settings');

// Callback pour l'email personnalisé
function pn_custom_email_callback() {
    $email = get_option('pn_custom_email', '');
    echo '<input type="email" name="pn_custom_email" value="' . esc_attr($email) . '" class="regular-text">';
}

// Callback pour le sujet de l'email
function pn_email_subject_callback() {
    $subject = get_option('pn_email_subject', 'Nouvel article publié');
    echo '<input type="text" name="pn_email_subject" value="' . esc_attr($subject) . '" class="regular-text">';
}

// Callback pour le contenu de l'email (éditeur WYSIWYG)
function pn_email_content_callback() {
    $content = get_option('pn_email_content', 'Un nouvel article a été publié. Cliquez sur le lien ci-dessous pour le lire.');
    wp_editor($content, 'pn_email_content', array('textarea_name' => 'pn_email_content', 'media_buttons' => false, 'textarea_rows' => 5));
}
