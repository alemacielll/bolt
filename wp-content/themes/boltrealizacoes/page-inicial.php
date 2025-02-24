<?php get_header(); ?>

<div class="bg-dark py-3 position-relative" style="background: url('<?php bloginfo('template_url');?>/images/corrida.jpeg') center center; background-size: cover;">
    <div class="overlay"></div>
    <div class="container py-3">
        <div class="row py-3">
            <div class="col-md-12 text-center">
                <?php if (is_user_logged_in()) { ?>
                <h3 class="text-light">Olá, <?php echo esc_html($current_user->display_name); ?></h3>
                <?php } ?>
                <h2 class="text-light fw-bold">ESCOLHA SEU PRÓXIMO DESAFIO</h2>
            </div>
        </div>
    </div>
</div>

<div class="container py-3">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs justify-content-center mb-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold font-18 active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">ABERTAS</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link font-18 fw-bold" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">ENCERRADAS</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    <div class="row">
                        <?php 
                        $hoje = date('Ymd');

                        $wp_query = new WP_Query(array(
                            'posts_per_page' => -1,
                            'post_type'      => 'evento',
                            'meta_key'       => 'data',
                            'meta_value'     => $hoje,
                            'meta_compare'   => '>=',
                            'orderby'        => 'meta_value',
                            'order'          => 'ASC'
                        ));

                        if($wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); 
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <a href="<?php bloginfo('url');?>/adicionar-inscricao?id_evento=<?php the_ID(); ?>">
                                    <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="Descrição da imagem">
                                </a>
                                <div class="card-body text-center">
                                    <h2 class="font-20 fw-bold mb-0 text-uppercase"><?php the_title(); ?></h2>
                                    <p class="font-16 mb-0 text-uppercase"><?php the_field('data'); ?></p>
                                    <p class="font-14 mb-0 text-muted text-uppercase"><?php the_field('cidade_uf'); ?></p>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex row justify-content-center">
                                        <div class="col text-end">
                                            <?php if( get_field('link_regulamento') ): ?>
                                                <a target="_blank" class="btn btn-outline-secondary text-uppercase" href="<?php the_field('link_regulamento'); ?>">Regulamento</a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col">
                                            <a class="btn btn-warning text-uppercase fw-bold" href="<?php bloginfo('url');?>/adicionar-inscricao?id_evento=<?php the_ID(); ?>">Inscreva-se</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; else : endif; wp_reset_query(); ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                    <div class="row">
                        <?php 
                        $hoje = date('Ymd');

                        $wp_query = new WP_Query(array(
                            'posts_per_page' => -1,
                            'post_type'      => 'evento',
                            'meta_key'       => 'data',
                            'meta_value'     => $hoje,
                            'meta_compare'   => '<=',
                            'orderby'        => 'meta_value',
                            'order'          => 'ASC'
                        ));

                        if($wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); 
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <a href="<?php bloginfo('url');?>/adicionar-inscricao?id_evento=<?php the_ID(); ?>">
                                    <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="Descrição da imagem">
                                </a>
                                <div class="card-body text-center">
                                    <h2 class="font-20 fw-bold mb-0 text-uppercase"><?php the_title(); ?></h2>
                                    <p class="font-16 mb-0 text-uppercase"><?php the_field('data'); ?></p>
                                    <p class="font-14 mb-0 text-muted text-uppercase"><?php the_field('cidade_uf'); ?></p>
                                </div>
                                <div class="card-footer">
                                    <div class="row d-flex align-items-center justify-content-center">
                                        <div class="col-md-4 text-center">
                                            <?php if( get_field('link_resultado') ): ?>
                                                <a target="_blank" class="btn btn-warning text-uppercase fw-bold" href="<?php the_field('link_resultado'); ?>">Resultado</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; else : endif; wp_reset_query(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>