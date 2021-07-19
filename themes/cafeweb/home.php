<?php $v->layout("_theme"); ?>

<section class="slide-container slide">
     <?php foreach($banner as $img): ?>
    <div class="slick-slide">
        <img src="<?= url("/storage/{$img->cover}"); ?>" alt="<?= $img->title; ?>" />
    </div>
 <?php endforeach; ?>
</section>
<!-- slide end -->
<!-- header title -->
<div class="container">
    <header class="title-header">
        <h1>Itens Lançamento</h1>
    </header>
    <section class="main-section">
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
                <a href="" class="button btn-budget"><i class="fab fa-whatsapp"></i>Orçamento</a>
            </div>
        </article>
        <?php endforeach; ?>
    </section>
</div>
<section class="sec-brands">
    <div class="container">
        <h1>Selecione uma Marca</h1>
        <div class="box-brands slide-link-brand">
            <?php foreach($brands as $brand): ?>
            <div class="card-brands">
                <a href="<?= url("/marca/{$brand->uri}"); ?>">
                    <img src="<?= url("/storage/{$brand->cover}"); ?>" alt="<?= $brand->brand_name; ?>" title="<?= $brand->brand_name; ?>">
                </a>
            </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>
 <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="<?= theme("/assets/js/slick.min.js"); ?>"></script>
<script src="<?= theme("/assets/js/slide.js"); ?>"></script>
<script>
$('.slide-link-brand').slick({
    dots: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2000,
    slidesToShow: 5,
    slidesToScroll: 5,
    responsive: [{
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
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ]
});
</script>