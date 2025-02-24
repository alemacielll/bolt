<?php get_header(); ?>

<?php if ( is_user_logged_in() && ( current_user_can('administrator') || current_user_can('editor') ) ) { ?>

<div class="container py-3">
    <div class="row align-items-center">

        <!-- Título da página -->
        <div class="col-md-6 col-6 mb-3">
            <h2 class="mb-0"><?php the_title(); ?></h2>
        </div>

        <!-- Formulário de filtro por prova -->
        <div class="col-md-6 col-6 d-flex justify-content-end">
            <form method="GET" class="d-flex align-items-center">
                <select class="form-select me-2" name="modalidade" id="modalidade">
                    <option value="">Todas as Modalidades</option>
                    <option value="Kids" <?php echo (isset($_GET['modalidade']) && $_GET['modalidade'] == 'Kids') ? 'selected' : ''; ?>>Kids</option>
                    <option value="2Km (caminhada)" <?php echo (isset($_GET['modalidade']) && $_GET['modalidade'] == '2Km (caminhada)') ? 'selected' : ''; ?>>2Km (caminhada)</option>
                    <option value="3km" <?php echo (isset($_GET['modalidade']) && $_GET['modalidade'] == '3km') ? 'selected' : ''; ?>>3Km</option>
                    <option value="5km" <?php echo (isset($_GET['modalidade']) && $_GET['modalidade'] == '5km') ? 'selected' : ''; ?>>5Km</option>
					<option value="7km" <?php echo (isset($_GET['modalidade']) && $_GET['modalidade'] == '7km') ? 'selected' : ''; ?>>7Km</option>
					<option value="10km" <?php echo (isset($_GET['modalidade']) && $_GET['modalidade'] == '10km') ? 'selected' : ''; ?>>10Km</option>
                </select>
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
                            $id_evento = get_the_ID();
                            $selected = (isset($_GET['prova']) && $_GET['prova'] == $id_evento) ? 'selected' : '';
                            echo '<option value="' . esc_attr($id_evento) . '" ' . $selected . '>' . esc_html($titulo_evento) . '</option>';
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </select>
                <input type="submit" class="btn btn-warning fw-bold" value="Filtrar">
                <!-- Botão de exportar: redireciona para a mesma página, mas com o parâmetro export_csv=1 -->
                <?php 
                    // Preserva os filtros aplicados para exportar os mesmos dados
                    $query_args = array();
                    if( isset($_GET['prova']) && !empty($_GET['prova']) ){
                        $query_args['prova'] = sanitize_text_field($_GET['prova']);
                    }
                    if( isset($_GET['modalidade']) && !empty($_GET['modalidade']) ){
                        $query_args['modalidade'] = sanitize_text_field($_GET['modalidade']);
                    }
                    $query_args['export_csv'] = '1';
                    $url_export = add_query_arg( $query_args, get_permalink() );
                ?>
                <a href="<?php echo esc_url( $url_export ); ?>" class="btn btn-warning fw-bold ms-2">Exportar</a>
            </form>
        </div>

        <div class="col-md-12 mt-3">
            <?php
            // Recupera o filtro selecionado
            $prova_selecionada = isset($_GET['prova']) ? sanitize_text_field($_GET['prova']) : '';
            $modalidade_selecionada = isset($_GET['modalidade']) ? sanitize_text_field($_GET['modalidade']) : '';

            /**
             * Consulta para totalizar as inscrições e seus valores de acordo com o filtro selecionado
             */
            $args_total = array(
                'posts_per_page' => -1,
                'post_type'      => 'inscricao',
            );
            if ( !empty($prova_selecionada) ) {
                $args_total['meta_query'] = array(
                    array(
                        'key'     => 'id_evento',
                        'value'   => $prova_selecionada,
                        'compare' => '='
                    )
                );
            }
            if ( !empty($modalidade_selecionada) ) {
                $args_total['meta_query'][] = array(
                    'key'     => 'modalidade',
                    'value'   => $modalidade_selecionada,
                    'compare' => '='
                );
            }
            $query_total = new WP_Query( $args_total );

            $total_inscricoes = $query_total->post_count;
            $total_pagos      = 0;
            $total_pendentes  = 0;
            $count_pagos      = 0;
            $count_pendentes  = 0;

            if ( $query_total->have_posts() ) {
                while ( $query_total->have_posts() ) {
                    $query_total->the_post();
                    $valor_final = get_field('valor_final');
                    $situacao    = get_field('pago');
                    
                    if ( $situacao) {
                        $total_pagos += $valor_final;
                        $count_pagos++;
                    } else {
                        $total_pendentes += $valor_final;
                        $count_pendentes++;
                    }
                }
            }
            wp_reset_postdata();
            ?>

            <!-- Cards dinâmicos -->
            <div class="row">
                <!-- Card 1: Total de Inscrições -->
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-0 fw-bold"><?php echo $total_inscricoes; ?></h4>
                            <p class="mb-0 fw-semibold">Total Inscrições</p>
                        </div>
                    </div>
                </div>
                <!-- Card 2: Inscrições Concluídas -->
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-0 fw-bold">R$ <?php echo number_format((float)$total_pagos, 2, ',', '.'); ?></h4>
                            <p class="mb-0 fw-semibold text-success">Concluídas (<?php echo $count_pagos; ?>)</p>
                        </div>
                    </div>
                </div>
                <!-- Card 3: Inscrições Pendentes -->
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-0 fw-bold">R$ <?php echo number_format((float)$total_pendentes, 2, ',', '.'); ?></h4>
                            <p class="mb-0 fw-semibold text-danger">Pendentes (<?php echo $count_pendentes; ?>)</p>
                        </div>
                    </div>
                </div>
                <!-- Card 4: Valor Total Bruto (exemplo) -->
                <?php 
                $valor_total_bruto = 0;
                $args_total_bruto = array(
                    'posts_per_page' => -1,
                    'post_type'      => 'inscricao',
                );
                if ( !empty($prova_selecionada) ) {
                    $args_total_bruto['meta_query'] = array(
                        array(
                            'key'     => 'id_evento',
                            'value'   => $prova_selecionada,
                            'compare' => '='
                        )
                    );
                }
                if ( !empty($modalidade_selecionada) ) {
                    $args_total_bruto['meta_query'][] = array(
                        'key'     => 'modalidade',
                        'value'   => $modalidade_selecionada,
                        'compare' => '='
                    );
                }
                $query_total_bruto = new WP_Query( $args_total_bruto );
                if ( $query_total_bruto->have_posts() ) {
                    while ( $query_total_bruto->have_posts() ) {
                        $query_total_bruto->the_post();
                        $valor_final = get_field('valor_final');
                        $valor_total_bruto += $valor_final;
                    }
                }
                wp_reset_postdata();
                ?>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-0 fw-bold">R$ <?php echo number_format((float)$valor_total_bruto, 2, ',', '.'); ?></h4>
                            <p class="mb-0 fw-semibold">Bruto</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            /**
             * Consulta principal para a listagem das inscrições com paginação
             */
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $args = array(
                'posts_per_page' => -1,
                'post_type'      => 'inscricao',
                'paged'          => $paged
            );
            if ( !empty($prova_selecionada) ) {
                $args['meta_query'] = array(
                    array(
                        'key'     => 'id_evento',
                        'value'   => $prova_selecionada,
                        'compare' => '='
                    )
                );
            }

            if ( !empty($modalidade_selecionada) ) {
                $args['meta_query'][] = array(
                    'key'     => 'modalidade',
                    'value'   => $modalidade_selecionada,
                    'compare' => '='
                );
            }

            $wp_query = new WP_Query( $args );
            ?>

            <?php if ( $wp_query->have_posts() ) : ?>
                <!-- Tabela para exibição das inscrições -->
                <div class="table-responsive pt-3" style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Prova</th>
                                <th>Atleta</th>
                                <th>Modalidade</th>
                                <th>Camiseta</th>
                                <th>Zap</th>
                                <!-- <th>Valor Inscrição</th>
                                <th>Desconto</th> -->
                                <th>Valor</th><!-- 
                                <th>Pesquisa</th> -->
                                <th>Situação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $a = 0; 
                            ?>
                            <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                                <?php 
                                    $a++;
                                    $id_evento         = get_field('id_evento');
                                    $pesquisa          = get_field('pesquisa');
                                    $situacao_inscricao= get_field('pago');
                                    $valor_final       = get_field('valor_final');
                                    $valor_desconto    = get_field('valor_desconto');
                                    $valor             = get_field('valor');
                                ?>
                                <tr class="align-middle">
                                    <td><?php echo $a; ?></td>
                                    <td class="vertical-align-middle"><?php echo get_the_title($id_evento); ?></td>
                                    <td class="vertical-align-middle text-capitalize"><?php the_title(); ?></td>
                                    <td class="vertical-align-middle"><?php the_field('modalidade'); ?></td>
                                    <td class="vertical-align-middle"><?php the_field('camiseta'); ?></td>
                                    <td class="vertical-align-middle">
                                    <?php 
                                        $telefone = get_field('telefone');
                                        if ( $telefone ) {
                                            // Remove todos os caracteres que não são dígitos
                                            $telefone_limpo = preg_replace('/\D+/', '', $telefone);
                                            // Monta o link do WhatsApp com o código do país +55
                                            $whatsapp_link = 'https://wa.me/55' . $telefone_limpo;
                                            // Exibe o link com as classes do Bootstrap, texto "zap" e o ícone do WhatsApp
                                            echo '<a href="' . esc_url($whatsapp_link) . '" target="_blank" class="btn btn-success btn-sm"><i class="bi bi-whatsapp"></i></a>';
                                        }
                                        ?>
                                    </td>

                                    <!-- <td>R$ <?php echo ($valor) ? number_format((float)$valor, 2, ',', '') : ''; ?></td>
                                    <td><?php echo ($valor_desconto) ? 'R$ ' . number_format((float)$valor_desconto, 2, ',', '') : '--'; ?></td> -->
                                    <td class="vertical-align-middle">R$ <?php echo ($valor_final) ? number_format((float)$valor_final, 2, ',', '') : ''; ?></td>
                                    <!-- <td><?php echo ($pesquisa == 'sim') ? 'Respondida' : '--'; ?></td> -->
                                    <td class="vertical-align-middle">
                                        <?php if ( $situacao_inscricao) { ?>
                                            <span class="text-success">Pago</span>
                                        <?php } else { ?>
                                            <span class="text-danger">Pendente</span>
                                        <?php } ?>
                                    </td>
                                    <td><a target="_blank" href="<?php the_permalink(); ?>" class="text-decoration-none btn btn-sm btn-outline-secondary">Visualizar</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Nenhum resultado encontrado.</p>
            <?php endif; ?>

            <?php 
            // Paginação
            $pagination_args = array( 'add_args' => array() );
            if ( !empty($prova_selecionada) ) {
                $pagination_args['add_args']['prova'] = $prova_selecionada;
            }
            if ( !empty($modalidade_selecionada) ) {
                $pagination_args['add_args']['modalidade'] = $modalidade_selecionada;
            }

            wp_pagenavi( $pagination_args ); 
            ?>

            <?php wp_reset_query(); ?>
        </div>

    </div>
</div>

<?php } else { ?>

    <?php wp_redirect( home_url('login') ); exit; ?>

<?php } ?>

<?php get_footer(); ?>
