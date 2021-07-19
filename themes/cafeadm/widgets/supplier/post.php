<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/supplier/sidebar.php"); ?>



<section class="dash_content_app">
        <header class="dash_content_app_header">
            <h2 class="icon-plus-circle">Adiconar Fornecedor</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/fornecedor/post"); ?>" method="post">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>

                <label class="label">
                    <span class="legend">Titulo: </span>
                    <input type="text" name="title" placeholder="Titulo"/>
                </label>

                <label class="label">
                    <span class="legend">Imagem: (1920x1080px)</span>
                    <input type="file" name="cover" placeholder="Uma imagem de capa"/>
                </label>


                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Publicar</button>
                </div>
            </form>
        </div>

</section>
