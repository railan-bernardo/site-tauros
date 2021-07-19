<?php

namespace Source\App\Admin;

use Source\Core\Save;
use Source\Models\ImageProduct;
use Source\Models\Gallerys;
use Source\Models\PostSize;
use Source\Models\Service;
use Source\Models\Subcategory;
use Source\Models\ServiceCategory;
use Source\Models\User;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;
use Source\Support\Media;
/**
 * Class Blog
 * @package Source\App\Admin
 */
class Services extends Admin
{
    /**
     * Blog constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array|null $data
     */
    public function home(?array $data): void
    {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/produtos/home/{$s}/1")]);
            return;
        }

        $search = null;
        $posts = (new Service())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $posts = (new Service())->find("MATCH(title, subtitle) AGAINST(:s)", "s={$search}");
            if (!$posts->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/produtos/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/produtos/home/{$all}/"));
        $pager->pager($posts->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Produtos",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/home", [
            "app" => "service/home",
            "head" => $head,
            "posts" => $posts->limit($pager->limit())->offset($pager->offset())->order("post_at DESC")->fetch(true),
            "paginator" => $pager->render(),
            "search" => $search
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function post(?array $data): void
    {
        //MCE Upload
        if (!empty($data["upload"]) && !empty($_FILES["image"])) {
            $files = $_FILES["image"];
            $upload = new Upload();
            $image = $upload->image($files, "post-" . time());

            if (!$image) {
                $json["message"] = $upload->message()->render();
                echo json_encode($json);
                return;
            }

            $json["mce_image"] = '<img style="width: 100%;" src="' . url("/storage/{$image}") . '" alt="{title}" title="{title}">';
            echo json_encode($json);
            return;
        }

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCover = new Gallerys();
            $postCreate = new Service();
             $postCreate->code = $data["code"];
              $postCreate->brand = $data["brand"];
            $postCreate->category = $data["category"];
            $postCreate->subcategory = $data["subcategory"];
            $postCreate->title = $data["title"];

            $postCreate->uri = str_slug($postCreate->title);
            $postCreate->subtitle = $data["subtitle"];

            $postCreate->content = str_replace(["{title}"], [$postCreate->title], $content);
            $postCreate->status = $data["status"];
            $postCreate->post_at = date_fmt_back($data["post_at"]);

             

            //upload cover
            if (!empty($_FILES["cover"])) {
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $postCreate->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $postCreate->cover = $image;
            }

            if (!$postCreate->save()) {
                $json["message"] = $postCreate->message()->render();
                echo json_encode($json);
                return;
            }


            if(!empty($_FILES['cover_img'])){
                $images = $_FILES['cover_img'];
                $upload = new Upload();
                for ($i = 0; $i < count($images['type']); $i++) {
                    foreach (array_keys($images) as $keys) {
                        $imageFiles[$i][$keys] = $images[$keys][$i];
                    }

                }

            $a = 1;
                foreach ($imageFiles as $file) {
                    $img = $upload->imageAll($file, time().$postCreate->title."-".$a);

                if (!$img) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
     
                     $postCover->cover_img = $img;
                    $postCover->idservice = $postCreate->id;
                    $save = new Save();

                   $create = $save->create($postCover->cover_img, $postCover->idservice);
            
                    $a++;
                     
                }

                if (!$create) {
                $json["message"] = $postCover->message()->render();
                echo json_encode($json);
                return;
            }
            }
            
           


            $this->message->success("Post publicado com sucesso...")->flash();
            $json["redirect"] = url("/admin/produtos/post/{$postCreate->id}");

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postEdit = (new Service())->findById($data["post_id"]);

            if (!$postEdit) {
                $this->message->error("Você tentou atualizar um post que não existe ou foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/produtos/home")]);
                return;
            }
            $postCover = new Gallerys();
            $postEdit->code = $data["code"];
              $postEdit->brand = $data["brand"];
            $postEdit->category = $data["category"];
            $postEdit->subcategory = $data["subcategory"];
            $postEdit->title = $data["title"];
            $postEdit->uri = str_slug($postEdit->title);
            $postEdit->subtitle = $data["subtitle"];

            $postEdit->content = str_replace(["{title}"], [$postEdit->title], $content);
            $postEdit->status = $data["status"];
            $postEdit->post_at = date_fmt_back($data["post_at"]);



            //upload cover
            if (!empty($_FILES["cover"])) {
                if ($postEdit->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postEdit->cover}")) {
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postEdit->cover}");
                    (new Thumb())->flush($postEdit->cover);
                }

                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $postEdit->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $postEdit->cover = $image;
            }

