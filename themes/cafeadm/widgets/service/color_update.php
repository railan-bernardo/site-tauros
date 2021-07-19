<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/service/sidebar.php"); ?>


<section class="dash_content_app">

    <header class="dash_content_app_header">
        <h2 class="icon-plus">Atualizar</h2>
    </header>

    <div class="dash_content_app_box">
        <form class="app_form" action="<?= url("/admin/produto/post_color_update/{$post->id}"); ?>" autocomplet="off"
            method="post" enctype="multipart/form-data">
            <!-- ACTION SPOOFING-->
            <input type="hidden" name="action" value="update" />


            <label class="label">

                <span class="legend">Cor:</span>
                <input type="text" name="color_code" id="colorValue" value="<?= $post->color_code; ?>"
                    placeholder="Clique Aqui para Adicionar uma Cor" required />
                <input style="width: 55px; height: 30px; opacity: 0;" type="color" value="<?= $post->color_code; ?>"
                    id="color" />
            </label>

            <input type="hidden" name="id_product" value="<?= $post->id_product; ?>" required />


            <div class="al-right">
                <button class="btn btn-blue icon-check-square-o">Atualizar</button>
            </div>
        </form>
    </div>

</section>
<script>
let color = document.getElementById('color');
let colorValue = document.getElementById('colorValue');

colorValue.addEventListener('click', () => {
    color.click();
});

color.addEventListener('change', () => {
    colorValue.value = color.value;
});
</script>