<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<?php wp_head(); ?>
</head>


<?php 
    if (!is_null($post)) {
        $post_slug = $post->post_name;
        $type = get_post_type();
    }
?>

<body <?php body_class('d-flex flex-column min-vh-100'); ?>>
    <main class="flex-grow-1">
	<?php if (is_user_logged_in()) { ?>
	<nav class="navbar py-2 navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php bloginfo('url');?>">
                <img src="<?php bloginfo('template_url');?>/images/logo-bolt.webp" width="100" alt="Bolt Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (current_user_can('administrator') || current_user_can('editor')) { ?>
                    <li class="nav-item py-2 dropdown">
                        <a class="nav-link <?php echo ($post_slug=='painel-inscricoes' or $post_slug=='painel-cupons' or $post_slug=='painel-provas' or $post_slug=='painel-pesquisas')?'active fw-bold':''; ?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ADMIN
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item <?php echo ($post_slug=='painel-inscricoes')?'active fw-bold':''; ?>" href="<?php bloginfo('url');?>/painel-inscricoes">Inscrições</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo ($post_slug=='painel-provas')?'active fw-bold':''; ?>" href="<?php bloginfo('url');?>/painel-provas">Provas</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo ($post_slug=='pesquisas')?'active fw-bold':''; ?>" href="<?php bloginfo('url');?>/pesquisas">Pesquisas</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo ($post_slug=='painel-cupons')?'active fw-bold':''; ?>" href="<?php bloginfo('url');?>/painel-cupons">Cupons</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="nav-item py-2">
                        <a class="nav-link <?php echo ($post_slug=='inicial')?'active fw-bold':''; ?>" href="<?php bloginfo('url');?>">PROVAS</a>
                    </li>
                    <li class="nav-item py-2">
                        <a class="nav-link" href="<?php bloginfo('url');?>/inscricoes">INSCRIÇÕES</a>
                    </li>
                    <li class="nav-item py-2">
                        <a class="nav-link" href="<?php echo wp_logout_url(); ?>">SAIR</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

	<?php } else { ?>

	<nav class="navbar py-2 navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php bloginfo('url');?>">
                <img src="<?php bloginfo('template_url');?>/images/logo-bolt.webp" width="100" alt="Bolt Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarNav">
			    <ul class="navbar-nav d-flex align-items-center">
			        <li class="nav-item">
			            <a class="nav-link" href="<?php bloginfo('url');?>/login">ENTRAR</a>
			        </li>
			        <li class="nav-item">
			            <a class="btn btn-warning" href="<?php bloginfo('url');?>/register">CADASTRAR</a>
			        </li>
			    </ul>
			</div>
        </div>
    </nav>

	<?php } ?>