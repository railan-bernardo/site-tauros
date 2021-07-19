<?php


namespace Source\App\Admin;

use Source\Models\Gallerys;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * Class Gallery
 * @package Source\App\Admin
 */
class Gallery extends Admin
{
    /**
     * Gallery constructor.
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

        $posts = (new Gallerys())->find();

        $pager = new Pager(url("/admin/gallery/home"));
        $pager->pager($posts->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Gallery",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/gallery/home", [
            "app" => "gallery/home",
            "head" => $head,
            "gallery" => $posts->limit($pager->limit())->offset($pager->offset())->order("post_at DESC")->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function posts(?array $data): void
    {

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);


            $postCreate = new Gallerys();
            $postCreate->title = $data["title"];
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

            $this->message->success("Post publicado com sucesso...")->flash();
            $json["redirect"] = url("/admin/gallery/home");

            echo json_encode($json);
            return;
        }


        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new Gallerys())->findById($data["post_id"]);

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


        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "New Gallery"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/gallery/post", [
            "app" => "gallery/post",
            "head" => $head
        ]);
    }
}