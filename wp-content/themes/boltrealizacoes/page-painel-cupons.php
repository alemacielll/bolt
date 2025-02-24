<?php get_header(); ?>

<?php if ( is_user_logged_in() && ( current_user_can('administrator') || current_user_can('editor') ) ) { ?>


<div class="container py-3">
    <div class="row align-items-center">

	    <div class="col-md-6 col-6 mb-3">
	        <h2 class="mb-0"><?php the_title(); ?></h2>
	    </div>
	    <div class="col-md-6 col-6 mb-3 text-end">
	        <a href="#" class="btn fw-bold btn-warning" data-bs-toggle="modal" data-bs-target="#ModalCupom">NOVO CUPOM</a>
	    </div>

		<!-- Modal -->
		<div class="modal fade" id="ModalCupom" tabindex="-1" aria-labelledby="Cupom" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<form method="post" autocomplete="off" action="<?php bloginfo('url');?>/insert-cupom">
						<div class="modal-header">
							<h1 class="modal-title fw-bold fs-5" id="Cupom">Adicionar Cupom</h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12 mb-2">
									<label class="fw-bold" for="titulo">Prova</label>
									<select name="post_id_evento" class="form-select">
										<option value="">Selecione</option>
										<?php
										$eventos = new WP_Query(array(
											'post_type'      => 'evento',
											'posts_per_page' => -1,
											'orderby'        => 'title',
											'order'          => 'ASC'
										));

										if ($eventos->have_posts()) :
											while ($eventos->have_posts()) : $eventos->the_post();
												$titulo_evento = get_the_title();
												$id_evento = get_the_ID();
												$selected = (isset($_GET['prova']) && $_GET['prova'] == $id_evento) ? 'selected' : '';
												echo '<option value="' . esc_attr($id_evento) . '" ' . $selected . '>' . esc_html($titulo_evento) . '</option>';
											endwhile;
											wp_reset_postdata();
										endif;
										?>
									</select>
								</div>
								<div class="col-md-6 mb-2">
									<label class="fw-bold" for="titulo">Título</label>
									<input type="text" name="post_title" placeholder="Ex: MARATONA10" class="form-control text-uppercase">
								</div>
								<div class="col-md-6 mb-2">
									<label class="fw-bold" for="titulo">Tipo</label>
									<select name="post_tipo" class="form-select">
										<option value="">Selecione</option>
										<option value="PERCENTAGE">Percentual (%)</option>
										<option value="FIXED">Valor Fixo (R$)</option>
									</select>
								</div>
								<div class="col-md-6 mb-2">
									<label class="fw-bold" for="titulo">Valor</label>
									<input type="number" name="post_valor" placeholder="00" class="form-control">
								</div>
								<div class="col-md-6 mb-2">
									<label class="fw-bold" for="titulo">Quantidade</label>
									<input type="number" name="post_quantidade" placeholder="000" class="form-control">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="post_utilizados" value="0"/>
							<input type="hidden" name="new_post" value="1"/>
							<input type="submit" class="btn btn-warning fw-bold" value="Salvar">
						</div>
					</form>
				</div>
			</div>
		</div>

        <div class="col-md-12">

        	<?php 
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
				    'posts_per_page' => -1, 
				    'post_type' => 'cupom',
				    'paged' => $paged
				);
				$wp_query = new WP_Query($args);
			?>

			<?php if ($wp_query->have_posts()) : ?>
			    <div class="table-responsive pt-3 border-top">
			        <table class="table">
			            <thead>
			                <tr>
			                    <th>Título</th>
								<th>Prova</th>
			                 	<th>Tipo</th>
			                 	<th>Valor</th>
			                 	<th>Quantidade</th>
			                 	<th>Utilizados</th>
			                 	<th class="text-end">Ações</th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
			                	<?php 
			                		$tipo = get_field('tipo');
							    	$valor = get_field('valor');
							    ?>
			                    <tr class="align-middle">
			                    	<td><?php the_title(); ?></td>
									<td>
									    <?php 
									        $id_evento = get_field('id_evento');
									        if ($id_evento) {
									            echo get_the_title($id_evento);
									        } else {
									            echo '--';
									        }
									    ?>
									</td>

			                		<td><?php if ($tipo == 'PERCENTAGE') {
			                			echo "Porcentagem";
			                		} elseif ($tipo == 'FIXED') {
			                			echo "Valor";
			                		} ?></td>
			                		<td>
			                			<?php if ($tipo == 'PERCENTAGE') {
				                			echo $valor . '%';
				                		} elseif ($tipo == 'FIXED') {
				                			echo 'R$ ' . number_format((float) $valor, 2, ',', '');
				                		} ?>
				                	</td>
			                		<td><?php the_field('quantidade'); ?></td>
			                		<td><?php the_field('utilizados'); ?></td>
									<td class="text-end">
									    <?php 
									        $utilizados = get_field('utilizados');

									        if (empty($utilizados)) :
									    ?>
									    <form method="post" action="<?php bloginfo('url'); ?>/insert-cupom">
										    <input type="hidden" name="delete_post" value="1">
										    <input type="hidden" name="post_id" value="<?php the_ID();?>">
										    <input type="submit" class="btn btn-outline-secondary btn-sm" value="Excluir Cupom">
										</form>
									    <?php endif; ?>
									</td>
			                    </tr>
			                <?php endwhile; ?>
			            </tbody>
			        </table>
			    </div>
			
			<?php else: ?>
			    <p>Nenhum resultado encontrado.</p>
			<?php endif; ?>

			<?php wp_reset_query(); ?>

        </div>

    </div>
</div>

<?php } else { ?>

	<?php wp_redirect(home_url('login')); exit; ?>

<?php } ?>

<?php get_footer(); ?>