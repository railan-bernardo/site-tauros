<?php

namespace Source\App;

use Source\Models\Gallerys;
use Source\Models\AbountGallery;
use Source\Models\Brands;
use Source\Models\Service;
use Source\Models\PostSize;
use Source\Models\Supplier;
use Source\Support\Thumb;
use Source\Support\Upload;
use Source\Support\File;
use Source\Models\Slide;
use Source\Models\Subcategory;
use Source\Models\ServiceCategory;
use Source\Models\SitePost;
use Source\Models\Contacts;
use Source\Models\Aboutus;
use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Banner;
use Source\Models\ImageProduct;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Pager;

/**
 * Web Controller
 * @package Source\App
 */
class Web extends Controller
{
    /**
     * Web constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");

        (new Access())->report();
        (new Online())->report();
    }

    /**
     * SITE HOME
     */
    public function home(): void
    {

        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            $siteConfig->title,
            $siteConfig->description,
            url(),
             theme("/assets/images/share.png")
        );



        echo $this->view->render("home", [
            "head" => $head,
            "products" => (new Service())
                ->find("","title,uri,subtitle,content,cover")
                ->order("post_at DESC")
                ->limit(6)
                ->fetch(true),
                "banner"=>(new Slide())
                ->find("","title,slide")
                ->order("created_at")
                ->fetch(true),
                "brands"=>(new Brands())
                ->find()
                ->order("created_at")
                ->fetch(true),
             "site" => (new SitePost())
                ->find("","phone, phone_wp")
                ->order("created_at DESC")
                ->limit(1)
                ->fetch(true)
        ]);

    }

     /**
     * SITE ABOUT
     */
    public function about(): void
    {
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Sobre " . $siteConfig->title . " - " . $siteConfig->description,
            $siteConfig->description,
            url("/about"),
             theme("/assets/images/share.png")
        );

         $service = (new Aboutus())->find();
        $pager = new Pager(url("/about/s/"));
        $pager->pager($service->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("about", [
            "head" => $head,
            "gallery"=>(new AbountGallery())->find()->fetch(true),
            "about" =>$service->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator"=>$pager->render()
        ]);
    }

    /**
     * SITE GALLERY
     */
    public function gallery(): void
    {
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Gallery " . $siteConfig->title . " - " . $siteConfig->description,
            $siteConfig->description,
            url("/gallery"),
             theme("/assets/images/share.png")
        );

        $service = (new Gallerys())->find();
        $pager = new Pager(url("/gallery/s/"));
        $pager->pager($service->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("gallery", [
            "head" => $head,
            "gallery" =>$service->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator"=>$pager->render()
        ]);
    }

         /**
     * SITE CONTACT
     */
    public function contact(): void
    {
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Cadastro | " . $siteConfig->title . " - " . $siteConfig->description,
            $siteConfig->description,
            url("/contato"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("contact", [
            "head" => $head,
            "sites"=>(new SitePost())
            ->find()
            ->fetch(true)
        ]);
    }

             /**
     * SITE CONTACT
     */
    public function comfirm(): void
    {
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Obrigado | " . $siteConfig->title,
            $siteConfig->description,
            url("/obrigado"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("comfirm", [
            "head" => $head,
            "sites"=>(new SitePost())
            ->find()
            ->fetch(true)
        ]);
    }


         /**
     * SITE CONTACT POST
     */
           /**
     * @param array|null $data
     * @throws \Exception
     */
    public function contactPost(?array $data): void
    {

           if($data){

            $contact = new Contacts();
            $message = $contact->bootstrap(
                $data["first_name"],
                $data["email"],
                $data["phone"],
                $data["subject"],
                $data["msg"]
            );

            if ($contact->register($message)) {

                $json['redirect'] = url("/obrigado");
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
           }

        $siteConfig =  site()->findById(1);
         $head = $this->seo->render(
            "Enviar Contato - " . $siteConfig->title,
            $siteConfig->description,
            url("/cadastrar"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("contact", [
            "head" => $head
        ]);

    }

    /**
     * SITE PRODUCTS
     */
    public function products(array $data): void
    {
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Produtos " . " - " . $siteConfig->title ,
            $siteConfig->description,
            url("/produtos"),
             theme("/assets/images/share.png")
        );

         $products = (new Service())->find();
        $pager = new Pager(url("/produtos/p/"));
        $pager->pager($products->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("products", [
            "head" => $head,
            "pub"=>(new Banner())->find()->fetch(true),
            "products" =>$products->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator"=>$pager->render()
        ]);
    }

        /**
     * SITE PRODUCTS
     */
    public function orcament(array $data): void
    {
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Orçamento " . $siteConfig->title . " - " . $siteConfig->description,
            $siteConfig->description,
            url("/orcamento"),
             theme("/assets/images/share.png")
        );
       echo $this->view->render("budget", [
            "head" => $head,
            "sites"=>(new SitePost())
            ->find()
            ->fetch(true)
        ]);
    }

    /**
     * SITE SERVICE CATEGORY
     */
    public function serviceCategory(array $data): void
    {

        $categoryUri = filter_var($data["category"], FILTER_SANITIZE_STRIPPED);
        $category = (new ServiceCategory())->findByUri($categoryUri);

        if (!$category) {
            redirect("/services");
        }

        $serviceCategory = (new Service())->findPost("category = :c", "c={$category->id}");
        $page = (!empty($data['page']) && filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);
        $pager = new Pager(url("/services/category/{$category->uri}/"));
        $pager->pager($serviceCategory->count(), 9, $page);
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Serviço em {$category->title} - " . $siteConfig->title,
            $category->description,
            url("/services/category/{$category->uri}/{$page}"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("service-category", [
            "head" => $head,
            "title" => "Serviços em {$category->title}",
            "desc" => $category->description,
            "services" => $serviceCategory
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->order("post_at DESC")
                ->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

     /**
     * SITE SERVICE CATEGORY
     */
    public function serviceSubCategory(array $data): void
    {

        $categoryUri = filter_var($data["subcategory"], FILTER_SANITIZE_STRIPPED);
        $category = (new Subcategory())->findByUri($categoryUri);
        $categorysubUri = filter_var($data["category"], FILTER_SANITIZE_STRIPPED);
        $categorys = (new ServiceCategory())->findByUri($categorysubUri);
        if (!$category) {
            redirect("/services");
        }

        $serviceCategory = (new Service())->findPost("subcategory = :c", "c={$category->id}");
        $page = (!empty($data['page']) && filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);
        $pager = new Pager(url("/services/category/{$categorys->uri}/{$category->uri}/"));
        $pager->pager($serviceCategory->count(), 9, $page);
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Serviço em {$category->title} - " . $siteConfig->title,
            $category->description,
            url("/services/category/{$categorys->uri}/{$category->uri}/{$page}"),
            ($category->cover ? image($category->cover, 1200, 628) :  theme("/assets/images/share.png"))
        );

        echo $this->view->render("service-list", [
            "head" => $head,
            "title" => "Serviços em {$category->title}",
            "desc" => $category->description,
            "category"=>$categorys->title,
            "subcategory"=>$category->title,
            "services" => $serviceCategory
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->order("post_at DESC")
                ->fetch(true),
            "paginator" => $pager->render()
        ]);
    }







 /**
     * SITE  CATEGORY
     */
    public function allCategory(array $data): void
    {
        $categoryUri = filter_var($data['uri'], FILTER_SANITIZE_STRIPPED);
        $postCategory = (new ServiceCategory())->findByUri($categoryUri);

        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
           $postCategory->title  . " - " . $siteConfig->title,
            $siteConfig->description,
            url("/categoria"),
             theme("/assets/images/share.png")
        );

          //$subcategory = (new Subcategory())->findCat();

         $service = (new Service())->find("category = :category", "category={$postCategory->id}");
       
        $pager = new Pager(url("/categoria/s/"));
        $pager->pager($service->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("category", [
            "head" => $head,
            "brands"=>(new Brands())
            ->find()
            ->fetch(true),
            "site" => (new SitePost())
             ->find()
            ->order("created_at DESC")
            ->limit(1)
            ->fetch(true),
            "category"=>$postCategory->title,
            "products" =>$service->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator"=>$pager->render()
        ]);
    }

        public function brands(array $data): void
    {
        $brandUri = filter_var($data['uri'], FILTER_SANITIZE_STRIPPED);
        $postBrand = (new Brands())->findByUri($brandUri);

        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
             $postBrand->brand_name . " - " . $siteConfig->title,
            $siteConfig->description,
            url("/marcas"),
             theme("/assets/images/share.png")
        );

          //$subcategory = (new Subcategory())->findCat();

         $service = (new Service())->find("brand = :brand", "brand={$postBrand->brand_name}");
       
        $pager = new Pager(url("/marcas/s/"));
        $pager->pager($service->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("brand", [
            "head" => $head,
            "category"=>(new ServiceCategory())
            ->find()
            ->fetch(true),
            "brands"=>(new Brands())
            ->find()
            ->fetch(true),
            "site" => (new SitePost())
            ->find()
            ->order("created_at DESC")
            ->limit(1)
            ->fetch(true),
            "brandName"=>$postBrand->brand_name,
            "products" =>$service->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator"=>$pager->render()
        ]);
    }

    /**
     * SITE  SUPPLIER
     */
    public function forn(array $data): void
    {
        

        $siteConfig =  site()->findById(1);
$head = $this->seo->render(
             "Fornecedor ". " - " . $siteConfig->title,
            $siteConfig->description,
            url("/fornecedor"),
             theme("/assets/images/share.png")
        );
         $service = (new Supplier())->find();
       
        $pager = new Pager(url("/fornecedor/s/"));
        $pager->pager($service->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("fornecedor", [
            "head" => $head,
            "site" => (new SitePost())
             ->find()
            ->order("created_at DESC")
            ->limit(1)
            ->fetch(true),
            "products" =>$service->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator"=>$pager->render()
        ]);
    }

     /**
     * SITE SUBCATEGORY
     */
    public function allsubCategory(array $data): void
    {

       // $categoryUri = filter_var($data["uri"], FILTER_SANITIZE_STRIPPED);
        //$category = (new Subcategory())->findByUri($categoryUri);
        $categorysubUri = filter_var($data["uri"], FILTER_SANITIZE_STRIPPED);
        $categorys = (new ServiceCategory())->findByUri($categorysubUri);
        if (!$categorys) {
            redirect("/services");
        }

        $serviceCategory = (new Subcategory())->findPost("idcategory = :c", "c={$categorys->id}");
        $page = (!empty($data['page']) && filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);
        $pager = new Pager(url("/services/category/{$categorys->uri}/"));
        $pager->pager($serviceCategory->count(), 9, $page);
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Serviço em {$categorys->title} - " . $siteConfig->title,
            $categorys->description,
            url("/services/subcategories/{$categorys->uri}/{$page}"),
              theme("/assets/images/share.png")
        );

        echo $this->view->render("service-category", [
            "head" => $head,
            "title" => "Serviços em {$categorys->title}",
            "desc" => $categorys->description,
            "categoryuri"=>$categorys->uri,
            "category"=>$categorys->title,
            "services" => $serviceCategory
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->fetch(true),
            "paginator" => $pager->render()
        ]);
    }





     /**
     * SITE SERVICE POST
     * @param array $data
     */
    public function productDetail(array $data): void
    {
        $post = (new Service())->findByUri($data['uri']);
        if (!$post) {
            redirect("/404");
        }



        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "{$post->title} - " . $siteConfig->title,
            $post->subtitle,
            url("/service/{$post->uri}"),
            ($post->cover ? url("/storage/".$post->cover) :  theme("/assets/images/share.png"))
        );

        echo $this->view->render("product-details", [
            "head" => $head,
            "post" => $post,
            "photos"=> (new Gallerys())
            ->find("idservice = :idservice", "idservice={$post->id}")
            ->fetch(true),
             "site" => (new SitePost())
                ->find("","phone, phone_wp, msg")
                ->order("created_at DESC")
                ->limit(1)
                ->fetch(true),
            "related" => (new Service())
                ->findPost("category = :c AND id != :i","category = :d AND id != :e", "c={$post->category}&i={$post->id}", "d={$post->category}&id{$post->id}")
                ->order("rand()")
                ->limit(3)
                ->fetch(true)
        ]);
    }


     /**
      * budget
     * @param array|null $data
     * @throws \Exception
     */
    public function budgetPost(?array $data): void
    {
         $post = (new Service())->findByUri($data['uri']);
        //create
            if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCretate = new Budget();
            $postCretate->juridic = $data['juridic'];
            $postCretate->first_name = $data['first_name'];
            $postCretate->email = $data['email'];
            $postCretate->telephone = $data['telephone'];
            $postCretate->phone = $data['phone'];
            $postCretate->state = $data['state'];
            $postCretate->city = $data['city'];
            $postCretate->address = $data['address'];
            $postCretate->zipcode = $data['zipcode'];
            $postCretate->company = $data['company'];
            $postCretate->items = $data['items'];
            $postCretate->msg = $data['msg'];


            if (!$postCretate->save()) {
                $json["message"] = $postCretate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Entraremos em contato...")->flash();
            $json["redirect"] = url("/produto/{$post->uri}");

            echo json_encode($json);
            return;
        }

        $siteConfig =  site()->findById(1);

        $head = $this->seo->render(
            "{$post->title} - " . $siteConfig->title,
            $post->subtitle,
            url("/service/{$post->uri}"),
            ($post->cover ? url("/storage/".$post->cover) :  theme("/assets/images/share.png"))
        );

 echo $this->view->render("product-details", [
            "head" => $head,
            "post" => $post,
            "sizes"=> (new PostSize())
            ->find("", "idpost, size, color, weight, persons, cover")
            ->fetch(true),
            "photos"=> (new Gallerys())
            ->find("", "cover_img, idservice")
            ->fetch(true),
             "site" => (new SitePost())
                ->find("","phone, phone_wp")
                ->order("created_at DESC")
                ->limit(1)
                ->fetch(true),
            "related" => (new Service())
                ->findPost("category = :c AND id != :i","category = :d AND id != :e", "c={$post->category}&i={$post->id}", "d={$post->category}&id{$post->id}")
                ->order("rand()")
                ->limit(3)
                ->fetch(true)
        ]);
    }

     /**
      * budget
     * @param array|null $data
     * @throws \Exception
     */
    public function budgetsPost(?array $data): void
    {

        //create
            if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCretate = new Budget();
            $postCretate->juridic = $data['juridic'];
            $postCretate->first_name = $data['first_name'];
            $postCretate->email = $data['email'];
            $postCretate->telephone = ($data['telephone'] ?? "vazio");
            $postCretate->phone = ($data['phone'] ?? "vazio");
            $postCretate->state = ($data['state'] ?? "vazio");
            $postCretate->city = ($data['city'] ?? "vazio");
            $postCretate->address = ($data['address'] ?? "vazio");
            $postCretate->zipcode = ($data['zipcode'] ?? "vazio");
            $postCretate->company = ($data['company'] ?? "vazio");
            $postCretate->items = ($data['items'] ?? "vazio");
            $postCretate->msg = $data['msg'];


            if (!$postCretate->save()) {
                $json["message"] = $postCretate->message("Preencha todos os campos")->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Entraremos em contato...")->flash();
             $json["redirect"] = url("/orcamento");

            echo json_encode($json);
            return;
        }

        
    }

            /**
     * SITE PRODUCTS
     */
    public function jobHome(array $data): void
    {
        $siteConfig =  site()->findById(1);
        $head = $this->seo->render(
            "Vagas " . $siteConfig->title . " - " . $siteConfig->description,
            $siteConfig->description,
            url("/vagas"),
             theme("/assets/images/share.png")
        );
       echo $this->view->render("work", [
            "head" => $head,
            "sites"=>(new SitePost())
            ->find()
            ->fetch(true)
        ]);
    }

     /**
      * budget
     * @param array|null $data
     * @throws \Exception
     */
    public function jobPost(?array $data): void
    {

        //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $postCretate = new Jobs();
            $postCretate->first_name = $data['first_name'];
            $postCretate->email = $data['email'];
            $postCretate->address = $data['address'];
            $postCretate->zipcode = ($data['zipcode'] ?? "vazio");
            $postCretate->state = ($data['state'] ?? "vazio");
            $postCretate->city = ($data['city'] ?? "vazio");
            $postCretate->msg = ($data['msg'] ?? "vazio");

            //upload cover
            if (!empty($_FILES["anexo"])) {
                $files = $_FILES["anexo"];
                $upload = new Upload();
                $image = $upload->file($files, $postCretate->first_name);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $postCretate->anexo = $image;
            }

            if (!$postCretate->save()) {
                $json["message"] = $postCretate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Obrigado por enviar seu Curriculo...")->flash();
             $json["redirect"] = url("/vagas");

            echo json_encode($json);
            return;
        }

        
    }


    /**
     * SITE BLOG
     * @param array|null $data
     */
    public function blog(?array $data): void
    {
        $head = $this->seo->render(
            "Blog - " . $siteConfig->title,
            "Confira em nosso blog dicas?",
            url("/blog"),
             theme("/assets/images/share.png")
        );

        $blog = (new Post())->find();
        $pager = new Pager(url("/blog/p/"));
        $pager->pager($blog->count(), 9, ($data['page'] ?? 1));

        echo $this->view->render("blog", [
            "head" => $head,
            "blog" => $blog->order("post_at DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * SITE BLOG CATEGORY
     * @param array $data
     */
    public function blogCategory(array $data): void
    {
        $categoryUri = filter_var($data["category"], FILTER_SANITIZE_STRIPPED);
        $category = (new Category())->findByUri($categoryUri);

        if (!$category) {
            redirect("/blog");
        }

        $blogCategory = (new Post())->findPost("category = :c", "c={$category->id}");
        $page = (!empty($data['page']) && filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);
        $pager = new Pager(url("/blog/em/{$category->uri}/"));
        $pager->pager($blogCategory->count(), 9, $page);

        $head = $this->seo->render(
            "Artigos em {$category->title} - " . $siteConfig->title,
            $category->description,
            url("/blog/em/{$category->uri}/{$page}"),
            ($category->cover ? image($category->cover, 1200, 628) :  theme("/assets/images/share.png"))
        );

        echo $this->view->render("blog", [
            "head" => $head,
            "title" => "Artigos em {$category->title}",
            "desc" => $category->description,
            "blog" => $blogCategory
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->order("post_at DESC")
                ->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * SITE BLOG SEARCH
     * @param array $data
     */
    public function blogSearch(array $data): void
    {
        if (!empty($data['s'])) {
            $search = str_search($data['s']);
            echo json_encode(["redirect" => url("/blog/buscar/{$search}/1")]);
            return;
        }

        $search = str_search($data['search']);
        $page = (filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);

        if ($search == "all") {
            redirect("/blog");
        }

        $head = $this->seo->render(
            "Pesquisa por {$search} - " . $siteConfig->title,
            "Confira os resultados de sua pesquisa para {$search}",
            url("/blog/buscar/{$search}/{$page}"),
             theme("/assets/images/share.png")
        );

        $blogSearch = (new Post())->findPost("MATCH(title, subtitle) AGAINST(:s)", "s={$search}");

        if (!$blogSearch->count()) {
            echo $this->view->render("blog", [
                "head" => $head,
                "title" => "PESQUISA POR:",
                "search" => $search
            ]);
            return;
        }

        $pager = new Pager(url("/blog/buscar/{$search}/"));
        $pager->pager($blogSearch->count(), 9, $page);

        echo $this->view->render("blog", [
            "head" => $head,
            "title" => "PESQUISA POR:",
            "search" => $search,
            "blog" => $blogSearch->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * SITE BLOG POST
     * @param array $data
     */
    public function blogPost(array $data): void
    {
        $post = (new Post())->findByUri($data['uri']);
        if (!$post) {
            redirect("/404");
        }

        $user = Auth::user();
        if (!$user || $user->level < 5) {
            $post->views += 1;
            $post->save();
        }

        $head = $this->seo->render(
            "{$post->title} - " . $siteConfig->title,
            $post->subtitle,
            url("/blog/{$post->uri}"),
            ($post->cover ? image($post->cover, 1200, 628) :  theme("/assets/images/share.png"))
        );

        echo $this->view->render("blog-content", [
            "head" => $head,
            "post" => $post,
            "related" => (new Post())
                ->findPost("category = :c AND id != :i", "c={$post->category}&i={$post->id}")
                ->order("rand()")
                ->limit(3)
                ->fetch(true)
        ]);
    }

    /**
     * SITE LOGIN
     * @param null|array $data
     */
    public function login(?array $data): void
    {
        if (Auth::user()) {
            redirect("/app");
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (request_limit("weblogin", 3, 60 * 5)) {
                $json['message'] = $this->message->error("Você já efetuou 3 tentativas, esse é o limite. Por favor, aguarde 5 minutos para tentar novamente!")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu email e senha para entrar")->render();
                echo json_encode($json);
                return;
            }

            $save = (!empty($data['save']) ? true : false);
            $auth = new Auth();
            $login = $auth->login($data['email'], $data['password'], $save);

            if ($login) {
                $this->message->success("Seja bem-vindo(a) de volta " . Auth::user()->first_name . "!")->flash();
                $json['redirect'] = url("/app");
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Entrar - " . $siteConfig->title,
            $siteConfig->description,
            url("/entrar"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("auth-login", [
            "head" => $head,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }

    /**
     * SITE PASSWORD FORGET
     * @param null|array $data
     */
    public function forget(?array $data)
    {
        if (Auth::user()) {
            redirect("/app");
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["email"])) {
                $json['message'] = $this->message->info("Informe seu e-mail para continuar")->render();
                echo json_encode($json);
                return;
            }

            if (request_repeat("webforget", $data["email"])) {
                $json['message'] = $this->message->error("Ooops! Você já tentou este e-mail antes")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            if ($auth->forget($data["email"])) {
                $json["message"] = $this->message->success("Acesse seu e-mail para recuperar a senha")->render();
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Recuperar Senha - " . $siteConfig->title,
            $siteConfig->description,
            url("/recuperar"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("auth-forget", [
            "head" => $head
        ]);
    }

    /**
     * SITE FORGET RESET
     * @param array $data
     */
    public function reset(array $data): void
    {
        if (Auth::user()) {
            redirect("/app");
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["password"]) || empty($data["password_re"])) {
                $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
                echo json_encode($json);
                return;
            }

            list($email, $code) = explode("|", $data["code"]);
            $auth = new Auth();

            if ($auth->reset($email, $code, $data["password"], $data["password_re"])) {
                $this->message->success("Senha alterada com sucesso. Vamos controlar?")->flash();
                $json["redirect"] = url("/entrar");
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Crie sua nova senha no " . $siteConfig->title,
            $siteConfig->description,
            url("/recuperar"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("auth-reset", [
            "head" => $head,
            "code" => $data["code"]
        ]);
    }

    /**
     * SITE REGISTER
     * @param null|array $data
     */
    public function register(?array $data): void
    {
        if (Auth::user()) {
            redirect("/app");
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json['message'] = $this->message->info("Informe seus dados para criar sua conta.")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            $user = new User();
            $user->bootstrap(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["password"]
            );

            if ($auth->register($user)) {
                $json['redirect'] = url("/confirma");
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Criar Conta - " . $siteConfig->title,
            $siteConfig->description,
            url("/cadastrar"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("auth-register", [
            "head" => $head
        ]);
    }

    /**
     * SITE OPT-IN CONFIRM
     */
    public function confirm(): void
    {
        $head = $this->seo->render(
            "Confirme Seu Cadastro - " . $siteConfig->title,
            $siteConfig->description,
            url("/confirma"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("optin", [
            "head" => $head,
            "data" => (object)[
                "title" => "Falta pouco! Confirme seu cadastro.",
                "desc" => "Enviamos um link de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro e comece a controlar com o CaféControl",
                "image" => theme("/assets/images/optin-confirm.jpg")
            ]
        ]);
    }

    /**
     * SITE OPT-IN SUCCESS
     * @param array $data
     */
    public function success(array $data): void
    {
        $email = base64_decode($data["email"]);
        $user = (new User())->findByEmail($email);

        if ($user && $user->status != "confirmed") {
            $user->status = "confirmed";
            $user->save();
        }

        $head = $this->seo->render(
            "Bem-vindo(a) ao " . $siteConfig->title,
            $siteConfig->description,
            url("/obrigado"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("optin", [
            "head" => $head,
            "data" => (object)[
                "title" => "Tudo pronto. Você já pode controlar :)",
                "desc" => "Bem-vindo(a) ao seu controle de contas, vamos tomar um café?",
                "image" => theme("/assets/images/optin-success.jpg"),
                "link" => url("/entrar"),
                "linkTitle" => "Fazer Login"
            ],
            "track" => (object)[
                "fb" => "Lead",
                "aw" => "AW-953362805/yAFTCKuakIwBEPXSzMYD"
            ]
        ]);
    }

    /**
     * SITE TERMS
     */
    public function terms(): void
    {
        $head = $this->seo->render(
            $siteConfig->title . " - Termos de uso",
            $siteConfig->description,
            url("/termos"),
             theme("/assets/images/share.png")
        );

        echo $this->view->render("terms", [
            "head" => $head
        ]);
    }


    /**
     * SITE BLOG SEARCH
     * @param array $data
     */
    public function productSearch(array $data): void
    {
        if (!empty($data['s'])) {
            $search = str_search($data['s']);
            echo json_encode(["redirect" => url("/produtos/buscar/{$search}/1")]);
            return;
        }

        $search = str_search($data['search']);
        $page = (filter_var($data['page'], FILTER_VALIDATE_INT) >= 1 ? $data['page'] : 1);

        if ($search == "all") {
            redirect("/produtos");
        }

        $head = $this->seo->render(
            "Pesquisa por {$search} - " . CONF_SITE_NAME,
            "Confira os resultados de sua pesquisa para {$search}",
            url("/produtos/buscar/{$search}/{$page}"),
            theme("/assets/images/share.jpg")
        );

        $blogSearch = (new Service())->findPost("MATCH(title, subtitle) AGAINST(:s)", "s={$search}");

        if (!$blogSearch->count()) {
            echo $this->view->render("search", [
                "head" => $head,
                "title" => "PESQUISA POR:",
                "search" => $search,
                            "site" => (new SitePost())
                ->find("","phone, phone_wp")
                ->order("created_at DESC")
                ->limit(1)
                ->fetch(true)
            ]);
            return;
        }

        $pager = new Pager(url("/produtos/buscar/{$search}/"));
        $pager->pager($blogSearch->count(), 9, $page);

        echo $this->view->render("search", [
            "head" => $head,
            "title" => "PESQUISA POR:",
            "search" => $search,
            "site" => (new SitePost())
                ->find("","phone, phone_wp")
                ->order("created_at DESC")
                ->limit(1)
                ->fetch(true),
            "products" => $blogSearch->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * SITE NAV ERROR
     * @param array $data
     */
    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problemas":
                $error->code = "OPS";
                $error->title = "Estamos enfrentando problemas!";
                $error->message = "Parece que nosso serviço não está diponível no momento. Já estamos vendo isso mas caso precise, envie um e-mail :)";
                $error->linkTitle = "ENVIAR E-MAIL";
                $error->link = "mailto:" . CONF_MAIL_SUPPORT;
                break;

            case "manutencao":
                $error->code = "OPS";
                $error->title = "Desculpe. Estamos em manutenção!";
                $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para você";
                $error->linkTitle = null;
                $error->link = null;
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Ooops. Conteúdo indispinível :/";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponível no momento ou foi removido :/";
                $error->linkTitle = "Continue navegando!";
                $error->link = url_back();
                break;
        }

        $head = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("/ops/{$error->code}"),
             theme("/assets/images/share.png"),
            false
        );

        echo $this->view->render("error", [
            "head" => $head,
            "error" => $error
        ]);
    }
}