            if (!$postEdit->save()) {
                $json["message"] = $postEdit->message()->render();
                echo json_encode($json);
                return;
            }


            if(!empty($_FILES['cover_img'])){
                $images = $_FILES['cover_img'];
                $upload = new Upload();
                for ($i = 0; $i < count($images['type']); $i++) {
                    foreach (array_keys($images) as $keys) {
                        $imageFiles[$i][$keys] = $images[$keys][$i];
                    }

                }

            $a = 1;
                foreach ($imageFiles as $file) {
                    $img = $upload->imageAll($file, time().$postEdit->title."-".$a);

                if (!$img) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
     
                     $postCover->cover_img = $img;
                    $postCover->idservice = $postEdit->id;
                    $save = new Save();

                   $create = $save->create($postCover->cover_img, $postCover->idservice);
            
                    $a++;
                     
                }
                if (!$create) {
                $json["message"] = $postCover->message()->render();
                echo json_encode($json);
                return;
            }
            }


            $this->message->success("Post atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new Service())->findById($data["post_id"]);

            if (!$postDelete) {
                $this->message->error("Você tentou excluir um post que não existe ou já foi removido")->flash();
                echo json_encode(["reload" => true]);
                return;
            }



            if ($postDelete->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}")) {
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}");
                  //unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->video}");
                (new Thumb())->flush($postDelete->cover);
            }

            $tm = (new Gallerys())->find()->fetch(true);

            foreach ($tm as $value) {
               
               if($postDelete->id == $value->idservice){
   
                        unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$value->cover_img}");
                        (new Thumb())->flush($value->cover_img);

                    $value->destroy();

               }

            }

                        $postDelete->destroy();
            $this->message->success("O post foi excluído com sucesso...")->flash();

            echo json_encode(["reload" => true]);
            return;
        }

            //delete img
        if (!empty($data["action"]) && $data["action"] == "excluir") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new Gallerys())->findById($data["img_id"]);

            if (!$postDelete) {
                $this->message->error("Você tentou excluir uma image que não existe ou já foi removido")->flash();
                echo json_encode(["reload" => true]);
                return;
            }



            if ($postDelete->cover_img && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover_img}")) {
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover_img}");
                  //unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->video}");
                (new Thumb())->flush($postDelete->cover_img);
            }

    
            $postDelete->destroy();
            $this->message->success("A Imagem foi excluído com sucesso...")->flash();

            echo json_encode(["reload" => true]);
            return;
        }

        $postEdit = null;
        if (!empty($data["post_id"])) {
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Service())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Novo Produto"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/post", [
            "app" => "service/post_size",
            "head" => $head,
            "post" => $postEdit,
            "gallery"=>(new Gallerys())->find()->fetch(true),
            "categories" => (new ServiceCategory())->find("type = :type", "type=post")->order("title")->fetch(true),
              "subcategories" => (new Subcategory())->find("type = :type", "type=post")->order("title")->fetch(true)
        ]);
    }

    /**
        SubCategory
    **/

    /**
     * @param array|null $data
     */
    public function subcategories(?array $data): void
    {
        $categories = (new Subcategory())->find();
        $pager = new Pager(url("/admin/produtos/subcategories/"));
        $pager->pager($categories->count(), 6, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | SubCategorias",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/subcategories", [
            "app" => "service/subcategories",
            "head" => $head,
            "categories" => $categories->order("title")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }



/**
 *post size 
 **/

 /**
     * @param array|null $data
     * @throws \Exception
     */
    public function postSize(?array $data): void
    {
       

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCreate = new PostSize();
            $postCreate->idpost = $data["idpost"];
            $postCreate->size = $data["size"];
            $postCreate->weight = $data["weight"];
            $postCreate->persons = $data["persons"];


            //upload cover
            if (!empty($_FILES["cover"])) {
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $postCreate->size);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $postCreate->cover = $image;
            }


            
            if (!$postCreate->save()) {
                $json["message"] = $postCreate->message()->render();
                echo json_encode($json);
                return;
            }

            

            $this->message->success("Adicionado com sucesso...")->flash();
            

            echo json_encode(["reload" => true]);
            return;
        }

       

        $postEdit = null;
        if (!empty($data["post_id"])) {
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Service())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Novo Produto"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/post_size", [
            "app" => "service/post_size",
            "head" => $head,
            "post" => $postEdit,
            "id"=>(new PostSize())->find("idpost = :idpost", "idpost={$postEdit->id}")->fetch(true),
            "categories" => (new ServiceCategory())->find("type = :type", "type=post")->order("title")->fetch(true),
              "subcategories" => (new Subcategory())->find("type = :type", "type=post")->order("title")->fetch(true)
        ]);
    }


    /**
 *post size 
 **/

 /**
     * @param array|null $data
     * @throws \Exception
     */
    public function postColor(?array $data): void
    {
       

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);


            $postCreate = new Colors();
            $postCreate->color_code = $data["color"];
            $postCreate->id_product = $data["id_product"];



            if (!$postCreate->save()) {
                $json["message"] = $postCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Adicionado com sucesso...")->flash();
            

            echo json_encode(["reload" => true]);
            return;
        }


        $postEdit = null;
        if (!empty($data["post_id"])) {
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Service())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Novo Produto"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/post_color", [
            "app" => "service/post_size",
            "head" => $head,
            "post" => $postEdit,
            "id"=>(new PostSize())->find("idpost = :idpost", "idpost={$postEdit->id}")->fetch(true),
            "categories" => (new ServiceCategory())->find("type = :type", "type=post")->order("title")->fetch(true),
              "subcategories" => (new Subcategory())->find("type = :type", "type=post")->order("title")->fetch(true)
        ]);
    }



    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function postColorUp(?array $data): void
    {
       
        //update
        if (!empty($data["action"]) && $data["action"] == "update") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postEdit = (new Colors())->findById($data["post_id"]);

            if (!$postEdit) {
                $this->message->error("Você tentou atualizar um post que não existe ou foi removido")->flash();
               echo json_encode(["redirect" => url("/admin/produto/post_size_up/{$postEdit->id_product}")]);
                return;
            }


            $postEdit->color_code = $data["color_code"];
            $postEdit->id_product = $data["id_product"];



            if (!$postEdit->save()) {
                $json["message"] = $postEdit->message()->render();
                echo json_encode($json);
                return;
            }


            $this->message->success("Atualizado com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/produto/post_size_up/{$postEdit->id_product}")]);
            return;
        }

         //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new Colors())->findById($data["post_id"]);


            if (!$postDelete) {
                $this->message->error("Você tentou excluir um post que não existe ou já foi removido")->flash();
                echo json_encode(["reload" => true]);
                return;
            }
             


            $postDelete->destroy();
            $this->message->success("O post foi excluído com sucesso...")->flash();

            echo json_encode(["reload" => true]);
            return;
        }



        $postEdit = null;
        if (!empty($data["post_id"])) {
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Colors())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Atualizar"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/color_update", [
            "app" => "service/color_update",
            "head" => $head,
            "post" => $postEdit
        ]);
    }


 /**
     * @param array|null $data
     * @throws \Exception
     */
    public function postSizeUp(?array $data): void
    {
       
        //update
        if (!empty($data["action"]) && $data["action"] == "update") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postEdit = (new PostSize())->findById($data["post_id"]);

            if (!$postEdit) {
                $this->message->error("Você tentou atualizar um post que não existe ou foi removido")->flash();
               echo json_encode(["redirect" => url("/admin/produto/post_size_up/{$postEdit->idpost}")]);
                return;
            }


            $postEdit->idpost = $data["idpost"];
            $postEdit->size = $data["size"];
            $postEdit->weight = $data["weight"];
            $postEdit->persons = $data["persons"];;


            

            if (!$postEdit->save()) {
                $json["message"] = $postEdit->message()->render();
                echo json_encode($json);
                return;
            }


            $this->message->success("Atualizado com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/produto/post_size_up/{$postEdit->idpost}")]);
            return;
        }

         //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new PostSize())->findById($data["post_id"]);


            if (!$postDelete) {
                $this->message->error("Você tentou excluir um post que não existe ou já foi removido")->flash();
                echo json_encode(["reload" => true]);
                return;
            }
             if ($postDelete->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}")) {
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}");
                (new Thumb())->flush($postDelete->cover);
            }


            $postDelete->destroy();
            $this->message->success("O post foi excluído com sucesso...")->flash();

            echo json_encode(["reload" => true]);
            return;
        }



        $postEdit = null;
        if (!empty($data["post_id"])) {
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new PostSize())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Atualizar"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/post_update", [
            "app" => "service/post_size",
            "head" => $head,
            "post" => $postEdit
        ]);
    }


    /**
     * 
     * @param array|null $data
     * @throws \Exception
     */
    public function postImageSize(?array $data): void
    {
       

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCreate = new ImageProduct();
            $postCreate->title = $data["title"];
            $postCreate->id_service = $data["id_service"];


            //upload cover
            if (!empty($_FILES["cover"])) {
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $postCreate->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $postCreate->cover = $image;
            }


            
            if (!$postCreate->save()) {
                $json["message"] = $postCreate->message()->render();
                echo json_encode($json);
                return;
            }

            

            $this->message->success("Adicionado com sucesso...")->flash();
            

            echo json_encode(["reload" => true]);
            return;
        }

        
 //delete
 if (!empty($data["action"]) && $data["action"] == "delete") {
    $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
    $postDelete = (new ImageProduct())->findById($data["post_id"]);

    if (!$postDelete) {
        $this->message->error("Você tentou excluir um post que não existe ou já foi removido")->flash();
        echo json_encode(["reload" => true]);
        return;
    }



    if ($postDelete->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}")) {
        unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover}");
          //unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->video}");
        (new Thumb())->flush($postDelete->cover);
    }

   
    $postDelete->destroy();
    $this->message->success("O post foi excluído com sucesso...")->flash();
    echo json_encode(["reload" => true]);
    return;
}
       

        $postEdit = null;
        if (!empty($data["post_id"])) {
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Service())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Novo Produto"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/post_image", [
            "app" => "service/post_image",
            "head" => $head,
            "post" => $postEdit,
            "id"=>(new PostSize())->find("idpost = :idpost", "idpost={$postEdit->id}")->fetch(true),
            "categories" => (new ServiceCategory())->find("type = :type", "type=post")->order("title")->fetch(true),
              "subcategories" => (new Subcategory())->find("type = :type", "type=post")->order("title")->fetch(true)
        ]);
    }

     /**
     * @param array|null $data
     * @throws \Exception
     */
    public function postSizeHome(?array $data): void
    {
       

        $postEdit = null;
        if (!empty($data["post_id"])) {
            $postId = filter_var($data["post_id"], FILTER_VALIDATE_INT);
            $postEdit = (new Service())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Novo Produto"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/post_size_up", [
            "app" => "service/post_size",
            "head" => $head,
            "post" => $postEdit,
            "images"=>(new ImageProduct())->find("id_service = :id_service", "id_service={$postEdit->id}")->fetch(true),
            "id"=>(new PostSize())->find("idpost = :idpost", "idpost={$postEdit->id}")->fetch(true),
            "colors"=>(new Colors())->find("id_product = :id_product", "id_product={$postEdit->id}")->fetch(true),
            "categories" => (new ServiceCategory())->find("type = :type", "type=post")->order("title")->fetch(true),
              "subcategories" => (new Subcategory())->find("type = :type", "type=post")->order("title")->fetch(true)
        ]);
    }





    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function subcategory(?array $data): void
    {
        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $categoryCreate = new Subcategory();
            $categoryCreate->title = $data["title"];
            $categoryCreate->idcategory = $data['idcategory'];
            $categoryCreate->uri = str_slug($categoryCreate->title);
            $categoryCreate->description = $data["description"];

            //upload cover
            if (!empty($_FILES["cover"])) {
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryCreate->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryCreate->cover = $image;
            }

            if (!$categoryCreate->save()) {
                $json["message"] = $categoryCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("SubCategoria criada com sucesso...")->flash();
            $json["redirect"] = url("/admin/produtos/subcategory/{$categoryCreate->id}");

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryEdit = (new Subcategory())->findById($data["subcategory_id"]);

            if (!$categoryEdit) {
                $this->message->error("Você tentou editar uma subcategoria que não existe ou foi removida")->flash();
                echo json_encode(["redirect" => url("/admin/produtos/subcategories")]);
                return;
            }

            $categoryEdit->title = $data["title"];
            $categoryEdit->uri = str_slug($categoryEdit->title);
            $categoryEdit->description = $data["description"];

            //upload cover
            if (!empty($_FILES["cover"])) {
                if ($categoryEdit->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}")) {
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}");
                    (new Thumb())->flush($categoryEdit->cover);
                }

                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryEdit->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryEdit->cover = $image;
            }

            if (!$categoryEdit->save()) {
                $json["message"] = $categoryEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("SubCategoria atualizada com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryDelete = (new Subcategory())->findById($data["subcategory_id"]);

            if (!$categoryDelete) {
                $json["message"] = $this->message->error("A subcategoria não existe ou já foi excluída antes")->render();
                echo json_encode($json);
                return;
            }

            if ($categoryDelete->posts()->count()) {
                $json["message"] = $this->message->warning("Não é possível remover pois existem posts cadastrados")->render();
                echo json_encode($json);
                return;
            }

            if ($categoryDelete->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryDelete->cover}")) {
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryDelete->cover}");
                (new Thumb())->flush($categoryDelete->cover);
            }

            $categoryDelete->destroy();

            $this->message->success("A subcategoria foi excluída com sucesso...")->flash();
            echo json_encode(["reload" => true]);

            return;
        }

        $categoryEdit = null;
        if (!empty($data["subcategory_id"])) {
            $categoryId = filter_var($data["subcategory_id"], FILTER_VALIDATE_INT);
            $categoryEdit = (new Subcategory())->findById($categoryId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | SubCategoria",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/subcategory", [
            "app" => "service/subcategory",
            "head" => $head,
            "category" => $categoryEdit,
            "categories" => (new ServiceCategory())->find("type = :type", "type=post")->order("title")->fetch(true)
        ]);
    }

