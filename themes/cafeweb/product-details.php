<?php $v->layout("_theme"); ?>

	<!-- header title -->
	<div class="container">
		  <header class="title-header">
      <h1><?= $post->title; ?></h1>
  </header>
		<section class="box-section">
			 <article class="article" style="display: inline-block;">
            <div class="image-full-dt slide">
              <?php foreach($photos as $img): ?>

              <a class="slick-slide" href="<?= url("/storage/{$img->cover_img}") ?>"><img src="<?= url("/storage/{$img->cover_img}") ?>" alt="<?= $post->title; ?>" /> </a>
            <?php endforeach; ?>
            </div> 
       </article>
       <article class="article">
           <?= html_entity_decode($post->content); ?>
          <div class="box-group-button">
            <?php foreach ($site as $value): ?>
              <a target="_blank" href="https://web.whatsapp.com/send?phone=55<?= $value->phone_wp; ?>&text=<?= $value->msg; ?>" class="button btn-budget desktop"><i class="fab fa-whatsapp"></i>Orçamento</a>
              <a target="_blank" href="https://api.whatsapp.com/send?phone=55<?= $value->phone_wp; ?>&text=<?= $value->msg; ?>" class="button btn-budget mobile"><i class="fab fa-whatsapp"></i>Orçamento</a>
              <?php endforeach; ?>
          </div>
       </article>
		</section>
    <!-- box section -->
    </div>

