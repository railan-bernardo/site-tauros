<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/service/sidebar.php"); ?>


<section class="dash_content_app">

    <header class="dash_content_app_header">
        <h2 class="icon-plus-circle">Adicionar Novo</h2>
    </header>

    <div class="dash_content_app_box">
        <form class="app_form" action="<?= url("/admin/produto/post_size"); ?>" autocomplet="off" method="post"
            enctype="multipart/form-data">
            <!-- ACTION SPOOFING-->
            <input type="hidden" name="action" value="create" />

            <label class="label">
                <span class="legend">Tamanho (0x0):</span>
                <input type="text" name="size" placeholder="Tamanho " required />
                <input type="hidden" name="idpost" value="<?= $post->id; ?>" placeholder="Tamanho " required />
            </label>


            <label class="label">
                <span class="legend">Peso: (ex: 10kg)</span>
                <input type="text" name="weight" placeholder="Peso" />
            </label>


            <label class="label">
                <span class="legend">Quantidade de Pessoas: (ex: 10)</span>
                <input type="text" name="persons" required placeholder="Quantidade de Pessoas" />
            </label>



            <div class="al-right">
                <button class="btn btn-green icon-check-square-o">Publicar</button>
                <a href="<?= url("/admin/produto/color/{$post->id}"); ?>" class="btn btn-yellow">Adicionar cores</a>
                <a href="<?= url("/admin/produto/image/{$post->id}"); ?>" class="btn btn-blue">Adicionar Imagem</a>
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