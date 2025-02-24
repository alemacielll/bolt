<?php get_header(); ?>

<?php if ( is_user_logged_in() ) {  ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="container py-3">
		
		<?php 
			$situacao_pagamento = get_field('pago');
			$forma_pagamento = get_field('forma_pagamento');
			$id_pagamento = get_field('id_pagamento');
			$parcelas = get_field('parcelas');
		?>

		<?php if ($situacao_pagamento) { ?>

			<div class="row d-flex align-items-center justify-content-center">	
				<div class="col-md-6">
					<div class="card bg-light mb-3">
						<div class="card-body pb-4">
							<?php 
							$parcelas = get_field('parcelas');
							$parcelado = (($parcelas > 1) && (empty(get_field('installment')) || !get_field('pago')));
							?>
							<div class="col-md-12 text-center">
								<i class="bi font-60 <?php echo ($parcelado)?'bi-clock-history  text-warning':'bi-check2-circle  text-success';?> "></i>
								<h2 class="font-26 fw-bold <?php echo ($parcelado)?'text-warning':'text-success';?>"><?php echo ($parcelado)?'AGUARDANDO PAGAMENTO!':'INSCRIÇÃO REALIZADA COM SUCESSO!!';?></h2>
							</div>

							<?php $id_evento = get_field('id_evento'); ?>
							<p class="mb-0 font-16 text-center"><strong>Atleta:</strong> <?php the_title(); ?></p>
							<p class="mb-0 font-16 text-center"><strong>Prova:</strong> <?php echo get_the_title($id_evento); ?></p>
							<p class="mb-0 font-16 text-center"><strong>Lote:</strong> <?php the_field('lote'); ?></p>
							<p class="mb-0 font-16 text-center"><strong>Modalidade:</strong> <?php the_field('modalidade'); ?></p>
							<p class="mb-0 font-16 text-center"><strong>Valor:</strong> R$ <?php $valor_final = get_field('valor_final'); if ($valor_final) {echo number_format((float) $valor_final, 2, ',', '');}?></p>
							<?php 
								$valor_final = get_field('valor_final'); 
								?>
							<?php 
							
							if ($parcelas > 1) {
								$valor_parcela = $valor_final / $parcelas;
								?>
								<p class="mb-0 font-16 text-center"><strong>Parcelas:</strong> <?php echo $parcelas; ?>x de R$ <?php echo number_format((float) $valor_parcela, 2, ',', ''); ?></p>
								<?php
							}
							?>
							<p class="mb-0 font-16 text-center"><strong>Camiseta:</strong> <?php the_field('camiseta'); ?></p>
							
							<?php
								$pesquisa = get_field('pesquisa');
								if (empty($pesquisa) || strtolower($pesquisa) !== 'sim') :
							?>
							    <div class="py-3 text-center">
							        <p>Obrigado por se inscrever, para finalizar <br> realize o preenchimento da nossa pesquisa!</p>
							        <a class="btn btn-warning fw-bold" href="<?php bloginfo('url'); ?>/pesquisa/?id_inscricao=<?php the_ID(); ?>">Responder Pesquisa</a>
							    </div>
							<?php else : ?>
								<div class="text-center text-success py-4">
									Pesquisa respondida com sucesso!
								</div>
								<div class="text-center">
									<a class="" href="<?php bloginfo('url');?>/inscricoes">Voltar</a>
								</div>
							<?php endif; ?>

							<div class="text-center mt-3 py-2 bg-dark">
								<img src="<?php bloginfo('template_url');?>/images/logo-bolt.webp" width="100" alt="Bolt Logo">
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php } else { ?>

				<!-- Stepper -->
			    <div class="row d-flex align-items-center justify-content-center mb-4">
			        <div class="col-12 col-md-5 border-bottom pb-4">
			            <div class="position-relative my-4">
			                <div class="progress" style="height: 2px;">
			                    <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
			                </div>
			                <div class="d-flex justify-content-between position-absolute w-100" style="top: -10px;">
			                    <div class="d-flex flex-column align-items-center">
			                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;">
			                            <i class="fas fa-check text-white small"></i>
			                        </div>
			                        <div class="text-center fw-bold text-muted small mt-2">1. CADASTRO</div>
			                    </div>
			                    <div class="d-flex flex-column align-items-center">
			                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;">
			                            <i class="fas fa-check text-white small"></i>
			                        </div>
			                        <div class="text-center fw-bold small mt-2">2. PAGAMENTO</div>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>

				<div class="row d-flex align-items-center justify-content-center">
					<div class="col-md-5">

						<div class="modal fade" id="ModalCupom" tabindex="-1" aria-labelledby="ModalCupomLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h1 class="modal-title fs-5" id="ModalCupomLabel">Cupom Desconto</h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="input-group">
											<form method="post" action="<?php echo home_url('/pagamento-cartao/'); ?>">
											    <div class="input-group">
											        <input type="text" name="titulo_cupom" class="form-control" placeholder="Código Cupom"/>
											        <button class="btn btn-warning fw-bold" type="submit">ENVIAR</button>
											    </div>
											    
											    <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
											    <input type="hidden" name="id" value="<?php echo get_field('id_pagamento'); ?>">
												<input type="hidden" name="id_evento" value="<?php echo get_field('id_evento'); ?>">
											    <input type="hidden" name="acao" value="aplicar_cupom">
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="card bg-light mb-3">
								<div class="card-body">
									<div class="row d-flex align-items-center justify-content-center">
										
										<div class="col-md-7">
											<?php $id_evento = get_field('id_evento'); ?>
											<p class="mb-0">Prova: <?php echo get_the_title($id_evento); ?></p>
											<p class="mb-0">Modalidade: <?php the_field('modalidade'); ?></p>
											<p class="mb-0"><strong>Valor: R$ <?php $valor_final = get_field('valor_final'); if ($valor_final) {echo number_format((float) $valor_final, 2, ',', '');} ?></strong></p>
											<?php 
											  /*$parcelas = get_field('parcelas');
											  if ( !empty($parcelas) && $parcelas > 0 ) : 
											?>
											  <p class="mb-0 text-warning fw-bold">
											    (em <?php echo $parcelas; ?>x de R$ <?php echo number_format($valor_final / $parcelas, 2, ',', '.'); ?>)
											  </p>
											<?php endif; */?>
										</div>
										<div class="col-md-5 text-end">
											<?php 
												$cupom_id = get_field('cupom_desconto');

												if ($cupom_id) {
												    $cupom_titulo = get_the_title($cupom_id);
												    $cupom_tipo = get_field('tipo', $cupom_id); 
												    $cupom_valor = get_field('valor', $cupom_id);

												    // Formata o valor conforme o tipo
												    if ($cupom_tipo === 'PERCENTAGE') {
												        $valor_formatado = number_format($cupom_valor, 0) . '%';
												    } else {
												        $valor_formatado = 'R$ ' . number_format($cupom_valor, 2, ',', '.');
												    }

												    echo "<p class='fw-medium mb-0'>CUPOM: <br> <strong>$cupom_titulo</strong> <br> <span class='text-success'>$valor_formatado de desconto<span></p>";
												} else {
												    echo '<a href="#" data-bs-toggle="modal" data-bs-target="#ModalCupom" class="btn fw-bold btn-warning btn-sm">Adicionar Cupom</a>';
												}
											?>

										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
							if (isset($_GET['situacao_pagamento']) && $_GET['situacao_pagamento'] == 'erro' && isset($_GET['error_message'])):
						    $errorMessage = urldecode($_GET['error_message']);
						?>
						    <div class="alert alert-danger" role="alert">
						        <p class="mb-0">Erro: <?php echo $errorMessage; ?></p>
						    </div>
						<?php endif; ?>
						<?php
							if (isset($_GET['success_message'])):
						    $success_message = urldecode($_GET['success_message']);
						?>
						    <div class="alert alert-success" role="alert">
						        <p class="mb-0"><?php echo $success_message; ?></p>
						    </div>
						<?php endif; ?>		
						<ul class="nav nav-tabs justify-content-center mb-3" id="myTab" role="tablist">
						    <li class="nav-item" role="presentation">
						        <button class="nav-link active font-18 py-3 fw-bold" id="cartao-tab" data-bs-toggle="tab" data-bs-target="#cartao" type="button" role="tab" aria-controls="cartao" aria-selected="true">
						            CARTÃO
						        </button>
						    </li>
						<?php 
							if(empty($parcelas) || $parcelas==1){
							?>
						    <li class="nav-item" role="presentation">
						        <button class="nav-link fw-bold font-18 py-3" id="pix-tab" data-bs-toggle="tab" data-bs-target="#pix" type="button" role="tab" aria-controls="pix" aria-selected="false">
						            PIX
						        </button>
						    </li>
						<?php 
							}
							?>
						</ul>

						<div class="tab-content" id="myTabContent">
						    <div class="tab-pane fade show active" id="cartao" role="tabpanel" aria-labelledby="cartao-tab">
						        <form method="post" action="<?php echo home_url('/pagamento-cartao/'); ?>">
									<div class="mb-3">
										<label for="holderName" class="form-label fw-bold">Nome no Cartão <span class="text-danger">*</span></label>
										<input type="text" name="holderName" class="form-control" id="holderName">
									</div>
									<div class="mb-3">
										<label for="number" class="form-label fw-bold">Número do Cartão <span class="text-danger">*</span></label>
										<input type="text" name="number" id="cartao_numero" class="form-control" id="number" maxlength="19">
									</div>
									<div class="row">
										<div class="col-md-4 mb-3">
											<label for="expiryMonth" class="form-label fw-bold">Mês Validade <span class="text-danger">*</span></label>
											<input type="text" class="form-control" id="cartao_validade_mes" name="expiryMonth" id="expiryMonth" maxlength="2">
										</div>
										<div class="col-md-4 mb-3">
											<label for="expiryYear" class="form-label fw-bold">Ano Validade <span class="text-danger">*</span></label>
											<input type="text" name="expiryYear" id="cartao_validade_ano" class="form-control" id="expiryYear" maxlength="4">
										</div>

										<div class="col-md-4 mb-3">
											<label for="ccv" class="form-label fw-bold">CVV <span class="text-danger">*</span></label>
											<input type="text" name="ccv" id="cartao_cvv" class="form-control" id="ccv" maxlength="3">
										</div>
									</div>
									<?php
									$valor_final = get_field('valor_final'); 
									?>

									<div class="mb-3">
										<label for="parcelas" class="form-label fw-bold">Parcelamento <span class="text-danger">*</span></label>
										<select name="parcelas" id="parcelas" class="form-select">
											<?php 
											for ($i = 1; $i <= 6; $i++): 
												$valor_parcela = $valor_final / $i;
												if ($valor_parcela >= 5): // Verifica se a parcela é pelo menos R$ 5,00
													$descricao = ($i == 1) ? "À vista" : "{$i}x ";
											?>
												<option value="<?php echo $i; ?>">
													<?php echo $descricao . ' de R$ ' . number_format($valor_parcela, 2, ',', '.'); ?>
												</option>
											<?php 
												endif; 
											endfor; 
											?>
										</select>
									</div>

									<?php /* FACILITAR PAGAMENTO TESTE CARTÃO
									<div class="mb-3">
										<label for="holderName" class="form-label fw-bold">Nome no Cartão <span class="text-danger">*</span></label>
										<input type="text" name="holderName" class="form-control" id="holderName" value="João Silva">
									</div>
									<div class="mb-3">
										<label for="number" class="form-label fw-bold">Número do Cartão <span class="text-danger">*</span></label>
										<input type="text" name="number" id="cartao_numero" class="form-control" id="number" value="1234567890123456" maxlength="19">
									</div>
									<div class="row">
										<div class="col-md-4 mb-3">
											<label for="expiryMonth" class="form-label fw-bold">Mês Validade <span class="text-danger">*</span></label>
											<input type="text" class="form-control" id="cartao_validade_mes" name="expiryMonth" id="expiryMonth" value="1" maxlength="2">
										</div>
										<div class="col-md-4 mb-3">
											<label for="expiryYear" class="form-label fw-bold">Ano Validade <span class="text-danger">*</span></label>
											<input type="text" name="expiryYear" id="cartao_validade_ano" class="form-control" id="expiryYear" value="2025" maxlength="4">
										</div>

										<div class="col-md-4 mb-3">
											<label for="ccv" class="form-label fw-bold">CVV <span class="text-danger">*</span></label>
											<input type="text" name="ccv" id="cartao_cvv" class="form-control" id="ccv" value="123" maxlength="3">
										</div>
									</div>
									*/?>

									<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
									<input type="hidden" name="nome" value="<?php the_title(); ?>">
									<input type="hidden" name="id" value="<?php the_field('id_pagamento'); ?>">
									<input type="hidden" name="email" value="<?php the_field('e-mail'); ?>">
									<input type="hidden" name="cpfCnpj" value="<?php the_field('cpf'); ?>">
									<input type="hidden" name="postalCode" value="<?php the_field('cep'); ?>">
									<input type="hidden" name="addressNumber" value="<?php the_field('numero'); ?>">
									<input type="hidden" name="phone" value="<?php the_field('telefone'); ?>">

									<div class="col-md-12">
										<input type="hidden" name="acao" value="pagar_cartao">
										<input type="submit" class="btn btn-warning fw-bold w-100" value="PAGAR">
									</div>
								</form>

						    </div>
						<?php 
							if(empty($parcelas) || $parcelas==1){
							?>
						    <div class="tab-pane fade" id="pix" role="tabpanel" aria-labelledby="pix-tab">
							<?php 
								 $retorno = do_shortcode('[asaas_gerar_pix_qrcode paymentid="' . $id_pagamento . '"]');
								 $retorno = json_decode($retorno, true);
							 ?>
							 	<div class="row d-flex align-items-center justify-content-center">
									<div class="col-md-12 mt-4">
										<div class="row d-flex align-items-center justify-content-center">
											<script>
												function myFunction() {
													var copyText = document.getElementById("copia_cola");
													copyText.select();
													copyText.setSelectionRange(0, 99999);
													navigator.clipboard.writeText(copyText.value);
													alert("Copiado");
												}
											</script>  
											<div class="col-md-4 text-center">
												<img style='display:block;    width: 100%;' src="data:image/png;base64,<?php echo $retorno['encodedImage']; ?>" alt="QR Code Pix">
												<input style='display:none;' type="text" value="<?php echo $retorno['payload'];?>" id="copia_cola">
												<a class="btn mt-2 btn-warning fw-bold w-100" href="javascript:void(0)" onclick="myFunction();">Pix Copia e Cola</a>
												
											</div>
											<div class="col-md-8">
												<h2 class="font-16 font-weight-bold">Pague com Pix e receba a confirmação imediata do seu pagamento:</h2>
												<ol>
													<li>
														<p class="font-16">Abra o aplicativo do seu banco de preferência</p>
													</li>
													<li>
														<p class="font-16">Selecione a opção <strong>pagar com Pix</strong></p>
													</li>
													<li>
														<p class="font-16">Leia o QR code ou copie o código abaixo e cole no campo de pagamento</p>
													</li>
													<li>
														<p class="font-16">Já pagou? <a class="fw-bold " href="<?php echo get_the_permalink(); ?>">Clique aqui</a></p>
													</li>
												</ol>
											</div>
										</div>
									</div>
								</div>
						    </div>
						<?php 
							}
							?>
						</div>

					</div>
				</div>

		<?php } ?>

		</div>


<?php endwhile; endif; ?>

<?php } ?>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('#cartao_numero').inputmask('9999 9999 9999 9999');
		$('#cartao_validade_mes').inputmask('99');
		$('#cartao_validade_ano').inputmask('9999');
		$('#cartao_cvv').inputmask('999');
	});
</script>
<?php get_footer(); ?>