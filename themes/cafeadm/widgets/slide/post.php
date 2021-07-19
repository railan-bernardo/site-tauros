<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/slide/sidebar.php"); ?>



<section class="dash_content_app">
        <header class="dash_content_app_header">
            <h2 class="icon-plus-circle">Adiconar Banner</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/slide/post"); ?>" method="post">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>

                <label class="label">
                    <span class="legend">Titulo: </span>
                    <input type="text" name="title" placeholder="Titulo"/>
                </label>

                <label class="label">
                    <span class="legend">Banner: (1920x1080px)</span>
                    <input type="file" name="cover" placeholder="Uma imagem de capa"/>
                </label>


                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Status:</span>
                        <select name="status" required>
                            <option value="post">Publicar</option>
                            <option value="draft">Rascunho</option>
                            <option value="trash">Lixo</option>
                        </select>
                    </label>

                    <label class="label">
                        <span class="legend">Data de publicação:</span>
                        <input class="mask-datetime" type="text" name="post_at" value="<?= date("d/m/Y H:i"); ?>"
                               required/>
                    </label>
                </div>

                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Publicar</button>
                </div>
            </form>
        </div>

</section>
