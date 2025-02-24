<?php
    // limpando o head
    function removeHeadLinks() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');

    show_admin_bar( false );

    function meu_tema_setup() {
        add_theme_support('post-thumbnails');
    }
    add_action('after_setup_theme', 'meu_tema_setup');

    //changing logo login
    function meu_login_logo() {
    echo '
        <style type="text/css">
            #loginform .button{background: #0fe0a4;color: #fff; border: 1px solid #0fe0a4;box-shadow: none;text-shadow: none;}
            #backtoblog{display: none;}
            .login #backtoblog a:hover, .login #nav a:hover{color: #0fe0a4;}
            .login h1 a{
                background-image: none,url('.get_template_directory_uri().'/images/logo_admin.png);
                margin: 0 auto;
                width: 150px;
                background-size: 150px;
                background-position: bottom;
            }
            a:focus{box-shadow:none;}
        </style>
    ';
    }
    add_action('login_head', 'meu_login_logo');
    add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
    function remove_wp_logo( $wp_admin_bar ) {
        $wp_admin_bar->remove_node( 'wp-logo' );
    }
    // changing the footer admin
    add_filter('admin_footer_text', 'bl_admin_footer');
    function bl_admin_footer() {
        echo 'Desenvolvido por <a target="_blank" href="https://argosolucoes.com.br">Argo Soluções</a>';
    }

    // changing the logo link from wordpress.org to your site 
    function my_login_url() { return get_option('home'); }

    // changing the alt text on the logo to show your site name
    function my_login_title() { return get_option('blogname'); }

    // remove wordpress title from dashboard
    function custom_admin_title( $admin_title ) {
        return str_replace( ' &#8212; WordPress', '', $admin_title );
    }
    add_filter( 'admin_title', 'custom_admin_title' );

    function wp_responsivo_scripts() {
        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );
        wp_enqueue_style( 'style', get_stylesheet_uri() );
        wp_enqueue_script('bootstrapjs', get_template_directory_uri().'/js/bootstrap.bundle.min.js', array('jquery'), '', true );
        wp_enqueue_script('jqery_js', get_template_directory_uri().'/js/jquery-3.7.1.min.js', array('jquery'), '', true );
        wp_enqueue_script('inputmask_js', get_template_directory_uri().'/js/jquery.inputmask.min.js', array('jquery'), '', true );
        wp_enqueue_script('cidades-estados-1.0', get_template_directory_uri().'/js/cidades-estados.js', array('jquery'), '', true );
        wp_enqueue_script('custom_js', get_template_directory_uri().'/js/custom5.js', array('jquery'), '', true );
    }
    add_action( 'wp_enqueue_scripts', 'wp_responsivo_scripts' );

    // Define o ambiente atual (homologação ou produção)
    define('ASAAS_ENVIRONMENT', 'homologacao'); // Troque para 'producao' quando necessário

    // Configurações de homologação
    define('ASAAS_HOMOLOGACAO_URL', 'https://sandbox.asaas.com/api/v3/customers');
    define('ASAAS_HOMOLOGACAO_TOKEN', '$aact_MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjBiZTNmMWRkLTFkNGUtNDQ4YS05YjZmLWQ5NmJlZDU4ZTEwNTo6JGFhY2hfODc2ZWJhMmQtZTRmZS00N2JmLTk1NDItZTExMjdiNzRmZWIx');

    // Configurações de produção
    define('ASAAS_PRODUCAO_URL', 'https://api.asaas.com/v3/customers');
    define('ASAAS_PRODUCAO_TOKEN', '$aact_MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjE1NTA2OWNlLTgyZDktNGMyOS1iYWQ5LTUzODViZjk5OWFmZDo6JGFhY2hfM2ExNWQ3MWQtMDliYi00YjM3LWI4MzYtNDczY2FlYzUwODg2');

    // Função para obter a URL e token com base no ambiente
    function get_asaas_config() {
        if (ASAAS_ENVIRONMENT === 'producao') {
            return [
                'url' => ASAAS_PRODUCAO_URL,
                'token' => ASAAS_PRODUCAO_TOKEN,
            ];
        } else {
            return [
                'url' => ASAAS_HOMOLOGACAO_URL,
                'token' => ASAAS_HOMOLOGACAO_TOKEN,
            ];
        }
    }

?>