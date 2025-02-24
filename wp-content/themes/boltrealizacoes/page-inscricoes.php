<?php get_header(); ?>

<?php if ( is_user_logged_in() ) {  ?>

<div class="container py-3">
    <div class="row">

	    <div class="col-md-6 col-6 mb-3">
	        <h2>Inscrições</h2>
	    </div>
	    <div class="col-md-6 col-6 mb-3 text-end">
	        <a href="<?php bloginfo('url'); ?>" class="btn fw-bold btn-warning">NOVA INSCRIÇÃO</a>
	    </div>

        <div class="col-md-12">

        	<?php 
				$wp_query = new WP_Query(array(
				    'posts_per_page' => '-1', 
				    'post_type' => 'inscricao',
				    'author' => get_current_user_id()
				));
			?>

			<?php if($wp_query->have_posts()) : ?>
			    <!-- Desktop -->
			    <div class="table-responsive pt-3 border-top d-none d-md-block">
			        <table class="table">
			            <thead>
			                <tr>
			                    <th>Prova/Modalidade</th>
			                    <th>Atleta</th>
			                    <th>Valor</th>
			                    <th>Situação</th>
			                    <th class="text-end">Ações</th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php while($wp_query->have_posts()) : $wp_query->the_post(); ?>
			                    <?php 
			                        $id_evento = get_field('id_evento');
			                        $situacao_inscricao = get_field('pago');
			                        $valor_final = get_field('valor_final');
			                    ?>
			                    <tr class="align-middle">
			                        <td class="vertical-align-middle"><strong><?php echo get_the_title($id_evento); ?></strong>  <br> <span class="text-muted"><?php the_field('modalidade'); ?></span></td>
			                        <td class="vertical-align-middle text-capitalize"><?php the_title(); ?></td>
			                        <td class="vertical-align-middle">R$ <?php if ($valor_final) {echo number_format((float) $valor_final, 2, ',', '');}?></td>
			                        <td class="vertical-align-middle">
			                            <?php if ($situacao_inscricao) { ?>
			                                <span class="text-success">Pago</span>
			                            <?php } else { ?>
			                                <span class="text-danger">Pendente</span>
			                            <?php } ?>
			                        </td>
			                        <td class="text-end vertical-align-middle">
			                            <?php if ($situacao_inscricao) { ?>
			                                <a href="<?php the_permalink(); ?>" class="text-decoration-none btn btn-sm btn-outline-secondary">Visualizar</a>
			                            <?php } else { ?>
			                                <a href="<?php the_permalink(); ?>" class="text-decoration-none btn btn-sm btn-warning fw-bold">Pagar</a>
			                            <?php } ?>
			                        </td>
			                    </tr>
			                <?php endwhile; ?>
			            </tbody>
			        </table>
			    </div>

			    <!-- Mobile -->
			    <div class="d-md-none">
			        <?php while($wp_query->have_posts()) : $wp_query->the_post(); ?>
			            <?php 
			                $id_evento = get_field('id_evento');
			                $situacao_inscricao = get_field('pago');
			                $valor_final = get_field('valor_final');
			            ?>
			            <div class="card mb-3">
			                <div class="card-body">
			                    <div class="mb-2">
			                        <h6 class="mb-0 font-20"><?php echo get_the_title($id_evento); ?></h6>
			                        <small class="text-muted font-16"><?php the_field('modalidade'); ?></small>
			                    </div>
			                    <div class="font-16 text-capitalize mb-2">
			                        <?php the_title(); ?>
			                    </div>
			                    <div class="">
			                        <div class="mb-2">
			                            <strong>R$ <?php if ($valor_final) {echo number_format((float) $valor_final, 2, ',', '');}?></strong> | 
			                            <?php if ($situacao_inscricao) { ?>
			                                <span class="text-success">Pago</span>
			                            <?php } else { ?>
			                                <span class="text-danger">Pendente</span>
			                            <?php } ?>
			                        </div>
			                        <?php if ($situacao_inscricao) { ?>
			                            <a href="<?php the_permalink(); ?>" class="text-decoration-none btn-sm btn btn-outline-secondary">Visualizar</a>
			                        <?php } else { ?>
			                            <a href="<?php the_permalink(); ?>" class="text-decoration-none btn-sm btn btn-warning fw-bold">Pagar</a>
			                        <?php } ?>
			                    </div>
			                </div>
			            </div>
			        <?php endwhile; ?>
			    </div>
			<?php endif; wp_reset_query(); ?>

        </div>

    </div>
</div>

<?php } else { ?>

	<?php wp_redirect(home_url('login')); exit; ?>

<?php } ?>

<?php get_footer(); ?>