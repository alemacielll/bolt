	<?php
		// limpando o head
		function removeHeadLinks() {remove_action('wp_head', 'rsd_link');remove_action('wp_head', 'wlwmanifest_link');}
		add_action('init', 'removeHeadLinks');
		remove_action('wp_head', 'wp_generator');
		show_admin_bar( false );
		function meu_tema_setup() {add_theme_support('post-thumbnails');}
		add_action('after_setup_theme', 'meu_tema_setup');

		add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
		function remove_wp_logo( $wp_admin_bar ) {$wp_admin_bar->remove_node( 'wp-logo' );}
		add_filter('admin_footer_text', 'bl_admin_footer');
		function bl_admin_footer() {echo 'Desenvolvido por <a target="_blank" href="https://argosolucoes.com.br">Argo Soluções</a>';}

		function my_login_url() { return get_option('home'); }
		function my_login_title() { return get_option('blogname'); }
		function custom_admin_title( $admin_title ) {return str_replace( ' &#8212; WordPress', '', $admin_title );}
		add_filter( 'admin_title', 'custom_admin_title' );

		function wp_responsivo_scripts() {
			wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );
			wp_enqueue_style( 'style', get_stylesheet_uri() );
			wp_enqueue_script('bootstrapjs', get_template_directory_uri().'/js/bootstrap.bundle.min.js', array('jquery'), '', true );
			wp_enqueue_script('jqery_js', get_template_directory_uri().'/js/jquery-3.7.1.min.js', array('jquery'), '', false );
			wp_enqueue_script('inputmask_js', get_template_directory_uri().'/js/jquery.inputmask.min.js', array('jquery'), '', false );
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

		// Exportação de Inscrições
		add_action('init', 'exportar_inscricoes_csv');
		function exportar_inscricoes_csv() {
			if ( isset($_GET['export_csv']) && $_GET['export_csv'] == '1' ) {

				// (Opcional) Valide permissões ou use um nonce para segurança

				// Define os cabeçalhos para download do CSV
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=inscricoes.csv');

				// Abre a saída do PHP para escrever o CSV
				$output = fopen('php://output', 'w');

				// Define os cabeçalhos das colunas que serão exportadas
				$colunas = array(
					'ID Inscrição',
					'Prova',
					'Atleta',
					'Modalidade',
					'E-mail',
					'Telefone',
					'CPF',
					'Gênero',
					'Nascimento',
					'Tipo Sanguíneo',
					'CEP',
					'Rua',
					'Número',
					'Complemento',
					'Bairro',
					'Cidade',
					'Estado',
					'Assessoria',
					'Camiseta',
					'Aceite',
					'Pesquisa',
					'Forma Pagamento',
					'Lote',
					'Valor Inscrição',
					'Desconto',
					'Valor Final',
					'Situação'
				);
				fputcsv($output, $colunas, ',');

				// Recupera o filtro, se houver (por exemplo, filtro por 'prova')
				$prova_selecionada = isset($_GET['prova']) ? sanitize_text_field($_GET['prova']) : '';
				$modalidade_selecionada = isset($_GET['modalidade']) ? sanitize_text_field($_GET['modalidade']) : '';

				// Prepara os argumentos para a consulta das inscrições (sem paginação)
				$args = array(
					'posts_per_page' => -1,
					'post_type'      => 'inscricao',
				);
				$meta_query = array('relation' => 'AND');
				if ( !empty($prova_selecionada) ) {
				    $meta_query[] = array(
				        'key'     => 'id_evento',
				        'value'   => $prova_selecionada,
				        'compare' => '='
				    );
				}
				if ( !empty($modalidade_selecionada) ) {
				    $meta_query[] = array(
				        'key'     => 'modalidade',
				        'value'   => $modalidade_selecionada,
				        'compare' => '='
				    );
				}
				if ( !empty($meta_query) ) {
				    $args['meta_query'] = $meta_query;
				}


				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						// Coleta os campos necessários
						$id_inscricao    = get_the_ID();
						$id_evento       = get_field('id_evento');
						$prova           = get_the_title( $id_evento );
						$atleta          = get_the_title();

						// Campos dos custom fields
						$modalidade      = get_field('modalidade');
						$email           = get_field('e-mail');
						$telefone        = get_field('telefone');
						$cpf             = get_field('cpf');
						$genero          = get_field('genero');
						$nascimento      = get_field('nascimento');
						$tipo_sanguineo  = get_field('tipo_sanguineo');
						$cep             = get_field('cep');
						$rua             = get_field('rua');
						$numero          = get_field('numero');
						$complemento     = get_field('complemento');
						$bairro          = get_field('bairro');
						$cidade          = get_field('cidade');
						$estado          = get_field('estado');
						$assessoria      = get_field('assessoria');
						$camiseta        = get_field('camiseta');
						$aceite          = get_field('aceite');
						$pesquisa        = get_field('pesquisa');
						$forma_pagamento = get_field('forma_pagamento');
						if ( $forma_pagamento === 'CREDIT_CARD' ) {
							$forma_pagamento = 'cartão de crédito';
						}
						$lote            = get_field('lote');

						// Valores financeiros com formatação (2 casas decimais com vírgula)
						$valor_inscricao = number_format( floatval( get_field('valor') ), 2, ',', '' );
						$desconto        = number_format( floatval( get_field('valor_desconto') ), 2, ',', '' );
						$valor_final     = number_format( floatval( get_field('valor_final') ), 2, ',', '' );
						$situacao        = get_field('pago') == true ? 'Pago' : 'Pendente';

						// Monta a linha do CSV com todos os campos
						$linha = array(
							$id_inscricao,
							$prova,
							$atleta,
							$modalidade,
							$email,
							$telefone,
							$cpf,
							$genero,
							$nascimento,
							$tipo_sanguineo,
							$cep,
							$rua,
							$numero,
							$complemento,
							$bairro,
							$cidade,
							$estado,
							$assessoria,
							$camiseta,
							$aceite,
							$pesquisa,
							$forma_pagamento,
							$lote,
							$valor_inscricao,
							$desconto,
							$valor_final,
							$situacao
						);
						fputcsv($output, $linha, ',');
					}
				}
				wp_reset_postdata();
				exit;
			}
		}

		// Exportação de Pesquisas
		add_action('init', 'exportar_pesquisas_csv');
			function exportar_pesquisas_csv() {
				// Verifica se o parâmetro export_csv_pesquisa está definido na URL
				if ( isset($_GET['export_csv_pesquisa']) && $_GET['export_csv_pesquisa'] == '1' ) {

					// Define os cabeçalhos para download do CSV
					header('Content-Type: text/csv; charset=utf-8');
					header('Content-Disposition: attachment; filename=pesquisas.csv');

					// Abre a saída para escrita do CSV
					$output = fopen('php://output', 'w');

					// Define as colunas do CSV
					$colunas = array(
						'Prova',
						'Modalidade',
						'Atleta',
						'Decisão',
						'Investimento',
						'Tempo Prova',
						'Fator Destino',
						'Tempo Destino'
					);
					fputcsv($output, $colunas, ',');

					// Recupera o filtro (por exemplo, o campo "prova") se houver
					$prova_selecionada = isset($_GET['prova']) ? sanitize_text_field($_GET['prova']) : '';
					$modalidade_selecionada = isset($_GET['modalidade']) ? sanitize_text_field($_GET['modalidade']) : '';

					// Prepara os argumentos para a consulta (sem paginação)
					$args = array(
						'posts_per_page' => -1,
						'post_type'      => 'pesquisa',
					);
					$meta_query = array('relation' => 'AND');
					if ( !empty($prova_selecionada) ) {
					    $meta_query[] = array(
					        'key'     => 'id_evento',
					        'value'   => $prova_selecionada,
					        'compare' => '='
					    );
					}
					if ( !empty($modalidade_selecionada) ) {
					    $meta_query[] = array(
					        'key'     => 'modalidade',
					        'value'   => $modalidade_selecionada,
					        'compare' => '='
					    );
					}
					if ( !empty($meta_query) ) {
					    $args['meta_query'] = $meta_query;
					}


					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();

							// Recupera os campos desejados
							$prova        = get_field('prova');
							$modalidade   = get_field('modalidade');
							$atleta       = get_the_title();  // Supondo que o título do post seja o nome do atleta
							$decisao      = get_field('decisao');
							$investimento = get_field('investimento');
							$tempo_prova  = get_field('tempo_prova');
							$fator_destino= get_field('fator_destino');
							$tempo_destino= get_field('tempo_destino');

							// Monta a linha do CSV com os campos desejados
							$linha = array(
								$prova,
								$modalidade,
								$atleta,
								$decisao,
								$investimento,
								$tempo_prova,
								$fator_destino,
								$tempo_destino
							);

							// Escreve a linha no CSV, usando a vírgula como delimitador
							fputcsv($output, $linha, ',');
						}
					}
					wp_reset_postdata();
					exit; // Encerra o script para não carregar o restante da página
				}
			}

	?>