<?php $v->layout("_theme"); ?>

	<!-- header -->
	<div class="container">
			<header class="title-header">
			<h1>Categoria: <?= $category; ?></h1>
	</header>
			<section class="main-form">
				<h1 class="right-filter mobile" id="filter"><i class="fas fa-filter"></i>Filtros</h1>
				<article class="right-contact" id="boxFilter">
					<h1 class="right-filter descktop"><i class="fas fa-filter"></i>Filtros</h1>
			           
			     <div class="cols-filter">
			     	<h2>Marcas</h2>
			     	<ul>
			     		 <?php foreach($products as $product): ?>
			     		 	<?php foreach($brands as $value): ?>
			     		 		<?php if(strtolower($product->brand) == strtolower($value->brand_name)): ?>
			     				<li><a href="<?= url("/marcas/{$value->uri}"); ?>"> <?= $value->brand_name; ?></a></li>
			     			<?php endif; ?>
			     		<?php endforeach; ?>
			     	 <?php endforeach; ?>
			     	</ul>
			     </div>
				</article>
				<div class="contain-category">
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
				</div>
				
			</section>
		</div>

