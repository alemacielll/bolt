<?php 

	if (isset($_POST['new_post']) && $_POST['new_post'] == '1') {

	    if (!empty($_POST['post_id']) && !empty($_POST['post_forma_pagamento'])) {
	        $post_id = intval($_POST['post_id']);
	        $post_forma_pagamento = sanitize_text_field($_POST['post_forma_pagamento']); 
	        $redirect_url = !empty($_POST['redirect_url']) ? esc_url_raw($_POST['redirect_url']) : home_url();

	        $new_post = array(
	            'ID' => $post_id,
	            'post_type' => 'inscricao',
	        );

	        $post_update_result = wp_update_post($new_post);

	        if ($post_update_result && !is_wp_error($post_update_result)) {
	            update_post_meta($post_id, 'forma_pagamento', $post_forma_pagamento);
	        }

	        wp_redirect($redirect_url);
	        exit;

	    } else {

	        wp_redirect(home_url());
	        exit;

	    }
	}

?>