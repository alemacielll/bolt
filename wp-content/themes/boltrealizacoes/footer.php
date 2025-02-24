		</main>

		<div style="background: #000;" class="py-2 mt-4">
	        <div class="container text-center">
				<div class="row">
					<div class="col-md-12 text-center">
						<a class="w-100" href="<?php bloginfo('url');?>">
							<img src="<?php bloginfo('template_url');?>/images/logo-bolt.webp" width="100" alt="Bolt Logo">
						</a>
					</div>
					<div class="col-md-12 text-center">
						<p style="font-size: 12px; color: #666;" class="text-center">
							Todos os Direitos Reservados a Bolt Realizações - Desenvolvido por <a style="font-size: 12px; color: #666;" href="https://argosolucoes.com.br" target="_blank">Argo Soluções</a>
						</p>
					</div>
				</div>
	        </div>
	    </div>

		<?php wp_footer(); ?>

	<script>
	  $(document).ready(function(){
		$("form").on("submit", function(){
		  var submitButton = $(this).find("input[type='submit']");
		  submitButton.val("Carregando..."); // Altera o texto do botão
		  submitButton.prop("disabled", true); // Desabilita o botão
		});
	  });
	</script>

	</body>
</html>