<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/siteinfo/sidebar.php"); ?>


<section class="dash_content_app">
    <?php if (!$post): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-plus-circle">Novo</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/footer/post"); ?>" method="post">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>

                <label class="label">
                    <span class="legend">*Titulo:</span>
                    <input type="text" name="title" placeholder="Titulo">
                </label>

                <label class="label">
                    <span class="legend">*Descrição (SEO):</span>
                    <input type="text" name="description" placeholder="Descrição para SEO" >
                </label>

                 <label class="label">
                    <span class="legend">*Telefone para Contato:</span>
                    <input type="text" name="phone" placeholder="Telefone para Contato">
                </label>
                <label class="label">
                    <span class="legend">*Mensagem do contato:</span>
                    <input type="text" name="msg" placeholder="Mensagem para whatsapp">
                </label>
                 <label class="label">
                    <span class="legend">*Whatsapp<small class="red"> (sem espaços, traços e parênteses)</small> DDD e número:</span>
                    <input type="text" name="phone_wp" placeholder="Whatsapp 00000000000"/>
                </label>
                 <label class="label">
                    <span class="legend">*E-mail:</span>
                    <input type="text" name="email" placeholder="E-mail de Contato">
                </label>

                <label class="label">
                    <span class="legend">*Facebook:</span>
                    <input type="text" name="facebook" placeholder="https://facebook.com/nome"/>
                </label>

                 <label class="label">
                    <span class="legend">*Instagram:</span>
                    <input type="text" name="instagram" placeholder="https://instagram.com/nome"/>
                </label>

                 <label class="label">
                    <span class="legend">*Copyright:</span>
                    <input type="text" name="copyright" placeholder="EX: 2021 nome todos direitos reservados"/>
                </label>

                
               <label class="label">
                    <span class="legend">*Meta tags para SEO <small class="red">(separe por vírgulas).</small>  Máximo 10 tags:</span>
                    <input type="text" name="meta_tag" placeholder="Ex: nome, lorem">
                </label>

                <div class="al-right">
                    <button class="btn btn-green icon-check-square-o">Publicar</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2 class="icon-pencil-square-o">Editar post #<?= $post->id; ?></h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/footer/post/{$post->id}"); ?>" method="post">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

               <label class="label">
                    <span class="legend">*Titulo:</span>
                    <input type="text" name="title" value="<?= $post->title; ?>" placeholder="Titulo">
                </label>

                <label class="label">
                    <span class="legend">*Descrição (SEO):</span>
                    <input type="text" name="description" value="<?= $post->description; ?>" placeholder="Descrição para SEO" >
                </label>

                 <label class="label">
                    <span class="legend">*Telefone para Contato:</span>
                    <input type="text" name="phone" value="<?= $post->phone; ?>" placeholder="Telefone para Contato">
                </label>
                <label class="label">
                    <span class="legend">*Mensagem do contato:</span>
                    <input type="text" name="msg" value="<?= $post->msg; ?>" placeholder="Mensagem para whatsapp">
                </label>
                 <label class="label">
                    <span class="legend">*Whatsapp<small class="red"> (sem espaços, traços e parênteses)</small> DDD e número:</span>
                    <input type="text" name="phone_wp" value="<?= $post->phone_wp; ?>" placeholder="Whatsapp 00000000000"/>
                </label>
                 <label class="label">
                    <span class="legend">*E-mail:</span>
                    <input type="text" name="email" value="<?= $post->email; ?>"  placeholder="E-mail de Contato">
                </label>

                <label class="label">
                    <span class="legend">*Facebook:</span>
                    <input type="text" name="facebook" value="<?= $post->facebook; ?>" placeholder="https://facebook.com/nome"/>
                </label>

                 <label class="label">
                    <span class="legend">*Instagram:</span>
                    <input type="text" name="instagram" value="<?= $post->instagram; ?>" placeholder="https://instagram.com/nome"/>
                </label>

                 <label class="label">
                    <span class="legend">*Copyright:</span>
                    <input type="text" name="copyright" value="<?= $post->copyright; ?>" placeholder="EX: 2021 nome todos direitos reservados"/>
                </label>

                
               <label class="label">
                    <span class="legend">*Meta tags para SEO <small class="red">(separe por vírgulas).</small>  Máximo 10 tags:</span>
                    <input type="text" name="meta_tag" value="<?= $post->meta_tag; ?>" placeholder="Ex: nome, lorem">
                </label>
                <div class="al-right">
                    <button class="btn btn-blue icon-pencil-square-o">Atualizar</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>