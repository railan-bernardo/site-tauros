<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">dashboard/fornecedor</h3>
    <p class="dash_content_sidebar_desc">Gerenciar todos os fornecedores...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("", "fornecedor/home", "Fonecedores");
        echo $nav("fas fa-plus-o", "fornecedor/post", "Adicionar");
        ?>

        <?php if (!empty($value->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= url("/storage/{$value->cover}"); ?>"/>
        <?php endif; ?>

    </nav>
</div>