<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/brand/sidebar.php"); ?>


<section class="dash_content_app">
    <?php if (!$post): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-plus-circle">Nova Marca</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/brand/post"); ?>" method="post">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>

                <label class="label">
                    <span class="legend">*Nome da Marca:</span>
                    <input type="text" name="brand_name" placeholder="Nome da Marca" required/>
                </label>


                <label class="label">
                    <span class="legend">Capa: (1920x1080px)</span>
                    <input type="file" name="cover" placeholder="Uma imagem de capa"/>
                </label>



                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Publicar</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2 class="icon-pencil-square-o">Editar Marca #<?= $post->id; ?></h2>
            
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/brand/post/{$post->id}"); ?>" method="post">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

                <label class="label">
                    <span class="legend">*Nome da Marca:</span>
                    <input type="text" name="brand_name" value="<?= $post->brand_name; ?>" placeholder="Nome da Marca"
                           required/>
                </label>


                <label class="label">
                    <span class="legend">Capa: (1920x1080px)</span>
                    <input type="file" name="cover" placeholder="Uma imagem de capa"/>
                </label>



                <div class="al-right">
                    <button class="btn btn-blue icon-pencil-square-o">Atualizar</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>