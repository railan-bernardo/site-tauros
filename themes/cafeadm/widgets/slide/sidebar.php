<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">dashboard/banner</h3>
    <p class="dash_content_sidebar_desc">Gerenciar todos os banner...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("", "slide/home", "Banner");
        echo $nav("fas fa-plus-o", "slide/post", "Novo Banner");
        ?>

        <?php if (!empty($slide->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= image($slide->cover, 680); ?>"/>
        <?php endif; ?>

    </nav>
</div>