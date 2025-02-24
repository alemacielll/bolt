<?php 
	$user_id = get_current_user_id();
	$post_id = get_the_ID();

	if (isset($_POST['new_post']) == '1') {
		extract($_POST);
		
		$new_post = array(
			'ID' => '',
			'post_type'   =>   'pesquisa',
			'post_author' =>   $user_id,
			'post_title'  =>   $post_title,
			'post_status' =>   'publish',
			'post_category'=> array($category),
		);

		$post_id = wp_insert_post($new_post);
		$post = get_post($post_id);

		add_post_meta($post_id, 'decisao', $post_decisao);
		add_post_meta($post_id, 'investimento', $post_investimento);
		add_post_meta($post_id, 'tempo_prova', $post_tempo_prova);
		add_post_meta($post_id, 'fator_destino', $post_fator_destino);
		add_post_meta($post_id, 'tempo_destino', $post_tempo_destino);
		add_post_meta($post_id, 'modalidade', $post_modalidade);
		add_post_meta($post_id, 'prova', $post_evento);

		if (isset($_POST['post_id_inscricao'])) {
		    $post_id = intval($_POST['post_id_inscricao']);
		    update_post_meta($post_id, 'pesquisa', 'sim'); // Atualiza o campo "pesquisa" com "sim"
		    wp_redirect(home_url() . '/?p=' . $post_id);
		    exit;
		}
	}
?>