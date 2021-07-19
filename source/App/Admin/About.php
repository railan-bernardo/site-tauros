<?php

namespace Source\App\Admin;

use Source\Core\Save;
use Source\Models\Aboutus;
use Source\Models\AbountGallery;
use Source\Models\User;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * Class Blog
 * @package Source\App\Admin
 */
class About extends Admin
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
            echo json_encode(["redirect" => url("/admin/about/home/{$s}/1")]);
            return;
        }

        $search = null;
        $posts = (new Aboutus())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $posts = (new Aboutus())->find("MATCH(title) AGAINST(:s)", "s={$search}");
            if (!$posts->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/about/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/about/home/{$all}/"));
        $pager->pager($posts->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | About Us",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/control/home", [
            "app" => "about/home",
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

            $postCover = new AbountGallery();
            $postCreate = new Aboutus();
            $postCreate->title = $data["title"];
            $postCreate->content = str_replace(["{title}"], [$postCreate->title], $content);
            $postCreate->post_at = date_fmt_back($data["post_at"]);

            //upload cover
           

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
                    $img = $upload->imageAll($file, time().$postCreate->title.$a);

                if (!$img) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
     
                     $postCover->cover_img = $img;
                    $postCover->id_about = $postCreate->id;
                    $save = new Save();

                   $create = $save->createGallery($postCover->cover_img, $postCover->id_about);
            
                    $a++;
                     
                }
            }
            
           
            if (!$create) {
                $json["message"] = $postCover->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Post publicado com sucesso...")->flash();
            $json["redirect"] = url("/admin/about/post/{$postCreate->id}");

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $content = $data["content"];
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postEdit = (new Aboutus())->findById($data["post_id"]);

            if (!$postEdit) {
                $this->message->error("Você tentou atualizar um post que não existe ou foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/about/home")]);
                return;
            }
             $postCoverEdit = new AbountGallery();
            $postEdit->title = $data["title"];
            $postEdit->content = str_replace(["{title}"], [$postEdit->title], $content);
            $postEdit->post_at = date_fmt_back($data["post_at"]);

             if (!$postEdit->save()) {
                $json["message"] = $postEdit->message()->render();
                echo json_encode($json);
                return;
            }

            //upload cover

            if(!empty($_FILES['cover_img'])){

                $galleryEdit = new AbountGallery();

            $findImgEdit = $galleryEdit->find()->fetch(true);

            foreach ($findImgEdit as $imgdl) {
                if($postEdit->id == $imgdl->id_about){
                     unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$imgdl->cover_img}");

                     $imgdl->destroy();
                }
            }

                $images = $_FILES['cover_img'];
                $upload = new Upload();
                for ($i = 0; $i < count($images['type']); $i++) {
                    foreach (array_keys($images) as $keys) {
                        $imageFiles[$i][$keys] = $images[$keys][$i];
                    }

                }

            $a = 1;
                foreach ($imageFiles as $file) {
                    $img = $upload->imageAll($file, time().$postEdit->title.$a);

                if (!$img) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }
     
                     $postCoverEdit->cover_img = $img;
                    $postCoverEdit->id_about = $postEdit->id;
                    $save = new Save();

                   $edit = $save->createGallery($postCoverEdit->cover_img, $postCoverEdit->id_about);
            
                    $a++;
                     
                }

                if (!$edit) {
                $json["message"] = $postCoverEdit->message()->render();
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
            $postDelete = (new Aboutus())->findById($data["post_id"]);

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
            $postEdit = (new Aboutus())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "New About Us"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/control/post", [
            "app" => "about/post",
            "head" => $head,
            "post" => $postEdit,
            "gallery"=>(new AbountGallery())->find()->fetch(true)
        ]);
    }

        /**
     * @param array|null $data
     * @throws \Exception
     */
    public function img(?array $data): void
    {


        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new AbountGallery())->findById($data["img_id"]);

            if (!$postDelete) {
                $this->message->error("Você tentou excluir um post que não existe ou já foi removido")->flash();
                echo json_encode(["reload" => true]);
                return;
            }

            if ($postDelete->cover_img && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover_img}")) {
                unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$postDelete->cover_img}");
                (new Thumb())->flush($postDelete->cover_img);
            }

            $postDelete->destroy();
            $this->message->success("Imagem excluído com sucesso...")->flash();

            echo json_encode(["reload" => true]);
            return;
        }

        $postEdit = null;
        if (!empty($data["img_id"])) {
            $postId = filter_var($data["img_id"], FILTER_VALIDATE_INT);
            $postEdit = (new AbountGallery())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "New About Us"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/control/post", [
            "app" => "about/post",
            "head" => $head,
            "post" => $postEdit,
            "gallery"=>(new AbountGallery())->find()->fetch(true)
        ]);

        
    }


   
}