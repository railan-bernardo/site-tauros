<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/service/sidebar.php"); ?>

<section class="dash_content_app">

        <header class="dash_content_app_header">
            <h2 class="icon-plus-circle">Nova Categoria</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/service/upload"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>


                <label class="label">
                    <span class="legend">Capa:</span>
                   <input type="file" name="cover_img[]" multiple placeholder="Uma imagem de capa"/>
                </label>

                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Criar Categoria</button>
                </div>
            </form>
        </div>
    
</section>