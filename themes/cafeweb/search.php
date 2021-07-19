<?php $v->layout("_theme"); ?>

	<!-- header -->
	<div class="container">
			<header class="title-header">
			<h1>Resultado de: <?= $search; ?></h1>
	</header>
			<section class="main-form">
				<h1 class="right-filter mobile" id="filter"><i class="fas fa-filter"></i>Filtros</h1>
				
				<div class="contain-category" style="width: 100%;">
					
					<?php if(empty($products)): ?>
						<p>Ainda estamos trabalhando aqui</p>
						<?php else: ?>
					 <?php foreach($products as $product): ?>
        <article class="box-article">
            <a href="<?= url("/produto/{$product->uri}"); ?>">
                <div class="img-container">
                    <img src="<?= url("/storage/{$product->cover}"); ?>" alt="<?= $product->title; ?>" title="<?= $product->title; ?>">
                </div>
                <!-- img -->
                <h2><?= $product->title; ?></h2>
            </a>
            <span>CÓDIGO: <?= $product->code; ?></span>
            <span><?= $product->brand; ?></span>
            <div class="box-group-button">
            	<?php foreach ($site as $value): ?>
                <a href="https://we.whatsapp.com/send?phone=55<?= $value->phone_wp; ?>&text=<?= $value->msg; ?>" class="button btn-budget"><i class="fab fa-whatsapp"></i>Orçamento</a>
            <?php endforeach; ?>
            </div>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
				</div>
				
			</section>
		</div>

