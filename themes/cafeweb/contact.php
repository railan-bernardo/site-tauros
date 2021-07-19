<?php $v->layout("_theme"); ?>


	<!-- header -->
	<div class="container">
			<header class="title-header">
			<h1>Cadastro ao Cliente</h1>
	</header>
			<section class="main-form">
				<article class="form-default-oc">
					<form class="form-orcament" action="<?= url("/cadastro"); ?>" method="post">
						<div class="label-lg2-oc">
							<input name="first_name" class="input-margin-r" placeholder="Nome">
							 <input type="text" name="email" placeholder="E-mail">
						</div>
						<div class="label-lg2-oc">
							 <input class="input-margin-r" type="text" name="phone" placeholder="Telefone">
							 <input type="text" name="subject" placeholder="Assunto">
						</div>
						
						<div class="label-lg2-oc">
							 <textarea name="msg" rows="8" cols="12" placeholder="Mensagem"></textarea>
						</div>
						<button type="submit" class="send-orcament">Enviar</button>
					</form>
				</article>
				
			</section>
		</div>
</main>
