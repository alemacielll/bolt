<?php get_header(); ?>

<?php if ( is_user_logged_in() ) {  ?>

<div class="container py-3">
    <div class="row">

	    <div class="row align-items-center">
		    <div class="col-md-6 col-6 mb-3">
		        <h2 class="mb-0"><?php the_title(); ?></h2>
		    </div>

		    <div class="col-md-6 col-6 d-flex justify-content-end">
			    <form method="GET" class="d-flex align-items-center">
			        <select class="form-select me-2" name="prova" id="prova">
			            <option value="">Todas as Provas</option>
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
			                    $selected = (isset($_GET['prova']) && $_GET['prova'] == $titulo_evento) ? 'selected' : '';
			                    echo '<option value="' . esc_attr($titulo_evento) . '" ' . $selected . '>' . esc_html($titulo_evento) . '</option>';
			                endwhile;
			                wp_reset_postdata();
			            endif;
			            ?>
			        </select>
			        <input type="submit" class="btn btn-warning fw-bold" value="Filtrar">
			        <?php
			            // Preserva os filtros aplicados
			            $query_args = array();
			            if( isset($_GET['prova']) && !empty($_GET['prova']) ){
			                $query_args['prova'] = sanitize_text_field($_GET['prova']);
			            }
			            $query_args['export_csv_pesquisa'] = '1';
			            $url_export = add_query_arg( $query_args, get_permalink() );
			        ?>
			        <a href="<?php echo esc_url( $url_export ); ?>" class="btn btn-warning fw-bold ms-2">Exportar</a>
			    </form>
			</div>

		</div>

        <div class="col-md-12">

        	<?php 
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$prova_selecionada = isset($_GET['prova']) ? sanitize_text_field($_GET['prova']) : '';

				$args = array(
				    'posts_per_page' => 10, 
				    'post_type' => 'pesquisa',
				    'paged' => $paged
				);

				// Se um evento (prova) foi selecionado, filtrar pelo campo "prova"
				if (!empty($prova_selecionada)) {
				    $args['meta_query'] = array(
				        array(
				            'key' => 'prova',  // Nome do campo ACF onde está armazenado o título da prova
				            'value' => $prova_selecionada,
				            'compare' => '='
				        )
				    );
				}

				$wp_query = new WP_Query($args);
			?>

			<?php if ($wp_query->have_posts()) : ?>
			    <!-- Desktop -->
			    <div class="table-responsive pt-3 border-top d-none d-md-block">
			        <table class="table">
			            <thead>
			                <tr>
			                    <th>Prova</th>
			                    <th>Modalidade</th>
			                    <th>Atleta</th>
			                    <th>O que mais influenciou sua decisão de participar desta prova?</th>
			                    <th>Qual foi o valor aproximado que você investiu para participar deste evento?</th>
			                    <th>Quanto tempo você pretende permanecer no local do evento no dia da prova?</th>
			                    <th>O local ou destino do evento foi um fator decisivo para a sua escolha de participação?</th>
			                    <th>Por quanto tempo você pretende permanecer no destino do evento?</th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
			                    <tr class="align-middle">
			                    	<td><?php the_field('prova'); ?></td>
			                		<td><?php the_field('modalidade'); ?></td>
			                		<td><?php the_title(); ?></td>
			                        <td class="vertical-align-middle text-capitalize"><?php the_field('decisao'); ?></td>
			                        <td class="vertical-align-middle text-capitalize"><?php the_field('investimento'); ?></td>
			                        <td class="vertical-align-middle text-capitalize"><?php the_field('tempo_prova'); ?></td>
			                        <td class="vertical-align-middle text-capitalize"><?php the_field('fator_destino'); ?></td>
			                        <td class="vertical-align-middle text-capitalize"><?php the_field('tempo_destino'); ?></td>
			                    </tr>
			                <?php endwhile; ?>
			            </tbody>
			        </table>
			    </div>
			
			<?php else: ?>
			    <p>Nenhum resultado encontrado.</p>
			<?php endif; ?>
			
			<?php 
			$pagination_args = array('add_args' => array());
			if (!empty($evento_id)) {
			    $pagination_args['add_args']['evento_id'] = $evento_id;
			}

			wp_pagenavi($pagination_args); 
			?>

			<?php wp_reset_query(); ?>

        </div>

    </div>
</div>

<?php } else { ?>

	<?php wp_redirect(home_url('login')); exit; ?>

<?php } ?>

<?php get_footer(); ?>