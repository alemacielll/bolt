<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            Eventos
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $wp_query = new WP_Query(array('posts_per_page' => '-1', 'post_type' => 'evento'));?>
                    <?php if($wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); ?>
                    <tr>
                        <td><?php the_title(); ?></td>
                        <td>Ver | Editar</td>
                    </tr>
                    <?php endwhile; else : endif; wp_reset_query(); ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php get_footer(); ?>