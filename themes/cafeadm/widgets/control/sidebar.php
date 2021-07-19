<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">dashboard/sobre</h3>
    <p class="dash_content_sidebar_desc">Gerenciar...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("", "about/home", "Sobre");
       // echo $nav("", "about/post", "Criar");
        ?>
    </nav>
</div>