<?php 
	$user_id = get_current_user_id();
	$post_id = get_the_ID();

	if (isset($_POST['new_post']) == '1') {
		extract($_POST);
		
		$new_post = array(
			'ID' => '',
			'post_type'   =>   'evento',
			'post_author' =>   $user_id,
			'post_title'  =>   $post_title,
			'post_status' =>   'publish',
		);

		$post_id = wp_insert_post($new_post);
		$post = get_post($post_id);

		add_post_meta($post_id, 'cidade_uf', $post_cidade . '-' . $post_estado);
		
		if(isset($_POST['post_data'])){
		    $post_data = sanitize_text_field($_POST['post_data']);
		    $data_formatada = date('d/m/Y', strtotime($post_data));
		    add_post_meta($post_id, 'data', $data_formatada);
		}
		wp_redirect(home_url() . '/painel-provas');
		exit;
	}
?>