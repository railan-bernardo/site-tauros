<?php $v->layout("_admin"); ?>
<?php $v->insert("widgets/service/sidebar.php"); ?>

<div class="mce_upload" style="z-index: 997">
    <div class="mce_upload_box">
        <form class="app_form" action="<?= url("/admin/produtos/post"); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="upload" value="true"/>
            <label>
                <label class="legend">Selecione uma imagem JPG ou PNG:</label>
                <input accept="image/*" type="file" name="image" required/>
            </label>
            <button class="btn btn-blue icon-upload">Enviar Imagem</button>
        </form>
    </div>
</div>

<section class="dash_content_app">
    <?php if (!$post): ?>
        <header class="dash_content_app_header">
            <h2 class="icon-plus-circle">Cadastrar Produto</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/produtos/post"); ?>" method="post" enctype="multipart/form-data">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="create"/>

                <label class="label">
                    <span class="legend">*Título:</span>
                    <input type="text" name="title" placeholder="Titulo " required/>
                </label>

                <label class="label">
                    <span class="legend">*Subtítulo:</span>
                    <textarea name="subtitle" placeholder="O texto de apoio" required></textarea>
                </label>


                 <label class="label">
                    <span class="legend">Capa: (1920x1080px)</span>
                    <input type="file" name="cover" placeholder="Uma imagem de capa"/>
                </label>


           <label class="label">
                    <span class="legend">Galeria: (1920x1080px)</span>
                    <input type="file" name="cover_img[]" required multiple placeholder="Uma imagem de capa"/>
                </label> 



                <label class="label">
                    <span class="legend">*Conteúdo:</span>
                    <textarea class="mce" name="content"></textarea>
                </label>

               
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Código:</span>
                        <input type="text" name="code" placeholder="Código">
                    </label>
                    <label class="label">
                        <span class="legend">*Marca:</span>
                        <input type="text" name="brand" placeholder="Marca">
                    </label>
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Categoria:</span>
                        <select name="category" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id; ?>"><?= $category->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                     <label class="label">
                        <span class="legend">*SubCategoria:</span>
                        <select name="subcategory" required>
                            <?php foreach ($subcategories as $category): ?>
                                <option value="<?= $category->id; ?>"><?= $category->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>

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
                    <button class="btn btn-green icon-check-square-o">Cadastrar</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <header class="dash_content_app_header">
            <h2 class="icon-pencil-square-o">Editar Produto #<?= $post->id; ?></h2>
            <a class="icon-link btn btn-green" href="<?= url("/produto/{$post->uri}"); ?>" target="_blank" title="">Ver no
                site</a>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/produtos/post/{$post->id}"); ?>" method="post">
                <!-- ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

                <label class="label">
                    <span class="legend">*Título:</span>
                    <input type="text" name="title" value="<?= $post->title; ?>" placeholder="Titulo"
                           required/>
                </label>

                <label class="label">
                    <span class="legend">*Subtítulo:</span>
                    <textarea name="subtitle" placeholder="O texto de apoio"
                              required><?= $post->subtitle; ?></textarea>
                </label>


                <label class="label">
                    <span class="legend">Capa: (1920x1080px)</span>
                    <input type="file" name="cover" placeholder="Uma imagem de capa"/>
                </label>

          <label class="label">
                    <span class="legend">Galeria: (1920x1080px)</span>
                    <input type="file" name="cover_img[]" multiple placeholder="Uma imagem de capa"  />
                </label> 

                <div style="width: 100%; display: flex; margin-bottom: 15px;">
                <?php foreach($gallery as $img): ?>
                    <?php if($post->id == $img->idservice): ?>
                <div style="width: 50px; margin: 6px 12px; display: inline-grid;">
                    <div style="width: 100%; height: 50px; display: flex;">
                        <img style="width: 100%; object-fit: cover;" src="<?= url("/storage/{$img->cover_img}"); ?>">
                    </div>
                    <a class="icon-trash-o btn btn-red" title="" href="#"
                        data-post="<?= url("/admin/produtos/post/img/{$img->id}"); ?>" 
                        data-action="excluir"
                        data-confirm="Tem certeza que deseja deletar?"
                        data-img_id="<?= $img->id; ?>"></a>
                </div>
            <?php endif; ?>
                <?php endforeach; ?>
            </div>


                <label class="label">
                    <span class="legend">*Conteúdo:</span>
                    <textarea class="mce" name="content"><?= $post->content; ?></textarea>
                </label>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Código:</span>
                        <input type="text" name="code" value="<?= $post->code; ?>" placeholder="Código">
                    </label>
                    <label class="label">
                        <span class="legend">*Marca:</span>
                        <input type="text" name="brand" value="<?= $post->brand; ?>" placeholder="Marca">
                    </label>
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Categoria:</span>
                        <select name="category" required>
                            <?php foreach ($categories as $category):
                                $categoryId = $post->category;
                                $select = function ($value) use ($categoryId) {
                                    return ($categoryId == $value ? "selected" : "");
                                };
                                ?>
                                <option <?= $select($category->id); ?>
                                        value="<?= $category->id; ?>"><?= $category->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="label">
                        <span class="legend">*SubCategoria:</span>
                        <select name="subcategory" required>
                            <?php foreach ($subcategories as $category): ?>
                                <?php

                                    $subCategoryId= $post->subcategory;
                                    $select = function($value) use($subCategoryId){
                                        return ($subCategoryId == $value ? "selected" : "");
                                    }
                                 ?>
                                <option <?= $select($category->id); ?> value="<?= $category->id; ?>"><?= $category->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>

                <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Status:</span>
                        <select name="status" required>
                            <?php
                            $status = $post->status;
                            $select = function ($value) use ($status) {
                                return ($status == $value ? "selected" : "");
                            };
                            ?>
                            <option <?= $select("post"); ?> value="post">Publicar</option>
                            <option <?= $select("draft"); ?> value="draft">Rascunho</option>
                            <option <?= $select("trash"); ?> value="trash">Lixo</option>
                        </select>
                    </label>

                    <label class="label">
                        <span class="legend">Data de publicação:</span>
                        <input class="mask-datetime" type="text" name="post_at"
                               value="<?= date_fmt($post->post_at, "d/m/Y H:i"); ?>" required/>
                    </label>
                </div>

                <div class="al-right">
                    <button class="btn btn-blue icon-pencil-square-o">Atualizar</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</section>