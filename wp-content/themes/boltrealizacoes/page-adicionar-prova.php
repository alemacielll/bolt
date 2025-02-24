<?php get_header(); ?>

<?php if ( is_user_logged_in() ) {  ?>

<div class="container py-3">
	<div class="row">
		<div class="col-md-12 col-12 text-center mb-3">
	        <h2><?php the_title(); ?></h2>
	    </div>
    </div>
	
	<!-- formulario -->
	<div class="row d-flex align-items-center justify-content-center">
	    <div class="col-md-6 col-12">

	    	<form method="post" autocomplete="off" action="<?php bloginfo('url');?>/insert-prova">
	            <div class="row d-flex">
					<div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="nome">Título <span class="text-danger">*</span></label>
	                    <input type="text" name="post_title" placeholder="Informe" id="nome" class="form-control" required>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="aluno_estado">Estado <span class="text-danger">*</span></label>
	                    <select class="form-select" name="post_estado" id="aluno_estado" required></select>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="aluno_cidade">Cidade <span class="text-danger">*</span></label>
	                    <select class="form-select" name="post_cidade" id="aluno_cidade" required></select>
	                </div>
	                <div class="col-md-6 mb-3">
	                    <label class="fw-bold" for="data">Data <span class="text-danger">*</span></label>
	                    <input type="date" name="post_data" placeholder="Informe" id="data" class="form-control" required>
	                </div>
	                
	                <div class="col-md-12 mb-3">
	                	<label class="fw-bold mt-2 mb-3">LOTEAMENTO</label>
	                	<div class="card">
	                		<div class="card-body">
                				<div class="row">
	                				<div class="col-md-12 mb-3">
	                					<label class="fw-bold" for="">Título</label>
	                					<input type="text" name="post_titulo" class="form-control" placeholder="Ex: Lote 1">
	                				</div>
	                				<div class="col-md-6 mb-3">
	                					<label class="fw-bold" for="">Quantidade</label>
	                					<input type="text" name="post_quantidade" class="form-control" placeholder="00">
	                				</div>
	                				<div class="col-md-6 mb-3">
			                			<label class="fw-bold" for="data">Modalidade <span class="text-danger">*</span></label>
			                			<select name="post_categoria" class="form-select mb-2">
							                <option disabled selected>Selecione</option>
							                <option>3Km</option>
							                <option>5Km</option>
							                <option>7Km</option>
							            </select>
			                		</div>
	                				<div class="col-md-6 mb-3">
			                			<label class="fw-bold" for="data">Data Início <span class="text-danger">*</span></label>
			                			<input name="post_data_inicio" type="date" class="form-control mb-2" placeholder="00/00/0000">
			                		</div>
			                		<div class="col-md-6 mb-3">
			                			<label class="fw-bold" for="data">Data Fim <span class="text-danger">*</span></label>
			                			 <input name="post_data_fim" type="date" class="form-control mb-2" placeholder="00/00/0000">
			                		</div>
			                		<div class="col-md-6">
			                			<label class="fw-bold" for="data">Valor (R$) <span class="text-danger">*</span></label>
			                			 <input type="number" class="form-control" placeholder="000">
			                		</div>
                				</div>
	                		</div>
	                	</div>
	                </div>

	                <div class="row d-flex align-items-center justify-content-center">
		                <div class="col-md-4 mt-3 mb-3">
		                    <input type="hidden" name="new_post" value="1"/>
		                    <input type="submit" value="SALVAR" class="btn btn-warning fw-bold w-100">
		                    <div class="mt-2 text-center">
		                    	<a class="text-muted" href="<?php bloginfo('url'); ?>/painel-provas">Voltar</a>
		                    </div>
		                </div>
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