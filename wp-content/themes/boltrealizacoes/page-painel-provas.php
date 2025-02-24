<?php get_header(); ?>

<?php if ( is_user_logged_in() && ( current_user_can('administrator') || current_user_can('editor') ) ) { ?>


<div class="container py-3">
    <div class="row">

	    <div class="col-md-6 col-6 mb-3">
	        <h2><?php the_title(); ?></h2>
	    </div>
	    <div class="col-md-6 col-6 mb-3 text-end">
	        <a href="<?php bloginfo('url');?>/adicionar-prova" class="btn fw-bold btn-warning">NOVA PROVA</a>
	    </div>

        <div class="col-md-12">

        	<?php 
				$wp_query = new WP_Query(array(
				    'posts_per_page' => '-1', 
				    'post_type' => 'evento',
				));
			?>

			<?php if($wp_query->have_posts()) : ?>
			    <!-- Desktop -->
			    <div class="table-responsive pt-3 border-top d-none d-md-block">
			        <table class="table">
			            <thead>
			                <tr>
			                    <th>Prova</th>
			                    <th>Data</th>
			                    <th>Cidade/Estado</th>
<!-- 								<th>Inscritos</th> -->
<!-- 			                    <th class="text-end">Ações</th> -->
			                </tr>
			            </thead>
			            <tbody>
			                <?php while($wp_query->have_posts()) : $wp_query->the_post(); ?>
			                    <?php 
			                        $id_evento = get_field('id_evento');
			                        $situacao_inscricao = get_field('situacao_pagamento');
			                        $valor_final = get_field('valor_final');
			                    ?>
			                    <tr class="align-middle">
			                        <td class="vertical-align-middle text-capitalize"><?php the_title(); ?></td>
			                        <td>
			                        	<?php the_field('data'); ?>
			                        </td>
			                        <td>
			                        	<?php the_field('cidade_uf'); ?>
			                        </td>
<!-- 									<td>
			                        	<?php $total_utilizados = 0;
										if (have_rows('lotes')):
											while (have_rows('lotes')): the_row();
												$utilizados = get_sub_field('utilizados');
												$total_utilizados += intval($utilizados);
											endwhile;
										endif;
										echo $total_utilizados;
										?>
			                        </td> -->
<!-- 			                        <td class="text-end">
			                        	<a class="text-decoration-none btn btn-sm btn-outline-secondary" href="#">Editar</a>
			                        </td> -->
			                    </tr>
			                <?php endwhile; ?>
			            </tbody>
			        </table>
			    </div>
			<?php endif; wp_reset_query(); ?>


        </div>

    </div>
</div>

<?php } else { ?>

	<?php wp_redirect(home_url('login')); exit; ?>

<?php } ?>

<?php get_footer(); ?>