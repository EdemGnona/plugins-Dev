<?php
if (!defined('ABSPATH')) {
    exit; // Sécurité
}

// Fonction pour envoyer des notifications par email lors de la publication d'un article
function gb_send_post_notification($post_id) {
    if (wp_is_post_revision($post_id)) {
        return;
    }

    $post = get_post($post_id);

    if ($post->post_status !== 'publish') {
        return;
    }

    $custom_email = get_option('gb_custom_email');
    $emails = array();

    // Récupérer les emails du Custom Post Type gb_email
    $email_query = new WP_Query(array(
        'post_type'      => 'gb_email',
        'posts_per_page' => -1
    ));

    if ($email_query->have_posts()) {
        while ($email_query->have_posts()) {
            $email_query->the_post();
            $email = get_post_meta(get_the_ID(), 'gb_email', true);
            if (is_email($email)) {
                $emails[] = $email;
            }
        }
    }

    wp_reset_postdata();

    // Ajouter l'email personnalisé
    if (is_email($custom_email)) {
        $emails[] = $custom_email;
    }

    if (empty($emails)) {
        return;
    }

    $subject = 'Nouvel article publié : ' . $post->post_title;
    $message = file_get_contents(plugin_dir_path(__FILE__) . '../templates/email-template.php');
    $message = str_replace('{{content}}', $post->post_content, $message);
    $message = str_replace('{{title}}', $post->post_title, $message);
    $message = str_replace('{{link}}', get_permalink($post_id), $message);

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($emails, $subject, $message, $headers);
}
add_action('publish_post', 'gb_send_post_notification');
