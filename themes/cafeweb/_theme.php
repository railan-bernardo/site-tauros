<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="mit" content="2021-03-29T10:32:56-03:00+163467">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?= $head; ?>

    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png"); ?>" />
    <link rel="stylesheet" href="<?= theme("/assets/style.css"); ?>" />
    <link rel="stylesheet" href="<?= theme("/assets/css/lightbox.css"); ?>" />
    <link rel="stylesheet" href="<?= theme("/assets/css/slick.css"); ?>" />
    <link rel="stylesheet" href="<?= theme("/assets/css/slick-theme.css"); ?>" />

</head>

<body>

    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>

    <!--HEADER-->
    <header class="main-header-top">
        <div class="container">
            <div class="nav-top flex-container">
                <div class="logo-top">
                    <a href="<?= url("/"); ?>"> <img src="<?= theme("/assets/images/logo.png"); ?>" alt="Tauros Distribuidora"
                        title="Tauros Distribuidora" /></a>
                </div>
                <?php 
                $siteConfig = (new Source\Models\SitePost())->find()->order("created_at DESC")->limit(1)->fetch(true); 

                $category = (new Source\Models\ServiceCategory())->find()->fetch(true);
                ?>
                <!-- end logo -->
                <div class="top-menu">

                    <!-- icon menu open -->
                    <div class="button-nav">
                        <i class="fas fa-bars"></i>
                        <span>Categorias</span>
                    </div>
                    <ul class="menu">
                        <?php foreach($category as $cat): ?>
                        <li><a href="<?= url("/categoria/{$cat->uri}"); ?>" class="a-link"><?= $cat->title; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <form name="search" class="top-form" action="<?= url("/produtos/buscar"); ?>" method="post" enctype="multipart/form-data">
                    <div class="group-form-top">
                        <input type="text" name="s" placeholder="O que você procura?">
                        <button class="fas fa-search btn-search"></button>
                    </div>
                </form>
                <div class="info-header-top">
                    <span>Atendemos somente</span>
                    <strong>Pessoas juridica!</strong>
                </div>
                <div class="social-top">
                    <?php foreach($siteConfig as $value): ?>
                    <a href="tel:<?= $value->phone_wp; ?>" class="fas fa-phone"></a>
                    <a href="https://web.whatsapp.com/send?phone=55<?= $value->phone_wp; ?>&text=<?= $value->msg; ?>" class="fab fa-whatsapp"></a>
                    <a href="<?= $value->instagram; ?>" class="fab fa-instagram"></a>
                <?php endforeach; ?>
                </div>

            </div>
            <!-- nav end -->
        </div>

    </header>

    <!--CONTENT-->
    <main class="main-container">
        <?= $v->section("content"); ?>
    </main>

    <!--FOOTER-->
    <footer class="main-footer">
        <div class="optin-footer">
            <div class="container flex-container">
                <div class="col-wd-3">
                    <div class="item-opt">
                        <span class="opt-awesome"><i class="fas fa-home"></i></span>
                        <h2>
                            ATENDIMENTO<br>
                            <p>Seg. á Sext.:8h00 ás 18h00<br>Sábado.:8h00 ás 18h00</p>
                        </h2>
                    </div>
                </div>
                <div class="col-wd-3">
                    <div class="item-opt">
                        <span class="opt-awesome"><i class="fas fa-headset"></i></span>
                        <h2>
                            FALE CONOSCO<br>
                            <p>(62) 3272-3700<br>marketing@taurosdistribuidora.com.br</p>
                        </h2>
                    </div>
                </div>
                <div class="col-wd-3">
                    <div class="item-opt">
                        <span class="opt-awesome"><i class="fas fa-map-marker-alt"></i></span>
                        <h2>
                            LOCALIZAÇÃO<br>
                            <p>R. dos Cravos, 548 - Pq. Oeste Industrial<br>Goiânia - GO, 74375-528</p>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- optin footer -->
        <div class="container">
            <div class="nav-footer">
                <div class="col-wd-5">
                    <h2>Institucional</h2>
                    <ul class="menu-footer">
                        <li><a href="<?= url("/sobre"); ?>">Sobre a Empresa</a></li>
                        <li><a href="<?= url("/fornecedor"); ?>">Fornecedores</a></li>
                    </ul>
                </div>
                <div class="col-wd-5">
                    <h2>Clientes</h2>
                    <ul class="menu-footer">
                        <li><a href="<?= url("/cadastro"); ?>">Cadastro Cliente</a></li>
                        <?php foreach($siteConfig as $value): ?>
                        <li><a href="https://web.whatsapp.com/send?phone=<?= $value->phone_wp; ?>&text=<?= $value->msg; ?>">Orçamento</a></li>
                        <li><a href="tel:<?= $value->phone_wp; ?>">Fale Conosco</a></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-wd-5">
                    <h2>Segurança</h2>
                    <div class="menu-footer">
                        <img style="width: 100%;" src="<?= theme("/assets/images/security.png"); ?>" alt="Site Seguro">
                    </div>
                </div>
                <div class="col-wd-5" style="margin-left: 15px;">
                    <h2>Siga Nos</h2>
                    <div class="social-midia-footer">
                        <?php foreach($siteConfig as $value): ?>
                        <a target="_blank" href="<?= $value->facebook; ?>" class="social-midia" title="Acompanhe no Facebook"><i
                                class="fab fa-facebook-f"></i></a>
                        <a target="_blank" href="https://web.whatsapp.com/send?phone=<?= $value->phone_wp; ?>&text=<?= $value->msg; ?>" class="social-midia" title="Entre em Contato"><i class="fab fa-whatsapp"></i></a>
                        <a target="_blank" href="<?= $value->instagram; ?>" class="social-midia" title="Siga no Instagram"><i class="fab fa-instagram"></i></a>
                    <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-wd-5">
                    <div class="info-header-bottom">
                        <span>Atendemos somente</span>
                        <strong>Pessoas juridica!</strong>
                    </div>
                </div>
            </div>
            <!-- nav footer -->

            <p class="copy">

                Razao Social [Tauros Distribuidora] CNPJ [02.814.050/0001-38] [R. dos Cravos, 548 - Pq. Oeste
                Industrial, Goiânia - GO, 74375-520]<br>
                Tauros Distribuidora © 2021 - Todos os direitos reservados
            </p>
        </div>
    </footer>
    <script src="https://kit.fontawesome.com/86f5b0a58f.js" crossorigin="anonymous"></script>
    <script src="<?= url("/shared/scripts/jquery.min.js"); ?>"></script>
    <script src="<?= theme("/assets/scripts.js"); ?>"></script>

    <script src="<?= url("/shared/scripts/jquery.form.js"); ?>"></script>
    <script src="<?= url("/shared/scripts/jquery-ui.js"); ?>"></script>
    <script src="<?= url("/shared/scripts/jquery.mask.js"); ?>"></script>
    <script src="<?= url("/shared/scripts/tinymce/tinymce.min.js"); ?>"></script>
    <script src="<?= url("/themes/cafeadm/assets/js/scripts.js", CONF_VIEW_ADMIN); ?>"></script>

    <?= $v->section("scripts"); ?>
</body>

</html>