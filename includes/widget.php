<?php
if (!defined('ABSPATH')) {
    exit; // Sécurité
}

class Post_Newsletter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'post_newsletter_widget',
            __('Post Newsletter Form', 'text_domain'),
            array('description' => __('Formulaire pour s\'inscrire à la newsletter', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo '<h3>' . __('Inscription à la Newsletter', 'text_domain') . '</h3>';
        echo '<form method="post">';
        echo '<p><input type="text" name="gb_nom" placeholder="Votre Nom" required></p>';
        echo '<p><input type="email" name="gb_email" placeholder="Votre Email" required></p>';
        echo '<p><input type="submit" name="submit_gb_email" value="Envoyer"></p>';
        echo wp_nonce_field('gb_email_nonce', 'gb_email_nonce_field', true, false);
        echo '</form>';
        echo $args['after_widget'];

        $this->handle_form_submission();
    }

    public function handle_form_submission() {
        if (isset($_POST['submit_gb_email']) && wp_verify_nonce($_POST['gb_email_nonce_field'], 'gb_email_nonce')) {
            $nom = sanitize_text_field($_POST['gb_nom']);
            $email = sanitize_email($_POST['gb_email']);

            if (!empty($nom) && is_email($email)) {
                $post_data = array(
                    'post_title'  => $nom,
                    'post_type'   => 'gb_email',
                    'post_status' => 'publish'
                );
                $post_id = wp_insert_post($post_data);

                if ($post_id) {
                    update_post_meta($post_id, 'gb_nom', $nom);
                    update_post_meta($post_id, 'gb_email', $email);
                }
            }
        }
    }

    public function form($instance) {
        echo '<p>' . __('Aucun paramètre pour ce widget.', 'text_domain') . '</p>';
    }

    public function update($new_instance, $old_instance) {
        return $new_instance;
    }
}

function register_post_newsletter_widget() {
    register_widget('Post_Newsletter_Widget');
}
add_action('widgets_init', 'register_post_newsletter_widget');
