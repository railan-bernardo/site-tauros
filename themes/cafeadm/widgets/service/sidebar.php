<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">dashboard/produtos</h3>
    <p class="dash_content_sidebar_desc">Aqui você gerencia todos os produtos...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("fas fa-edit", "produtos/home", "Produtos");
        echo $nav("fas fa-bookmark", "produtos/categories", "Categoria");
         echo $nav("fas fa-bookmark", "produtos/subcategories", "SubCategoria");
        echo $nav("fas fa-plus-circle", "produtos/post", "Cadastrar Produto");
       //  echo $nav("fas fa-bookmark", "produtos/upload", "Galleria");
        ?>

        <?php if (!empty($post->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= url("/storage/".$post->cover); ?>"/>
        <?php endif; ?>

        <?php if (!empty($category->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= url("/storage/".$category->cover); ?>"/>
        <?php endif; ?>
    </nav>
</div>