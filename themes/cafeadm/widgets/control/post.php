<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/control/sidebar.php"); ?>

<div class="mce_upload" style="z-index: 997">
    <div class="mce_upload_box">
        <form class="app_form" action="<?= url("/admin/about/post"); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="upload" value="true" />
            <label>
                <label class="legend">Selecione uma imagem JPG ou PNG:</label>
                <input accept="image/*" type="file" name="image" required />
            </label>
            <button class="btn btn-blue icon-upload">Enviar Imagem</button>
        </form>
    </div>
</div>

<section class="dash_content_app">
    <?php if (!$post): ?>
    <header class="dash_content_app_header">
        <h2 class="icon-plus-circle">Criar</h2>
    </header>

    <div class="dash_content_app_box">
        <form class="app_form" action="<?= url("/admin/about/post"); ?>" method="post" enctype="multipart/form-data">
            <!-- ACTION SPOOFING-->
            <input type="hidden" name="action" value="create" />

            <label class="label">
                <span class="legend">Titulo</span>
                <input type="text" name="title" placeholder="Titulo" />
            </label>

            <label class="label">
                <span class="legend">Imagens: (1920x1080px)</span>
                <input type="file" name="cover_img[]" multiple placeholder="Uma imagem de capa" />
            </label>



            <label class="label">
                <span class="legend">*Conteúdo:</span>
                <textarea class="mce" name="content"></textarea>
            </label>

            <div class="label_g2">

                <label class="label">
                    <span class="legend">Data de publicação:</span>
                    <input class="mask-datetime" type="text" name="post_at" value="<?= date("d/m/Y H:i"); ?>"
                        required />
                </label>
            </div>

            <div class="al-right">
                <button class="btn btn-green icon-check-square-o">Publicar</button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <header class="dash_content_app_header">
        <h2 class="icon-pencil-square-o">Editar Sobre #<?= $post->id; ?></h2>
    </header>

    <div class="dash_content_app_box">
        <form class="app_form" action="<?= url("/admin/about/post/{$post->id}"); ?>" method="post"
            nctype="multipart/form-data">
            <!-- ACTION SPOOFING-->
            <input type="hidden" name="action" value="update" />
            <label class="label">
                <span class="legend">Titulo</span>
                <input type="text" name="title" value="<?= $post->title; ?>" placeholder="Titulo" />
            </label>

            <label class="label">
                <span class="legend">Imagens: (1920x1080px)</span>
                <input type="file" name="cover_img[]" multiple placeholder="Uma imagem de capa" />
            </label>



            <div style="width: 100%; display: flex; margin-bottom: 15px;">
                <?php if($gallery): ?>
                <?php foreach($gallery as $img): ?>
                <div style="width: 50px; margin: 6px 12px; display: inline-grid;">
                    <div  style="width: 100%; height: 50px; display: flex;">
                        <img style="width: 100%; object-fit: cover;" src="<?= url("/storage/{$img->cover_img}"); ?>">
                    </div>
                    <a class="icon-trash-o btn btn-red" title="" href="#"
                        data-post="<?= url("/admin/about/img/delete/{$img->id}"); ?>" data-action="delete"
                        data-confirm="Tem certeza que deseja deletar?" data-img_id="<?= $img->id; ?>"></a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>

            <label class="label">
                <span class="legend">*Conteúdo:</span>
                <textarea class="mce" name="content"><?= $post->content; ?></textarea>
            </label>


            <div class="label_g2">
                <label class="label">
                    <span class="legend">Data de publicação:</span>
                    <input class="mask-datetime" type="text" name="post_at"
                        value="<?= date_fmt($post->post_at, "d/m/Y H:i"); ?>" required />
                </label>
            </div>

            <div class="al-right">
                <button class="btn btn-blue icon-pencil-square-o">Atualizar</button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</section>