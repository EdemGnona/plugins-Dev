<?php
if (!defined('ABSPATH')) {
    exit; // Sécurité
}

// Enregistrer le Custom Post Type "gb_email"
function gb_register_email_cpt() {
    $labels = array(
        'name'               => 'Emails',
        'singular_name'      => 'Email',
        'menu_name'          => 'Emails Newsletter',
        'name_admin_bar'     => 'Email',
        'add_new'            => 'Ajouter un email',
        'add_new_item'       => 'Ajouter un nouvel email',
        'new_item'           => 'Nouvel email',
        'edit_item'          => 'Modifier l’email',
        'view_item'          => 'Voir l’email',
        'all_items'          => 'Tous les emails',
        'search_items'       => 'Rechercher des emails',
        'not_found'          => 'Aucun email trouvé.',
        'not_found_in_trash' => 'Aucun email trouvé dans la corbeille.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'supports'           => array('title'),
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-email'
    );

    register_post_type('gb_email', $args);
}
add_action('init', 'gb_register_email_cpt');

// Ajouter les champs personnalisés "Nom" et "Email"
function gb_add_email_meta_boxes() {
    add_meta_box('gb_email_info', 'Informations Email', 'gb_email_meta_box_callback', 'gb_email', 'normal', 'high');
}
add_action('add_meta_boxes', 'gb_add_email_meta_boxes');

function gb_email_meta_box_callback($post) {
    $nom = get_post_meta($post->ID, 'gb_nom', true);
    $email = get_post_meta($post->ID, 'gb_email', true);
    ?>
    <p>
        <label for="gb_nom">Nom :</label>
        <input type="text" id="gb_nom" name="gb_nom" value="<?php echo esc_attr($nom); ?>" class="widefat" />
    </p>
    <p>
        <label for="gb_email">Email :</label>
        <input type="email" id="gb_email" name="gb_email" value="<?php echo esc_attr($email); ?>" class="widefat" />
    </p>
    <?php
}

// Sauvegarder les champs personnalisés
function gb_save_email_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['gb_nom'])) {
        update_post_meta($post_id, 'gb_nom', sanitize_text_field($_POST['gb_nom']));
    }
    if (isset($_POST['gb_email'])) {
        update_post_meta($post_id, 'gb_email', sanitize_email($_POST['gb_email']));
    }
}
add_action('save_post', 'gb_save_email_meta');
