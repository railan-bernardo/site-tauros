<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/service/sidebar.php"); ?>


<section class="dash_content_app">

    <header class="dash_content_app_header">
        <h2 class="icon-plus-circle">Adicionar Imagem</h2>
    </header>

    <div class="dash_content_app_box">
        <form class="app_form" action="<?= url("/admin/produto/image"); ?>" autocomplet="off" method="post"
            enctype="multipart/form-data">
            <!-- ACTION SPOOFING-->
            <input type="hidden" name="action" value="create" />

            <label class="label">
                <span class="legend">Titulo:</span>
                <input type="text" name="title" placeholder="Titulo " required />
                <input type="hidden" name="id_service" value="<?= $post->id; ?>" placeholder="Tamanho " required />
            </label>
            <label class="label">
                <span class="legend">Imagem:</span>
                <input type="file" name="cover" placeholder="Titulo " />

            </label>

            <div class="al-right">
                <button class="btn btn-green icon-check-square-o">Publicar</button>
            </div>
        </form>
    </div>

</section>