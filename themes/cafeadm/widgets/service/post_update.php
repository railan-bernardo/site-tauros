<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/service/sidebar.php"); ?>


<section class="dash_content_app">

    <header class="dash_content_app_header">
        <h2 class="icon-plus">Atualizar</h2>
    </header>

    <div class="dash_content_app_box">
        <form class="app_form" action="<?= url("/admin/produto/post_size_update/{$post->id}"); ?>" autocomplet="off"
            method="post" enctype="multipart/form-data">
            <!-- ACTION SPOOFING-->
            <input type="hidden" name="action" value="update" />

            <label class="label">
                <span class="legend">Tamanho (0x0):</span>
                <input type="text" name="size" placeholder="Tamanho " value="<?= $post->size; ?>" required />
                <input type="hidden" name="idpost" value="<?= $post->idpost; ?>" placeholder="Tamanho " required />
            </label>

            <label class="label">
                <span class="legend">Imagem: (ex: 0x0)</span>
                <input type="file" name="cover" placeholder="" />
            </label>


            <label class="label">
                <span class="legend">Peso: (ex: 10kg)</span>
                <input type="text" name="weight" value="<?= $post->weight; ?>" placeholder="Peso" />
            </label>


            <label class="label">
                <span class="legend">Quantidade de Pessoas: (ex: 10)</span>
                <input type="text" name="persons" value="<?= $post->persons; ?>" required
                    placeholder="Quantidade de Pessoas" />
            </label>

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