<?php

namespace Source\App\Admin;

use Source\Models\Contacts;
use Source\Models\User;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * Class Users
 * @package Source\App\Admin
 */
class Contact extends Admin
{
    /**
     * Users constructor.
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
            echo json_encode(["redirect" => url("/admin/contact/home/{$s}/1")]);
            return;
        }

        $search = null;
        $users = (new Contacts())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $users = (new Contacts())->find("MATCH(first_name, email) AGAINST(:s)", "s={$search}");
            if (!$users->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/contact/home");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/contact/home/{$all}/"));
        $pager->pager($users->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Contacts",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/contact/home", [
            "app" => "contact/home",
            "head" => $head,
            "search" => $search,
            "contacts" => $users->order("created_at DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function post(?array $data): void
    {
       
        //delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $userDelete = (new Contacts())->findById($data["post_id"]);

            if (!$userDelete) {
                $this->message->error("Você tentnou deletar um contact que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/contact/home")]);
                return;
            }

           

            $userDelete->destroy();

            $this->message->success("O usuário foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/admin/contact/home")]);

            return;
        }

    

        $head = $this->seo->render(
            CONF_SITE_NAME . "Novo Contact",
            CONF_SITE_DESC,
            url("/admin"),
            url("/admin/assets/images/image.jpg"),
            false
        );

        echo $this->view->render("widgets/contact/home", [
            "app" => "contact/home",
            "head" => $head
        ]);
    }
}