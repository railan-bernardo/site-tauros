<?php $v->layout("_theme"); ?>

    <!-- header title -->
    <div class="container">
          <header class="title-header">
            <h1>Quem Somos</h1>
    </header>
        <article class="content-sobre">
           <?php foreach($about as $value): ?>
            <?= html_entity_decode($value->content); ?>
           <?php endforeach; ?>
        </article>
        <section class="main-section">
            <ul class="slide-gallery">
              <?php foreach($gallery as $value): ?>
                <li class="box-article-g">
                    <a href="<?= url("/storage/{$value->cover_img}"); ?>" class="img-container">
                            <img src="<?= url("/storage/{$value->cover_img}"); ?>" alt="<?= $value->title; ?>">
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </section>
    </div>
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
   <script src="<?= theme('/assets/js/lighbox.js'); ?>"></script>
   <script src="<?= theme('/assets/js/slick.min.js"'); ?>"></script>
<script>
    (function() {
        var $gallery = new SimpleLightbox('.box-article-g a', {});
    })();
    $('.slide-gallery').slick({
  dots: true,
  infinite: true,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 3,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
</script>
  <?= $v->section("scripts"); ?>