<?php

namespace Source\App\Admin;

use Source\Models\SitePost;
use Source\Models\User;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * Class Blog
 * @package Source\App\Admin
 */
class SiteInfo extends Admin
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
            echo json_encode(["redirect" => url("/admin/footer/home/{$s}/1")]);
            return;
        }

        $search = null;
        $posts = (new SitePost())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $posts = (new SitePost())->find("MATCH(title) AGAINST(:s)", "s={$search}");
            if (!$posts->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/footer/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/footer/home/{$all}/"));
        $pager->pager($posts->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Configurações",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/siteinfo/home", [
            "app" => "footer/home",
            "head" => $head,
            "posts" => $posts->limit($pager->limit())->offset($pager->offset())->order("created_at DESC")->fetch(true),
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
     
       

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCreate = new SitePost();
            $postCreate->title = $data["title"];
             $postCreate->description = $data["description"];
              $postCreate->phone = $data["phone"];
               $postCreate->msg = $data["msg"];
                $postCreate->phone_wp = $data["phone_wp"];
                 $postCreate->email = $data["email"];
                  $postCreate->facebook = $data["facebook"];
                   $postCreate->instagram = $data["instagram"];
                   $postCreate->copyright = $data["copyright"];
                   $postCreate->meta_tag = $data["meta_tag"];

            if (!$postCreate->save()) {
                $json["message"] = $postCreate->message()->render();
                echo json_encode($json);
                return;
            }
            $this->message->success("Post publicado com sucesso...")->flash();
            $json["redirect"] = url("/admin/footer/post/{$postCreate->id}");

            echo json_encode($json);
            return;
        }

        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
          
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postEdit = (new SitePost())->findById($data["post_id"]);

            if (!$postEdit) {
                $this->message->error("Você tentou atualizar um post que não existe ou foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/footer/home")]);
                return;
            }

            
            $postEdit->title = $data["title"];
             $postEdit->description = $data["description"];
              $postEdit->phone = $data["phone"];
               $postEdit->msg = $data["msg"];
                $postEdit->phone_wp = $data["phone_wp"];
                 $postEdit->email = $data["email"];
                  $postEdit->facebook = $data["facebook"];
                   $postEdit->instagram = $data["instagram"];
                   $postEdit->copyright = $data["copyright"];
                   $postEdit->meta_tag = $data["meta_tag"];

           

            if (!$postEdit->save()) {
                $json["message"] = $postEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Post atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $postDelete = (new SitePost())->findById($data["post_id"]);

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
            $postEdit = (new SitePost())->findById($postId);
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | " . ($postEdit->title ?? "Site Info"),
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/siteinfo/post", [
            "app" => "footer/post",
            "head" => $head,
            "post" => $postEdit
        ]);
    }


   
}