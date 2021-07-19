<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/service/sidebar.php"); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-pencil-square-o">SubCategorias</h2>
        <a class="icon-plus-circle btn btn-green" href="<?= url("/admin/produtos/subcategory"); ?>">Criar SubCategoria</a>
    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_categories">
                <?php if (!$categories): ?>
                    <div class="message info icon-info">Ainda não existem subcategorias cadastradas</div>
                <?php else: ?>
                    <?php foreach ($categories as $category):
                        $categoryCover = ($category->cover ? url("/storage/".$category->cover) : "");
                        ?>
                        <article class="radius">
                            <div class="thumb">
                                <img style="width: 100; object-fit: cover; height: 150px;" src="<?= url("/storage/".$category->cover); ?>"
                                     class="cover embed radius">
                            </div>
                            <div class="info">
                                <h3 class="title">
                                    <?= $category->title; ?>
                                    [ <b><?= $category->posts()->count(); ?> service aqui</b> ]
                                </h3>
                                <p class="desc"><?= $category->description; ?></p>

                                <div class="actions">
                                    <a class="icon-pencil btn btn-blue" title=""
                                       href="<?= url("/admin/produtos/subcategory/{$category->id}"); ?>">Editar</a>

                                    <a class="icon-trash-o btn btn-red" href="#" title=""
                                       data-post="<?= url("/admin/produtos/subcategory"); ?>"
                                       data-action="delete"
                                       data-confirm="Tem certeza que deseja deletar a subcategoria?"
                                       data-subcategory_id="<?= $category->id; ?>">Deletar</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

           
        </section>
    </div>
</section>