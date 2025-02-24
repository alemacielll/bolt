<?php get_header(); ?>

<?php if ( is_user_logged_in() ) {  ?>

<div class="container py-3">
	<!-- Stepper -->
    <div class="row d-flex align-items-center justify-content-center mb-4">
        <div class="col-12 col-md-6 border-bottom pb-4">
            <div class="position-relative my-4">
                <div class="progress" style="height: 2px;">
                    <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between position-absolute w-100" style="top: -10px;">
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;">
                            <i class="fas fa-check text-white small"></i>
                        </div>
                        <div class="text-center fw-bold small mt-2">1. CADASTRO</div>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;">
                        </div>
                        <div class="text-center fw-bold text-muted small mt-2">2. PAGAMENTO</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<!-- formulario -->
	<div class="row d-flex align-items-center justify-content-center">
	    <div class="col-md-6 col-12">
	    	<?php
			if (isset($_GET['id_evento'])) {
			    $id_evento = intval($_GET['id_evento']);
			    
			    // Recupera os lotes dinamicamente do WordPress
			    $lotes = get_field('lotes', $id_evento);
			    $lotes_ativos = [];
			    $modalidades_ativas = [];
			    if ($lotes && is_array($lotes)) {
			        $data_atual = date('Y-m-d'); 
			        
			        foreach ($lotes as $key=>$lote) {
			            $esgotado = intval($lote['utilizados']) >= intval($lote['quantidade']);			            
			            foreach ($lote['itens'] as $item) {
			                $data_inicio = DateTime::createFromFormat('d/m/Y', $item['inicio'])->format('Y-m-d');
			                $data_fim = DateTime::createFromFormat('d/m/Y', $item['fim'])->format('Y-m-d');
			                $categoria_nome = get_term($item['categoria'])->name;			                
			                if ($data_atual >= $data_inicio && $data_atual <= $data_fim && !$esgotado) {
			                    $lotes_ativos[] = [
									'key' => $key,
			                        'lote' => $lote['titulo'],
			                        'categoria' => $categoria_nome,
			                        'inicio' => $item['inicio'],
			                        'fim' => $item['fim'],
			                        'valor' => $item['valor'],
			                    ];			                    
			                    $modalidades_ativas[] = [
			                        'categoria' => $categoria_nome,
			                        'valor' => $item['valor']
			                    ];
			                }
			            }
			        }
			    }
			}
			?>
	    	<?php if (!empty($lotes_ativos)): ?>
	    	<div class="card bg-light mb-3">
				<div class="card-body text-center">
					<div class="row d-flex align-items-center justify-content-center">
						<div class="col-md-7">
							<h3 class="mb-0"><?php echo esc_html($lotes_ativos[0]['lote']); ?></h3>
						</div>
					</div>
				</div>
			</div>

	    	<form method="post" autocomplete="off" action="<?php bloginfo('url');?>/insert-inscricao">
				<input type="hidden" name="post_key_lote" value="<?php echo esc_html($lotes_ativos[0]['key']); ?>">
	            <div class="row d-flex">
	            	<?php if (isset($_GET['id_evento'])) { ?>
	                <?php $id_evento = intval($_GET['id_evento']); ?>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="evento">Prova <span class="text-danger">*</span></label>
	                    <select name="post_evento" id="evento" class="form-select" required>
	                        <option value="<?php echo $id_evento; ?>" selected=""><?php echo get_the_title($id_evento); ?></option>
	                    </select>
	                </div>
	                <?php } ?>
	                <div class="col-md-6 mb-3">
					    <label class="fw-bold" for="modalidade">Modalidade <span class="text-danger">*</span></label>
					    <select name="post_modalidade" id="modalidade" class="form-select" required>
					        <option value="">Selecione</option>
					        <?php if (!empty($modalidades_ativas)): ?>
					            <?php foreach ($modalidades_ativas as $modalidade): ?>
					                <option value="<?php echo esc_attr($modalidade['categoria']);?>"
										data-valor="<?php echo esc_attr($modalidade['valor']); ?>">
					                    <?php echo esc_html($modalidade['categoria']) . ' - ' . (($modalidade['valor']>0)?'R$ '.number_format($modalidade['valor'], 2, ',', '.'):'GRATUITO'); ?>
					                </option>
					            <?php endforeach; ?>
					        <?php endif; ?>
					    </select>
					</div>
						                
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="nome">Nome Completo <span class="text-danger">*</span></label>
	                    <input type="text" name="post_title" placeholder="Informe" id="nome" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="email">E-mail <span class="text-danger">*</span></label>
	                    <input type="email" name="post_email" placeholder="Informe" id="email" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="telefone">Telefone <span class="text-danger">*</span></label>
	                    <input type="telefone" name="post_telefone" placeholder="Informe" id="telefone" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="cpf">CPF <span class="text-danger">*</span></label>
	                    <input type="text" name="post_cpf" id="cpf" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gênero</label>
                        <select name="post_genero" class="form-select" required>
                        	<option value="">Selecione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Feminino">Feminino</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
					    <label class="form-label fw-bold">Tipo Sanguíneo</label>
					    <select name="post_tipo_sanguineo" class="form-select" required>
					        <option value="">Selecione</option>
					        <option value="A+">A+</option>
					        <option value="A-">A-</option>
					        <option value="B+">B+</option>
					        <option value="B-">B-</option>
					        <option value="AB+">AB+</option>
					        <option value="AB-">AB-</option>
					        <option value="O+">O+</option>
					        <option value="O-">O-</option>
					        <option value="Não sei">Não sei</option>
					    </select>
					</div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Data Nascimento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="post_nascimento" placeholder="Informe" required>
                    </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="aluno_cep">CEP <span class="text-danger">*</span></label>
	                    <input type="text" name="post_cep" id="aluno_cep" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="aluno_endereco">Rua <span class="text-danger">*</span></label>
	                    <input type="text" name="post_rua" id="aluno_endereco" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="numero">Número <span class="text-danger">*</span></label>
	                    <input type="number" name="post_numero" id="numero" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="complemento">Complemento</label>
	                    <input type="text" name="post_complemento" id="complemento" class="form-control">
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="bairro">Bairro <span class="text-danger">*</span></label>
	                    <input type="text" name="post_bairro" id="aluno_bairro" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="estado">Estado <span class="text-danger">*</span></label>
	                    <select class="form-select" name="post_estado" id="aluno_estado" required></select>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="cidade">Cidade <span class="text-danger">*</span></label>
	                    <select class="form-select" name="post_cidade" id="aluno_cidade" required></select>
	                </div>
	                <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Equipe / Assessoria</label>
                            <select name="post_assessoria" id="" class="form-select">
                                <option value="">Selecione</option>
                                <option value="4 Pá 1">4 Pá 1</option>
								<option value="ACORP.CG">ACORP.CG</option>
                                <option value="ADAC">ADAC</option>
                                <option value="Agilityms">Agilityms</option>
                                <option value="Anib Runners">Anib Runners</option>
                                <option value="AS Runners">AS Runners</option>
                                <option value="Caio Pompeu Assessoria Esportiva">Caio Pompeu Assessoria Esportiva</option>
                                <option value="Capivara Flash">Capivara Flash</option>
                                <option value="Cfgp Running">Cfgp Running</option>
                                <option value="Chapa Run">Chapa Run</option>
                                <option value="FHS Assessoria">FHS Assessoria</option>
                                <option value="Gigi Bittencourt">Gigi Bittencourt</option>
                                <option value="Grupo Jk Academia">Grupo Jk Academia</option>
								<option value="Grupo Trilheiros">Grupo Trilheiros</option>
                                <option value="Kalenjin Running">Kalenjin Running</option>
                                <option value="Maira Brum Assessoria">Maira Brum Assessoria</option>
                                <option value="Mtr">Mtr</option>
                                <option value="Ng Training">Ng Training</option>
                                <option value="Pace Runners">Pace Runners</option>
                                <option value="Percurso Livre">Percurso Livre</option>
                                <option value="Premierun Assessoria">Premierun Assessoria</option>
                                <option value="Rb Assessoria">Rb Assessoria</option>
                                <option value="Rafaela Schwaab">Rafaela Schwaab</option>
                                <option value="Rs Assessoria Esportiva">Rs Assessoria Esportiva</option>
                                <option value="Sidney Menezes">Sidney Menezes</option>
                                <option value="Sidi Ajala">Sidi Ajala</option>
                                <option value="Teamdouil">Teamdouil</option>
                                <option value="Tj Team Jardel">Tj Team Jardel</option>
                                <option value="Top Run Assessoria">Top Run Assessoria</option>
                                <option value="Tr8 Run Club">Tr8 Run Club</option>
                                <option value="Turma Do Longo">Turma Do Longo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tamanho Camiseta <span class="text-danger">*</span></label>
                            <select name="post_camiseta" class="form-select" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="Baby look M">Baby look M</option>
                                <option value="Baby look G">Baby look G</option>
                                <option value="Camiseta P">Camiseta P</option>
                                <option value="Camiseta M">Camiseta M</option>
                                <option value="Camiseta G">Camiseta G</option>
                                <option value="Camiseta GG">Camiseta GG</option>
                                <option value="Camiseta XGG">Camiseta XGG</option>
                                <option value="Camiseta EXGG">Camiseta EXGG</option>
                                <option value="Infantil 4 anos">Infantil 4 anos</option>
                                <option value="Infantil 6 anos">Infantil 6 anos</option>
                                <option value="Infantil 8 anos">Infantil 8 anos</option>
                                <option value="Infantil 10 anos">Infantil 10 anos</option>
                            </select>
                        </div>
	                <div class="col-md-12 mb-4">
						<input type="checkbox" name="post_aceite" value="Aceito os termos em: <?php echo current_time('d/m/Y H:i:s'); ?>" checked required>
						<?php if($id_evento) {

							$link_regulamento = get_field('link_regulamento', $id_evento); 
							$link_termo = get_field('link_termo', $id_evento);

							if( $link_regulamento && $link_termo ): ?>
								<label>
									Li e aceito os termos do 
									<a target="_blank" class="text-primary" href="<?php echo esc_url($link_regulamento); ?>">Regulamento</a> 
									e os 
									<a target="_blank" class="text-primary" href="<?php echo esc_url($link_termo); ?>">Termo de Responsabilidade</a>.
								</label>
							<?php endif; 
						} ?>
					</div>
	                <div class="col-md-12 mb-3">
	                    <input type="hidden" name="new_post" value="1"/>
	                    <input type="hidden" id="" name="post_lote" value="<?php echo esc_html($lotes_ativos[0]['lote']); ?>"/>
	                    <input type="hidden" id="post_valor" name="post_valor" value="0.00"/>
	                    <input type="hidden"  id="post_valor_final" name="post_valor_final" value="0.00"/>

	                    <input type="submit" value="PRÓXIMA ETAPA" class="btn btn-warning fw-bold w-100">
	                </div>
	                <div class="col-md-12 text-center mt-2">
	                	<a class="text-muted" href="<?php bloginfo('url'); ?>">Voltar</a>
	                </div>
	            </div>
	        </form>

	        <?php else: ?>

	        	<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<div class="row d-flex align-items-center justify-content-center">
							<div class="col-md-7">
								<h3 class="mb-0">Aguarde o próximo lote</h3>
							</div>
						</div>
					</div>
				</div>
		    <?php endif; ?>

	    </div>
	</div>

</div>

<?php } else { ?>

	<?php wp_redirect(home_url('register')); exit; ?>

<?php } ?>

<?php get_footer(); ?>