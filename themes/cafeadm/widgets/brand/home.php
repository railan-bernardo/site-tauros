<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/brand/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-pencil-square-o">Marcas</h2>
    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_home">
                <?php if (!$posts): ?>
                    <div class="message info icon-info">Ainda não existem marcas cadastradas.</div>
                <?php else: ?>
                    <?php foreach ($posts as $post):
                        $postCover = ($post->cover ? url("/storage/".$post->cover) : url("/storage/{$post->cover}"));
                        ?>
                        <article>
                            <img style="width: 100%; object-fit: cover;" src="<?= url( "/storage/{$post->cover}"); ?>"
                                 class="cover embed radius">
                            <h3 class="tittle">
                                <a target="_blank" href=" <?= url("/marca/{$post->uri}"); ?>">

                                        <span class="icon-check"><?= $post->brand_name; ?></span>
                                </a>
                            </h3>


                            <div class="actions">
                                <a class="icon-pencil btn btn-blue" title=""
                                   href="<?= url("/admin/brand/post/{$post->id}"); ?>">Editar</a>

                                <a class="icon-trash-o btn btn-red" title="" href="#"
                                   data-post="<?= url("/admin/brand/post"); ?>"
                                   data-action="delete"
                                   data-confirm="Tem certeza que deseja deletar esse post?"
                                   data-post_id="<?= $post->id; ?>">Deletar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>