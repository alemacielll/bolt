<?php get_header(); ?>

<?php if ( is_user_logged_in() ) {  ?>

<div class="container py-3">

	<div class="row d-flex align-items-center justify-content-center">
	    <div class="col-md-6 col-12">

	    	<form method="post" autocomplete="off" action="<?php bloginfo('url');?>/insert-pesquisa">
	            <div class="row d-flex align-items-center justify-content-center">

	            	<?php if (isset($_GET['id_inscricao'])) { ?>

	                <div class="col-md-8 mb-4">
					    <label class="fw-bold mb-1">1) O que mais influenciou sua decisão de participar desta prova? <span class="text-danger">*</span></label>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_decisao" id="local" value="Local do evento" required>
					        <label class="form-check-label" for="local">Local do evento</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_decisao" id="premiacao" value="Premiação">
					        <label class="form-check-label" for="premiacao">Premiação</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_decisao" id="organizacao" value="Organização">
					        <label class="form-check-label" for="organizacao">Organização</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_decisao" id="indicacao" value="Indicação de amigos">
					        <label class="form-check-label" for="indicacao">Indicação de amigos</label>
					    </div>
					    <div class="form-check d-flex align-items-center">
					        <input class="form-check-input" type="radio" name="post_decisao" id="outros" value="Outros">
					        <label class="form-check-label me-2" for="outros">Outros (especificar):</label>
					        <input type="text" class="form-control form-control-sm w-50" name="outros_texto" placeholder="Digite aqui">
					    </div>
					</div>

					<div class="col-md-8 mb-4">
					    <label class="fw-bold mb-1">2) Qual foi o valor aproximado que você investiu para participar deste evento? <span class="text-danger">*</span></label>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_investimento" id="ate100" value="Até R$ 100" required>
					        <label class="form-check-label" for="ate100">Até R$ 100</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_investimento" id="101-300" value="R$ 101 - R$ 300">
					        <label class="form-check-label" for="101-300">R$ 101 - R$ 300</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_investimento" id="301-500" value="R$ 301 - R$ 500">
					        <label class="form-check-label" for="301-500">R$ 301 - R$ 500</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_investimento" id="acima500" value="Acima de R$ 500">
					        <label class="form-check-label" for="acima500">Acima de R$ 500</label>
					    </div>
					</div>

					<div class="col-md-8 mb-4">
					    <label class="fw-bold mb-1">3) Quanto tempo você pretende permanecer no local do evento no dia da prova? <span class="text-danger">*</span></label>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_tempo_prova" id="menos2" value="Menos de 2 horas" required>
					        <label class="form-check-label" for="menos2">Menos de 2 horas</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_tempo_prova" id="2-4horas" value="De 2 a 4 horas">
					        <label class="form-check-label" for="2-4horas">De 2 a 4 horas</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_tempo_prova" id="mais4" value="Mais de 4 horas">
					        <label class="form-check-label" for="mais4">Mais de 4 horas</label>
					    </div>
					</div>

					<div class="col-md-8 mb-4">
					    <label class="fw-bold mb-1">4) O local ou destino do evento foi um fator decisivo para a sua escolha de participação? <span class="text-danger">*</span></label>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_fator_destino" id="totalmente" value="Sim, o destino influenciou totalmente minha decisão." required>
					        <label class="form-check-label" for="totalmente">Sim, o destino influenciou totalmente minha decisão.</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_fator_destino" id="parcialmente" value="Sim, mas outros fatores também foram importantes.">
					        <label class="form-check-label" for="parcialmente">Sim, mas outros fatores também foram importantes.</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_fator_destino" id="nao" value="Não, escolhi participar por outros motivos.">
					        <label class="form-check-label" for="nao">Não, escolhi participar por outros motivos.</label>
					    </div>
					</div>

					<div class="col-md-8 mb-4">
					    <label class="fw-bold mb-1">5) Por quanto tempo você pretende permanecer no destino do evento? <span class="text-danger">*</span></label>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_tempo_destino" id="somente_prova" value="Apenas durante o dia da prova" required>
					        <label class="form-check-label" for="somente_prova">Apenas durante o dia da prova</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_tempo_destino" id="1_pernoite" value="1 pernoite">
					        <label class="form-check-label" for="1_pernoite">1 pernoite (chegada no dia anterior ou saída no dia seguinte)</label>
					    </div>
					    <div class="form-check">
					        <input class="form-check-input" type="radio" name="post_tempo_destino" id="2_ou_mais" value="2 ou mais dias">
					        <label class="form-check-label" for="2_ou_mais">2 ou mais dias</label>
					    </div>
					</div>

	                <div class="col-md-8 mb-3">
	                	<?php $id_inscricao = intval($_GET['id_inscricao']); ?>
	                	<input type="hidden" name="post_title" value="<?php echo get_the_title($id_inscricao); ?>">
	                	<input type="hidden" name="post_modalidade" value="<?php echo get_field('modalidade', $id_inscricao); ?>">
	                	<?php $id_evento =  get_field('id_evento', $id_inscricao); ?>
	                	<input type="hidden" name="post_evento" value="<?php echo get_the_title($id_evento); ?>">
	                    <input type="hidden" name="new_post" value="1"/>
		                <input type="hidden" name="post_id_inscricao" value="<?php echo $id_inscricao; ?>"/>
		                <?php } ?>
	                    <input type="submit" value="ENVIAR" class="btn btn-warning fw-bold w-100">
	                </div>
	                <div class="col-md-8 text-center mt-2">
	                	<a class="text-muted" href="<?php bloginfo('url'); ?>">Voltar</a>
	                </div>
	            </div>
	        </form>
	    </div>
	</div>

</div>

<?php } else { ?>

	<?php wp_redirect(home_url('register')); exit; ?>

<?php } ?>

<?php get_footer(); ?>