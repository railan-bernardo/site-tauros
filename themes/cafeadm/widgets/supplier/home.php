<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/supplier/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class=""><i class="fas fa-edit"></i> Fornecedores</h2>

    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_home">
                <?php if (!$supplier): ?>
                    <div class="message info icon-info">Ainda não existem fornecedor cadastrados.</div>
                <?php else: ?>
                    <?php foreach ($supplier as $value):
                        $postCover = ($value->cover ? url("/storage/{$value->cover}") : "");
                        ?>
                        <article>
                            <div class="cover embed radius">
                                <img  style="width: 100%;" src="<?= url( "storage/". $value->cover); ?>" title="">
                            </div>

                             <div class="info">
                                <p class="icon-clock-o"><?= date_fmt($value->created_at, "d.m.y \à\s H\hi"); ?></p>
                                <p class="icon-bookmark"><?= $value->title; ?></p>

                            </div>
                            <div class="actions">

                                <a class="icon-trash-o btn btn-red" title="" href="#"
                                   data-post="<?= url("/admin/fornecedor/post"); ?>"
                                   data-action="delete"
                                   data-confirm="Tem certeza que deseja deletar?"
                                   data-post_id="<?= $value->id; ?>">Deletar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>