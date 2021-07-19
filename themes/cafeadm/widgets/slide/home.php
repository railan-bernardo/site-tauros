<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/slide/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class=""><i class="fas fa-edit"></i> Banner</h2>

    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_home">
                <?php if (!$slide): ?>
                    <div class="message info icon-info">Ainda não existem Banner cadastrados.</div>
                <?php else: ?>
                    <?php foreach ($slide as $banner):
                        $postCover = ($banner->slide ? image($banner->cover, 300) : "");
                        ?>
                        <article>
                            <div class="cover embed radius">
                                <img  style="width: 100%;" src="<?= url( "storage/". $banner->cover); ?>" title="">
                            </div>

                            <div class="info">
                                <p class="icon-clock-o"><?= date_fmt($banner->post_at, "d.m.y \à\s H\hi"); ?></p>
                                <p class="icon-pencil-square-o"><?= ($banner->status == "post" ? "Banner" : ($banner->status == "draft" ? "Rascunho" : "Lixo")); ?></p>
                            </div>

                            <div class="actions">

                                <a class="icon-trash-o btn btn-red" title="" href="#"
                                   data-post="<?= url("/admin/slide/post"); ?>"
                                   data-action="delete"
                                   data-confirm="Tem certeza que deseja deletar esse banner?"
                                   data-slide_id="<?= $banner->id; ?>">Deletar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>