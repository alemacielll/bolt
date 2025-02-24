<?php 
    $user_id = get_current_user_id();

    // requisição para exclusão
    if ( isset($_POST['delete_post']) && $_POST['delete_post'] == '1' ) {
        if ( isset($_POST['post_id']) ) {
            $post_id = intval($_POST['post_id']);
            wp_delete_post($post_id, true);
            wp_redirect(home_url() . '/painel-cupons');
            exit;
        }
    }
    
    // requisição para inserção
    if ( isset($_POST['new_post']) && $_POST['new_post'] == '1' ) {
        $post_title = sanitize_text_field($_POST['post_title']);
        
        $post_id_evento  = $_POST['post_id_evento'];
		$post_tipo       = sanitize_text_field($_POST['post_tipo']);
        $post_valor      = sanitize_text_field($_POST['post_valor']);
        $post_quantidade = sanitize_text_field($_POST['post_quantidade']);
        $post_utilizados = sanitize_text_field($_POST['post_utilizados']);
        
        $new_post = array(
            'post_type'   => 'cupom',
            'post_author' => $user_id,
            'post_title'  => $post_title,
            'post_status' => 'publish',
        );

        $post_id = wp_insert_post($new_post);

        add_post_meta($post_id, 'id_evento', $post_id_evento);
		add_post_meta($post_id, 'tipo', $post_tipo);
        add_post_meta($post_id, 'valor', $post_valor);
        add_post_meta($post_id, 'quantidade', $post_quantidade);
        add_post_meta($post_id, 'utilizados', $post_utilizados);

        wp_redirect(home_url() . '/painel-cupons');
        exit;
    }
?>