/**
*Category
**/

    /**
     * @param array|null $data
     */
    public function categories(?array $data): void
    {
        $categories = (new ServiceCategory())->find();
        $pager = new Pager(url("/admin/produtos/categories/"));
        $pager->pager($categories->count(), 6, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Categorias",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/categories", [
            "app" => "service/categories",
            "head" => $head,
            "categories" => $categories->order("title")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function category(?array $data): void
    {
        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $categoryCreate = new ServiceCategory();
            $categoryCreate->title = $data["title"];
            $categoryCreate->uri = str_slug($categoryCreate->title);
            $categoryCreate->description = $data["description"];

            //upload cover
            if (!empty($_FILES["cover"])) {
                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryCreate->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryCreate->cover = $image;
            }

            if (!$categoryCreate->save()) {
                $json["message"] = $categoryCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Categoria criada com sucesso...")->flash();
            $json["redirect"] = url("/admin/produtos/category/{$categoryCreate->id}");

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryEdit = (new ServiceCategory())->findById($data["category_id"]);

            if (!$categoryEdit) {
                $this->message->error("Você tentou editar uma categoria que não existe ou foi removida")->flash();
                echo json_encode(["redirect" => url("/admin/produtos/categories")]);
                return;
            }

            $categoryEdit->title = $data["title"];
            $categoryEdit->uri = str_slug($categoryEdit->title);
            $categoryEdit->description = $data["description"];

            //upload cover
            if (!empty($_FILES["cover"])) {
                if ($categoryEdit->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}")) {
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryEdit->cover}");
                    (new Thumb())->flush($categoryEdit->cover);
                }

                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $categoryEdit->title);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $categoryEdit->cover = $image;
            }

            if (!$categoryEdit->save()) {
                $json["message"] = $categoryEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Categoria atualizada com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryDelete = (new ServiceCategory())->findById($data["category_id"]);

            if (!$categoryDelete) {
                $json["message"] = $this->message->error("A categoria não existe ou já foi excluída antes")->render();
                echo json_encode($json);
                return;
            }

            if ($categoryDelete->posts()->count()) {
                $json["message"] = $this->message->warning("Não é possível remover pois existem posts cadastrados")->render();
                echo json_encode($json);
                return;
            }

            if ($categoryDelete->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryDelete->cover}")) {
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$categoryDelete->cover}");
                (new Thumb())->flush($categoryDelete->cover);
            }

            $categoryDelete->destroy();

            $this->message->success("A categoria foi excluída com sucesso...")->flash();
            echo json_encode(["reload" => true]);

            return;
        }

        $categoryEdit = null;
        if (!empty($data["category_id"])) {
            $categoryId = filter_var($data["category_id"], FILTER_VALIDATE_INT);
            $categoryEdit = (new ServiceCategory())->findById($categoryId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Categoria",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/service/category", [
            "app" => "service/categories",
            "head" => $head,
            "category" => $categoryEdit
        ]);
    }


}