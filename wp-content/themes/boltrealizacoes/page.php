<?php get_header(); ?>
	
	<div class="container mt-3">
		<div class="row d-flex align-items-center justify-content-center">
			
			<div class="col-md-4 col-12">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="col-md-12 py-3 text-center">
						<h2 class="font-22 color-secundary fw-bold"><?php the_title(); ?></h2>
					</div>
				    <?php the_content(); ?>
				<?php endwhile; endif; ?>
			</div>
			
		</div>
	</div>

<?php get_footer(); ?>