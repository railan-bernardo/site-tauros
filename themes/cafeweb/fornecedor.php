<?php $v->layout("_theme"); ?>

	<!-- header title -->
	<div class="container">
			<header class="title-header">
			<h1>Fornecedores</h1>
			</header>
		<section class="main-section">
			<div class="box-brands ">
    			<?php foreach($products as $product): ?>
    			<div class="card-brands shadow">
    				<a href="#">
    					<img src="<?= url("/storage/{$product->cover}"); ?>" alt="<?= $product->title; ?>" title="<?= $product->title; ?>">
    				</a>
    			</div>
    			<?php endforeach; ?>
    		</div>
		</section>
    </div>
